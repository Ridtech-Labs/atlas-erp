<?php

declare(strict_types=1);

namespace App\Finance\Actions\RateAgreements;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\CRM\Models\Client;
use App\Finance\Enums\BillingUnit;
use App\Finance\Models\RateAgreement;
use App\Models\User;
use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateRateAgreementAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, User $actor): RateAgreement
    {
        if (! $actor->hasPermissionTo(PermissionName::RateAgreementsManage->value)) {
            throw new BusinessException('You are not allowed to manage Rate Agreements.', 403);
        }

        $client = $this->resolveClient($data, $actor);

        return DB::transaction(function () use ($data, $actor, $client): RateAgreement {
            $agreement = RateAgreement::query()->create([
                ...Arr::except($data, ['tenant_id', 'company_id', 'client_id', 'lines']),
                'uuid' => (string) Str::uuid(),
                'tenant_id' => $client->tenant_id,
                'company_id' => $client->company_id,
                'client_id' => $client->getKey(),
                'created_by' => $actor->getKey(),
                'updated_by' => $actor->getKey(),
            ]);

            $this->syncLines($agreement, (array) ($data['lines'] ?? []));

            $this->logger->log('rate_agreement.created', 'Rate Agreement created', $actor, $agreement, [
                'tenant_id' => $agreement->tenant_id,
                'company_id' => $agreement->company_id,
                'client_id' => $agreement->client_id,
            ]);

            return $agreement->refresh();
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function resolveClient(array $data, User $actor): Client
    {
        $clientId = $data['client_id'] ?? null;

        if (! is_numeric($clientId)) {
            throw new BusinessException('Select a client for this Rate Agreement.', 422);
        }

        $client = Client::query()->find((int) $clientId);

        if (! $client instanceof Client || ! $this->access->canAccessActiveOperationalCompany($actor, $client->company_id, $client->tenant_id)) {
            throw new BusinessException('You are not allowed to manage rates for the selected client.', 403);
        }

        return $client;
    }

    /**
     * @param  array<int, array<string, mixed>>  $lines
     */
    private function syncLines(RateAgreement $agreement, array $lines): void
    {
        if ($lines === []) {
            throw new BusinessException('Add at least one Rate Agreement line.', 422);
        }

        $agreement->lines()->delete();

        foreach ($lines as $line) {
            $agreement->lines()->create($this->normalizeLine($agreement, $line));
        }
    }

    /**
     * @param  array<string, mixed>  $line
     * @return array<string, mixed>
     */
    private function normalizeLine(RateAgreement $agreement, array $line): array
    {
        $equipmentReference = $this->normalizeOptionalString($line['equipment_reference'] ?? null);
        $machineNumber = $this->normalizeOptionalString($line['machine_number'] ?? null);
        $pickupPoint = $this->normalizeOptionalString($line['pickup_point'] ?? null);
        $destination = $this->normalizeOptionalString($line['destination'] ?? null);
        $unit = BillingUnit::tryFrom((string) ($line['billing_unit'] ?? ''));

        if (! $unit instanceof BillingUnit) {
            throw new BusinessException('Select a valid billing unit.', 422);
        }
        if ($unit === BillingUnit::Hourly && $equipmentReference === null && $machineNumber === null) {
            throw new BusinessException('Each Rate Agreement line must target either an equipment class/type or a specific machine.', 422);
        }
        if ($unit === BillingUnit::Trip && ($pickupPoint === null || $destination === null)) {
            throw new BusinessException('Trip rate lines require both pickup point and destination.', 422);
        }
        if (! is_numeric($line['rate'] ?? null) || (float) $line['rate'] <= 0) {
            throw new BusinessException('Rate Agreement line rates must be positive.', 422);
        }

        if ($unit === BillingUnit::Trip) {
            $equipmentReference = null;
            $machineNumber = null;
        } else {
            $pickupPoint = null;
            $destination = null;
        }

        return [
            'equipment_reference' => $equipmentReference,
            'machine_number' => $machineNumber,
            'pickup_point' => $pickupPoint,
            'destination' => $destination,
            'billing_unit' => $unit->value,
            'currency' => strtoupper((string) $line['currency']),
            'rate' => $line['rate'],
            'effective_from' => $this->normalizeInheritedDate($line['effective_from'] ?? null, $this->agreementDateString($agreement, 'effective_from')),
            'effective_to' => $this->normalizeInheritedDate($line['effective_to'] ?? null, $this->agreementDateString($agreement, 'effective_to')),
        ];
    }

    private function normalizeOptionalString(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $normalized = trim($value);

        return $normalized === '' ? null : preg_replace('/\s+/', ' ', $normalized);
    }

    private function normalizeInheritedDate(mixed $value, ?string $agreementDate): ?string
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        $normalized = trim($value);

        return $agreementDate !== null && $normalized === $agreementDate ? null : $normalized;
    }

    private function agreementDateString(RateAgreement $agreement, string $attribute): ?string
    {
        $rawValue = $agreement->getRawOriginal($attribute);

        if (is_string($rawValue) && $rawValue !== '') {
            return substr($rawValue, 0, 10);
        }

        $value = $agreement->getAttribute($attribute);

        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return is_string($value) && $value !== '' ? $value : null;
    }
}
