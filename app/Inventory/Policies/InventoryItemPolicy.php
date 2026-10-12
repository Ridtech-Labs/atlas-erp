<?php

declare(strict_types=1);

namespace App\Inventory\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Inventory\Models\InventoryItem;
use App\Models\User;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class InventoryItemPolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::InventoryItemsView->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function view(User $user, InventoryItem $item): bool
    {
        return $this->hasPermission($user, PermissionName::InventoryItemsView->value)
            && $this->access->canAccessActiveOperationalCompany($user, $item->company_id, $item->tenant_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::InventoryItemsManage->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function update(User $user, InventoryItem $item): bool
    {
        return $this->hasPermission($user, PermissionName::InventoryItemsManage->value)
            && $this->access->canAccessActiveOperationalCompany($user, $item->company_id, $item->tenant_id);
    }

    public function delete(User $user, InventoryItem $item): bool
    {
        return $this->update($user, $item);
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
