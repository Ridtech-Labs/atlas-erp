<?php

declare(strict_types=1);

namespace App\Finance\Actions\RateAgreements;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Finance\Models\RateAgreement;
use App\Models\User;
use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UpdateRateAgreementAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(RateAgreement $agreement, array $data, User $actor): RateAgreement
    {
        if (! $actor->hasPermissionTo(PermissionName::RateAgreementsManage->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $agreement->company_id, $agreement->tenant_id)) {
            throw new BusinessException('You are not allowed to manage this Rate Agreement.', 403);
        }

        if ((array) ($data['lines'] ?? []) === []) {
            throw new BusinessException('Add at least one Rate Agreement line.', 422);
        }

        return DB::transaction(function () use ($agreement, $data, $actor): RateAgreement {
            $agreement->fill(Arr::except($data, ['tenant_id', 'company_id', 'client_id', 'lines']));
            $agreement->updated_by = $actor->getKey();
            $agreement->save();

            $agreement->lines()->delete();

            foreach ((array) ($data['lines'] ?? []) as $line) {
                $agreement->lines()->create($this->normalizeLine($agreement, $line));
            }

            $this->logger->log('rate_agreement.updated', 'Rate Agreement updated', $actor, $agreement, [
                'tenant_id' => $agreement->tenant_id,
                'company_id' => $agreement->company_id,
                'client_id' => $agreement->client_id,
            ]);

            return $agreement->refresh();
        });
    }

    /**
     * @param  array<string, mixed>  $line
     * @return array<string, mixed>
     */
    private function normalizeLine(RateAgreement $agreement, array $line): array
    {
        $equipmentReference = $this->normalizeOptionalString($line['equipment_reference'] ?? null);
        $machineNumber = $this->normalizeOptionalString($line['machine_number'] ?? null);

        if ($equipmentReference === null && $machineNumber === null) {
            throw new BusinessException('Each Rate Agreement line must target either an equipment class/type or a specific machine.', 422);
        }

        return [
            'equipment_reference' => $equipmentReference,
            'machine_number' => $machineNumber,
            'billing_unit' => $line['billing_unit'],
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
