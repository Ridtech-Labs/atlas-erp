<?php

declare(strict_types=1);

namespace App\Administration\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Enums\RoleName;
use App\Administration\Services\AdministrationAccessService;
use App\Models\User;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::RolesView->value);
    }

    public function view(User $user, Role $role): bool
    {
        return $this->viewAny($user) && $this->access->canManageRole($user, $role->name);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::RolesCreate->value)
            && $this->access->isSuperAdministrator($user);
    }

    public function update(User $user, Role $role): bool
    {
        return $this->hasPermission($user, PermissionName::RolesUpdate->value)
            && $this->access->canManageRole($user, $role->name);
    }

    public function delete(User $user, Role $role): bool
    {
        return $this->hasPermission($user, PermissionName::RolesDelete->value)
            && $this->access->isSuperAdministrator($user)
            && $role->name !== RoleName::SuperAdministrator->value;
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
