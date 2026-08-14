<?php

declare(strict_types=1);

namespace App\Administration\Services;

use App\Administration\Enums\RoleName;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Core\Tenancy\Support\ActiveCompanyResolver;
use App\Core\Tenancy\Support\ActiveTenantResolver;
use App\Models\User;
use Illuminate\Support\Collection;

class AdministrationAccessService
{
    public function __construct(
        private readonly ActiveTenantResolver $tenantResolver,
        private readonly ActiveCompanyResolver $companyResolver,
    ) {}

    public function isSuperAdministrator(?User $user): bool
    {
        return $user?->hasRole(RoleName::SuperAdministrator->value) ?? false;
    }

    public function isCompanyAdministrator(?User $user): bool
    {
        return $user?->hasRole(RoleName::CompanyAdministrator->value) ?? false;
    }

    public function belongsToTenant(?User $actor, ?int $tenantId): bool
    {
        return $actor !== null && $tenantId !== null && $actor->tenant_id === $tenantId;
    }

    public function hasActiveTenantContext(?User $actor): bool
    {
        return $this->tenantResolver->resolveFor($actor) !== null;
    }

    public function activeTenantId(?User $actor): ?int
    {
        return $this->tenantResolver->resolveFor($actor)?->getKey();
    }

    public function hasActiveCompanyContext(?User $actor): bool
    {
        return $this->companyResolver->resolveFor($actor) !== null;
    }

    public function activeCompany(?User $actor): ?Company
    {
        return $this->companyResolver->resolveFor($actor);
    }

    public function activeCompanyId(?User $actor): ?int
    {
        return $this->companyResolver->resolveFor($actor)?->getKey();
    }

    public function isPlatformSession(?User $actor): bool
    {
        return $this->tenantResolver->isPlatformSession($actor);
    }

    public function canAccessTenant(?User $actor, ?int $tenantId): bool
    {
        return $this->isSuperAdministrator($actor) || $this->belongsToTenant($actor, $tenantId);
    }

    public function canAccessOperationalTenant(?User $actor, ?int $tenantId): bool
    {
        if ($actor === null || $tenantId === null) {
            return false;
        }

        if ($this->isSuperAdministrator($actor)) {
            return $this->activeTenantId($actor) === $tenantId;
        }

        return $this->belongsToTenant($actor, $tenantId);
    }

    public function canAccessOperationalCompany(?User $actor, ?int $companyId, ?int $tenantId = null): bool
    {
        if ($actor === null || $companyId === null) {
            return false;
        }

        $company = Company::query()->find($companyId);

        if (! $company instanceof Company) {
            return false;
        }

        if ($tenantId !== null && $company->tenant_id !== $tenantId) {
            return false;
        }

        if (! $this->canAccessOperationalTenant($actor, $company->tenant_id)) {
            return false;
        }

        if ($this->hasTenantWideCompanyAccess($actor, $company->tenant)) {
            return true;
        }

        return $this->companyResolver->userCanAccessCompany($actor, $company)
            && $this->activeCompanyId($actor) === $company->getKey();
    }

    public function canAccessActiveOperationalCompany(?User $actor, ?int $companyId, ?int $tenantId = null): bool
    {
        if (! $this->canAccessOperationalCompany($actor, $companyId, $tenantId)) {
            return false;
        }

        return $companyId !== null && $this->activeCompanyId($actor) === $companyId;
    }

    /**
     * @return Collection<int, Company>
     */
    public function authorizedCompanies(?User $actor, ?Tenant $tenant = null): Collection
    {
        return $this->companyResolver->authorizedCompaniesFor($actor, $tenant);
    }

    public function hasTenantWideCompanyAccess(?User $actor, ?Tenant $tenant = null): bool
    {
        return $this->companyResolver->hasTenantWideCompanyAccess($actor, $tenant);
    }

    public function canManageTenant(?User $actor, ?int $tenantId): bool
    {
        return $this->canAccessTenant($actor, $tenantId);
    }

    public function canManageRole(User $actor, string $roleName): bool
    {
        if ($this->isSuperAdministrator($actor)) {
            return true;
        }

        if (! $this->isCompanyAdministrator($actor)) {
            return false;
        }

        return $roleName !== RoleName::SuperAdministrator->value;
    }

    public function canAssignRole(User $actor, string $roleName): bool
    {
        return $this->canManageRole($actor, $roleName);
    }

    /**
     * @param  list<string>  $newRoleNames
     */
    public function wouldRemoveFinalAdministrativeAccess(User $actor, User $subject, array $newRoleNames): bool
    {
        $isSelfEdit = $actor->is($subject);
        $retainsAdminRole = collect($newRoleNames)->intersect([
            RoleName::SuperAdministrator->value,
            RoleName::CompanyAdministrator->value,
        ])->isNotEmpty();

        if (! $isSelfEdit || $retainsAdminRole) {
            return false;
        }

        if ($this->isSuperAdministrator($actor)) {
            return User::query()
                ->whereKeyNot($actor->getKey())
                ->role(RoleName::SuperAdministrator->value)
                ->exists() === false;
        }

        return User::query()
            ->where('tenant_id', $actor->tenant_id)
            ->whereKeyNot($actor->getKey())
            ->where(function ($query): void {
                $query->role(RoleName::CompanyAdministrator->value)
                    ->orWhere(fn ($orQuery) => $orQuery->role(RoleName::SuperAdministrator->value));
            })
            ->exists() === false;
    }
}
