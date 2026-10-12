<?php

declare(strict_types=1);

namespace App\Inventory\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Inventory\Models\StockMovement;
use App\Models\User;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class StockMovementPolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::StockMovementsView->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function view(User $user, StockMovement $movement): bool
    {
        return $this->hasPermission($user, PermissionName::StockMovementsView->value)
            && $this->access->canAccessActiveOperationalCompany($user, $movement->company_id, $movement->tenant_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::StockMovementsManage->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function update(User $user, StockMovement $movement): bool
    {
        return false;
    }

    public function delete(User $user, StockMovement $movement): bool
    {
        return false;
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
