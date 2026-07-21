<?php

declare(strict_types=1);

namespace App\Administration\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class TenantPolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::CompaniesView->value);
    }

    public function view(User $user, Tenant $tenant): bool
    {
        return $this->access->canAccessTenant($user, $tenant->id)
            && (
                $this->hasPermission($user, PermissionName::CompaniesView->value)
                || $this->access->isCompanyAdministrator($user)
            );
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::CompaniesCreate->value)
            && $this->access->isSuperAdministrator($user);
    }

    public function update(User $user, Tenant $tenant): bool
    {
        return $this->access->canAccessTenant($user, $tenant->id)
            && (
                $this->hasPermission($user, PermissionName::CompaniesUpdate->value)
                || $this->access->isCompanyAdministrator($user)
            );
    }

    public function delete(User $user, Tenant $tenant): bool
    {
        return $this->hasPermission($user, PermissionName::CompaniesDelete->value)
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

    private function hasPermission(User $user, string $permission): bool
    {
        try {
            return $user->hasPermissionTo($permission);
        } catch (PermissionDoesNotExist) {
            return false;
        }
    }
}
