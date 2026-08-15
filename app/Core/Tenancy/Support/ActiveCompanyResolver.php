<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Support;

use App\Administration\Enums\RoleName;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Collection;

class ActiveCompanyResolver
{
    public function __construct(
        private readonly ActiveTenantResolver $tenantResolver,
        private readonly CompanyContext $companyContext,
    ) {}

    public function resolveFor(?User $user): ?Company
    {
        if (! $user instanceof User) {
            return null;
        }

        $contextCompany = $this->companyContext->company();

        if ($contextCompany instanceof Company && $this->userCanAccessCompany($user, $contextCompany)) {
            return $contextCompany;
        }

        $tenant = $this->tenantResolver->resolveFor($user);

        if (! $tenant instanceof Tenant) {
            return null;
        }

        $storedCompanyId = session('active_company_id');

        if (is_numeric($storedCompanyId)) {
            $storedCompany = Company::query()
                ->whereKey((int) $storedCompanyId)
                ->where('tenant_id', $tenant->getKey())
                ->first();

            if ($storedCompany instanceof Company && $this->userCanAccessCompany($user, $storedCompany)) {
                return $storedCompany;
            }

            session()->forget('active_company_id');
        }

        $authorizedCompanies = $this->authorizedCompaniesFor($user, $tenant);

        if ($authorizedCompanies->count() === 1) {
            $company = $authorizedCompanies->first();

            if ($company instanceof Company) {
                session(['active_company_id' => $company->getKey()]);
            }

            return $company;
        }

        return null;
    }

    /**
     * @return Collection<int, Company>
     */
    public function authorizedCompaniesFor(?User $user, ?Tenant $tenant = null): Collection
    {
        if (! $user instanceof User) {
            return collect();
        }

        $tenant ??= $this->tenantResolver->resolveFor($user);

        if (! $tenant instanceof Tenant) {
            return collect();
        }

        $query = Company::query()
            ->where('tenant_id', $tenant->getKey())
            ->orderBy('name');

        if ($this->hasTenantWideCompanyAccess($user, $tenant)) {
            return $query->get();
        }

        return $user->companies()
            ->where('companies.tenant_id', $tenant->getKey())
            ->orderBy('companies.name')
            ->get();
    }

    public function userCanAccessCompany(?User $user, ?Company $company): bool
    {
        if (! $user instanceof User || ! $company instanceof Company) {
            return false;
        }

        $tenant = $this->tenantResolver->resolveFor($user);

        if (! $tenant instanceof Tenant || $tenant->getKey() !== $company->tenant_id) {
            return false;
        }

        if ($this->hasTenantWideCompanyAccess($user, $tenant)) {
            return true;
        }

        return $user->companies()
            ->whereKey($company->getKey())
            ->exists();
    }

    public function hasTenantWideCompanyAccess(?User $user, ?Tenant $tenant = null): bool
    {
        if (! $user instanceof User) {
            return false;
        }

        $tenant ??= $this->tenantResolver->resolveFor($user);

        if (! $tenant instanceof Tenant || $tenant->getKey() !== $user->tenant_id) {
            return false;
        }

        return $user->hasRole(RoleName::SuperAdministrator->value);
    }
}
