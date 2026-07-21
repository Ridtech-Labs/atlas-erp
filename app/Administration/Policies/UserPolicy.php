<?php

declare(strict_types=1);

namespace App\Administration\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Models\User;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class UserPolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::UsersView->value);
    }

    public function view(User $user, User $subject): bool
    {
        return $this->hasPermission($user, PermissionName::UsersView->value)
            && $this->access->canAccessTenant($user, $subject->tenant_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::UsersCreate->value);
    }

    public function update(User $user, User $subject): bool
    {
        return $this->hasPermission($user, PermissionName::UsersUpdate->value)
            && $this->access->canAccessTenant($user, $subject->tenant_id);
    }

    public function delete(User $user, User $subject): bool
    {
        return $this->hasPermission($user, PermissionName::UsersDelete->value)
            && $this->access->canAccessTenant($user, $subject->tenant_id);
    }

    public function restore(User $user, User $subject): bool
    {
        return $this->delete($user, $subject);
    }

    public function forceDelete(User $user, User $subject): bool
    {
        return $this->delete($user, $subject);
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
