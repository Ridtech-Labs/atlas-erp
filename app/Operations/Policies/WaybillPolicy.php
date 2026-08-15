<?php

declare(strict_types=1);

namespace App\Operations\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Models\User;
use App\Operations\Models\Waybill;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class WaybillPolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::JobsViewAny->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function view(User $user, Waybill $waybill): bool
    {
        return $this->hasPermission($user, PermissionName::JobsView->value)
            && $this->access->canAccessActiveOperationalCompany($user, $waybill->company_id, $waybill->tenant_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::JobsCreate->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function update(User $user, Waybill $waybill): bool
    {
        return $this->hasPermission($user, PermissionName::JobsUpdate->value)
            && $this->access->canAccessActiveOperationalCompany($user, $waybill->company_id, $waybill->tenant_id);
    }

    public function delete(User $user, Waybill $waybill): bool
    {
        return $this->hasPermission($user, PermissionName::JobsDelete->value)
            && $this->access->canAccessActiveOperationalCompany($user, $waybill->company_id, $waybill->tenant_id);
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
