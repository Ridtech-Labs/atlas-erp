<?php

declare(strict_types=1);

namespace App\Administration\Actions\Companies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateCompanyAction
{
    public function __construct(
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, User $actor): Tenant
    {
        if (! $actor->can(PermissionName::CompaniesCreate->value)) {
            throw new BusinessException('You are not allowed to create companies.', 403);
        }

        return DB::transaction(function () use ($data, $actor): Tenant {
            $tenant = Tenant::query()->create([
                ...$this->tenantAttributes($data),
                'slug' => (string) ($data['slug'] ?? Str::slug((string) $data['name'])),
                'status' => (string) ($data['status'] ?? 'active'),
            ]);

            $company = Company::query()->create([
                'uuid' => (string) Str::uuid(),
                'tenant_id' => $tenant->getKey(),
                'name' => (string) $tenant->name,
                'legal_name' => (string) $tenant->name,
                ...$this->companyProfileAttributes($data),
                'code' => strtoupper(substr((string) Str::slug((string) $tenant->name, ''), 0, 12)).'-001',
                'status' => (string) $tenant->getRawOriginal('status'),
                'currency' => (string) ($data['currency'] ?? 'GHS'),
                'country' => $data['country'] ?? null,
                'timezone' => (string) ($data['timezone'] ?? 'Africa/Accra'),
                'is_default' => true,
            ]);

            $this->logger->log('company.created', 'Company created', $actor, $tenant, [
                'tenant_id' => $tenant->getKey(),
                'default_company_id' => $company->getKey(),
            ]);

            return $tenant;
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
