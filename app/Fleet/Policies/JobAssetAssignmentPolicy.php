<?php

declare(strict_types=1);

namespace App\Fleet\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Fleet\Models\JobAssetAssignment;
use App\Models\User;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class JobAssetAssignmentPolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::FleetAssignmentsView->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function view(User $user, JobAssetAssignment $assignment): bool
    {
        return $this->hasPermission($user, PermissionName::FleetAssignmentsView->value)
            && $this->access->canAccessActiveOperationalCompany($user, $assignment->company_id, $assignment->tenant_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::FleetAssignmentsManage->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function update(User $user, JobAssetAssignment $assignment): bool
    {
        return $this->hasPermission($user, PermissionName::FleetAssignmentsManage->value)
            && $this->access->canAccessActiveOperationalCompany($user, $assignment->company_id, $assignment->tenant_id);
    }

    public function release(User $user, JobAssetAssignment $assignment): bool
    {
        return $this->update($user, $assignment);
    }

    public function cancel(User $user, JobAssetAssignment $assignment): bool
    {
        return $this->update($user, $assignment);
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
