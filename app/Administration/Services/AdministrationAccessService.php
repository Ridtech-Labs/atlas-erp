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

    public function isCompanyAdministrator(?User $user): bool
    {
        return $user?->hasRole(RoleName::CompanyAdministrator->value) ?? false;
    }

    public function belongsToTenant(?User $actor, ?int $tenantId): bool
    {
        return $actor !== null && $tenantId !== null && $actor->tenant_id === $tenantId;
    }

    public function canAccessTenant(?User $actor, ?int $tenantId): bool
    {
        return $this->isSuperAdministrator($actor) || $this->belongsToTenant($actor, $tenantId);
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
