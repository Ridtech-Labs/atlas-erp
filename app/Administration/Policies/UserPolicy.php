<?php

declare(strict_types=1);

namespace App\Administration\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Enums\RoleName;
use App\Models\User;

class UserPolicy
{
    public function view(User $user, User $subject): bool
    {
        return $user->can(PermissionName::UsersView->value)
            && ($user->hasRole(RoleName::SuperAdministrator->value) || $user->tenant_id === $subject->tenant_id);
    }

    public function update(User $user, User $subject): bool
    {
        return $user->can(PermissionName::UsersUpdate->value)
            && ($user->hasRole(RoleName::SuperAdministrator->value) || $user->tenant_id === $subject->tenant_id);
    }
}
