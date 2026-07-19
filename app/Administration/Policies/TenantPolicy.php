<?php

declare(strict_types=1);

namespace App\Administration\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;

class TenantPolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $user->can(PermissionName::CompaniesView->value);
    }

    public function view(User $user, Tenant $tenant): bool
    {
        return $user->can(PermissionName::CompaniesView->value)
            && $this->access->canAccessTenant($user, $tenant->id);
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionName::CompaniesCreate->value)
            && $this->access->isSuperAdministrator($user);
    }

    public function update(User $user, Tenant $tenant): bool
    {
        return $user->can(PermissionName::CompaniesUpdate->value)
            && $this->access->canAccessTenant($user, $tenant->id);
    }

    public function delete(User $user, Tenant $tenant): bool
    {
        return $user->can(PermissionName::CompaniesDelete->value)
            && $this->access->isSuperAdministrator($user);
    }

    public function restore(User $user, Tenant $tenant): bool
    {
        return $this->delete($user, $tenant);
    }

    public function forceDelete(User $user, Tenant $tenant): bool
    {
        return $this->delete($user, $tenant);
    }
}
