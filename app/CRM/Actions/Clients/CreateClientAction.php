<?php

declare(strict_types=1);

namespace App\CRM\Actions\Clients;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Shared\Services\TenantSequenceService;
use App\Core\Tenancy\Models\Company;
use App\CRM\Models\Client;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateClientAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly TenantSequenceService $sequences,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, User $actor): Client
    {
        $company = $this->resolveCompany($data, $actor);
        $tenantId = $company->tenant_id;

        if (! $actor->hasPermissionTo(PermissionName::ClientsCreate->value)
            || ! $this->access->canAccessOperationalCompany($actor, $company->getKey(), $tenantId)) {
            throw new BusinessException('You are not allowed to create clients for the selected company.', 403);
        }

        return DB::transaction(function () use ($data, $actor, $tenantId, $company): Client {
            $attributes = [
                ...Arr::except($data, ['tenant_id', 'company_id', 'client_code']),
                'tenant_id' => $tenantId,
                'company_id' => $company->getKey(),
                'client_code' => $data['client_code'] ?? $this->generateClientCode($tenantId),
                'created_by' => $actor->getKey(),
                'updated_by' => $actor->getKey(),
            ];

            $client = new Client;
            $client->fill($attributes);

            foreach ($this->legacyCompatibilityAttributes($data) as $attribute => $value) {
                $client->setAttribute($attribute, $value);
            }

            $client->save();

            $this->logger->log('client.created', 'Client created', $actor, $client, [
                'tenant_id' => $client->tenant_id,
                'company_id' => $client->company_id,
                'client_code' => $client->client_code,
            ]);

            return $client->refresh();
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function resolveCompany(array $data, User $actor): Company
    {
        $tenantId = $this->access->activeTenantId($actor) ?? $actor->tenant_id;
        $activeCompany = $this->access->activeCompany($actor);

        if ($this->access->hasTenantWideCompanyAccess($actor)) {
            $selectedCompanyId = $data['company_id'] ?? $activeCompany?->getKey();
        } else {
            $selectedCompanyId = $activeCompany?->getKey();
        }

        if (! is_numeric($selectedCompanyId)) {
            throw new BusinessException('Select an active company before creating a client.', 422);
        }

        $company = Company::query()
            ->whereKey((int) $selectedCompanyId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (! $company instanceof Company || ! $this->access->canAccessOperationalCompany($actor, $company->getKey(), $tenantId)) {
            throw new BusinessException('You are not allowed to create clients for the selected company.', 403);
        }

        return $company;
    }

    private function generateClientCode(int $tenantId): string
    {
        $nextValue = $this->sequences->nextValue($tenantId, 'client_code');
        $highestExistingCode = Client::query()
            ->withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('client_code', 'like', 'CLI-%')
            ->orderByDesc('client_code')
            ->value('client_code');

        $highestExistingNumber = $this->extractClientCodeNumber($highestExistingCode);

        if ($highestExistingNumber >= $nextValue) {
            $nextValue = $highestExistingNumber + 1;
            $this->sequences->ensureAtLeast($tenantId, 'client_code', $nextValue);
        }

        return sprintf('CLI-%05d', $nextValue);
    }

    private function extractClientCodeNumber(?string $code): int
    {
        if (! is_string($code) || ! preg_match('/^CLI-(\d+)$/', $code, $matches)) {
            return 0;
        }

        return (int) $matches[1];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function legacyCompatibilityAttributes(array $data): array
    {
        $attributes = [];

        if (Schema::hasColumn('clients', 'name') && filled($data['legal_name'] ?? null)) {
            $attributes['name'] = $data['legal_name'];
        }

        return $attributes;
    }
}
