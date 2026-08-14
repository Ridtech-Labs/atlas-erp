<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Support;

use App\Administration\Enums\RoleName;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;

class ActiveTenantResolver
{
    public function resolveFor(?User $user): ?Tenant
    {
        if (! $user instanceof User) {
            return null;
        }

        if ($user->hasRole(RoleName::SuperAdministrator->value)) {
            $supportTenantId = session('support_tenant_id');

            if (is_numeric($supportTenantId)) {
                return Tenant::query()->find((int) $supportTenantId);
            }

            return null;
        }

        $tenant = $user->tenant;

        return $tenant instanceof Tenant ? $tenant : null;
    }

    public function isPlatformSession(?User $user): bool
    {
        return $user instanceof User
            && $user->hasRole(RoleName::SuperAdministrator->value)
            && $this->resolveFor($user) === null;
    }
}
