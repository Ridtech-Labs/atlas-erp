<?php

declare(strict_types=1);

namespace App\Inventory\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Inventory\Models\StorageLocation;
use App\Models\User;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class StorageLocationPolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::StorageLocationsView->value) && $this->access->hasActiveCompanyContext($user);
    }

    public function view(User $user, StorageLocation $location): bool
    {
        return $this->hasPermission($user, PermissionName::StorageLocationsView->value) && $this->access->canAccessActiveOperationalCompany($user, $location->company_id, $location->tenant_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::StorageLocationsManage->value) && $this->access->hasActiveCompanyContext($user);
    }

    public function update(User $user, StorageLocation $location): bool
    {
        return $this->hasPermission($user, PermissionName::StorageLocationsManage->value) && $this->access->canAccessActiveOperationalCompany($user, $location->company_id, $location->tenant_id);
    }

    public function delete(User $user, StorageLocation $location): bool
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
