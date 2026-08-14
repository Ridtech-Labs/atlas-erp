<?php

declare(strict_types=1);

namespace App\Administration\Actions\Companies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateCompanyAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Tenant $tenant, array $data, User $actor): Tenant
    {
        if (! $actor->can(PermissionName::CompaniesUpdate->value) || ! $this->access->canManageTenant($actor, $tenant->getKey())) {
            throw new BusinessException('You are not allowed to update this company.', 403);
        }

        return DB::transaction(function () use ($tenant, $data, $actor): Tenant {
            $tenant->fill([
                ...$this->tenantAttributes($data),
                'slug' => (string) ($data['slug'] ?? Str::slug((string) ($data['name'] ?? $tenant->name))),
                'status' => (string) ($data['status'] ?? $tenant->getRawOriginal('status')),
            ]);
            $tenant->save();

            $company = $tenant->defaultCompany()->first() ?? Company::query()->create([
                'uuid' => (string) Str::uuid(),
                'tenant_id' => $tenant->getKey(),
                'name' => (string) ($data['name'] ?? $tenant->name),
                'legal_name' => (string) ($data['name'] ?? $tenant->name),
                'code' => strtoupper(substr((string) Str::slug((string) ($data['name'] ?? $tenant->name), ''), 0, 12)).'-001',
                'status' => (string) ($data['status'] ?? $tenant->getRawOriginal('status')),
                'is_default' => true,
            ]);

            $company->fill([
                'name' => (string) ($data['name'] ?? $tenant->name),
                'legal_name' => (string) ($data['name'] ?? $tenant->name),
                ...$this->companyProfileAttributes($data),
                'currency' => (string) ($data['currency'] ?? $company->currency ?? 'GHS'),
                'country' => $data['country'] ?? $company->country,
                'timezone' => (string) ($data['timezone'] ?? $company->timezone ?? 'Africa/Accra'),
                'status' => (string) ($data['status'] ?? $company->getRawOriginal('status')),
            ]);
            $company->save();

            $this->logger->log('company.updated', 'Company updated', $actor, $tenant, [
                'tenant_id' => $tenant->getKey(),
                'default_company_id' => $company->getKey(),
            ]);

            return $tenant->refresh();
        });
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function tenantAttributes(array $data): array
    {
        return Arr::only($data, [
            'uuid',
            'name',
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function companyProfileAttributes(array $data): array
    {
        return Arr::only($data, [
            'email',
            'phone',
            'logo_path',
            'address',
            'city',
        ]);
    }
}
