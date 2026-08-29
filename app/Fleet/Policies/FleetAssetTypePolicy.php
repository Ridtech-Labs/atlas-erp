<?php

declare(strict_types=1);

namespace App\Fleet\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Fleet\Models\FleetAssetType;
use App\Models\User;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class FleetAssetTypePolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::FleetAssetsView->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function view(User $user, FleetAssetType $type): bool
    {
        return $this->hasPermission($user, PermissionName::FleetAssetsView->value)
            && $this->access->canAccessActiveOperationalCompany($user, $type->company_id, $type->tenant_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::FleetAssetsManage->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function update(User $user, FleetAssetType $type): bool
    {
        return $this->hasPermission($user, PermissionName::FleetAssetsManage->value)
            && $this->access->canAccessActiveOperationalCompany($user, $type->company_id, $type->tenant_id);
    }

    public function delete(User $user, FleetAssetType $type): bool
    {
        return $this->update($user, $type) && ! $type->assets()->exists();
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
