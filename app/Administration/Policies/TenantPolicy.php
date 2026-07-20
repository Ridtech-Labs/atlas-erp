<?php

declare(strict_types=1);

namespace App\Administration\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Enums\RoleName;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;

class TenantPolicy
{
    public function view(User $user, Tenant $tenant): bool
    {
        return $user->can(PermissionName::CompaniesView->value)
            && ($user->hasRole(RoleName::SuperAdministrator->value) || $user->tenant_id === $tenant->getKey());
    }

    public function update(User $user, Tenant $tenant): bool
    {
        return $user->can(PermissionName::CompaniesUpdate->value)
            && ($user->hasRole(RoleName::SuperAdministrator->value) || $user->tenant_id === $tenant->getKey());
    }
}
