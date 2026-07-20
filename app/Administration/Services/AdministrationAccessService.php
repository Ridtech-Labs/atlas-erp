<?php

declare(strict_types=1);

namespace App\Administration\Services;

use App\Administration\Enums\RoleName;
use App\Models\User;

class AdministrationAccessService
{
    public function isSuperAdministrator(?User $user): bool
    {
        return $user?->hasRole(RoleName::SuperAdministrator->value) ?? false;
    }

    public function canManageTenant(?User $user, ?int $tenantId): bool
    {
        return $user !== null
            && ($this->isSuperAdministrator($user) || $user->tenant_id === $tenantId);
    }

    public function canAssignRole(User $actor, string $roleName): bool
    {
        if ($this->isSuperAdministrator($actor)) {
            return true;
        }

        if (! $actor->hasRole(RoleName::CompanyAdministrator->value)) {
            return false;
        }

        return $roleName !== RoleName::SuperAdministrator->value;
    }
}
