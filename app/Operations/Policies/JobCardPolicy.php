<?php

declare(strict_types=1);

namespace App\Operations\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Models\User;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Models\JobCard;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class JobCardPolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::JobsViewAny->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function view(User $user, JobCard $jobCard): bool
    {
        return $this->hasPermission($user, PermissionName::JobsView->value)
            && $this->access->canAccessActiveOperationalCompany($user, $jobCard->company_id, $jobCard->tenant_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::JobsCreate->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function update(User $user, JobCard $jobCard): bool
    {
        $canAccess = $this->access->canAccessActiveOperationalCompany($user, $jobCard->company_id, $jobCard->tenant_id);

        if (! $this->hasPermission($user, PermissionName::JobsUpdate->value) || ! $canAccess) {
            return false;
        }

        if ((string) $jobCard->getRawOriginal('approval_status') === JobCardApprovalStatus::Approved->value) {
            return $this->hasPermission($user, PermissionName::JobsApprove->value);
        }

        return true;
    }

    public function delete(User $user, JobCard $jobCard): bool
    {
        return $this->hasPermission($user, PermissionName::JobsDelete->value)
            && (string) $jobCard->getRawOriginal('approval_status') !== JobCardApprovalStatus::Approved->value
            && $this->access->canAccessActiveOperationalCompany($user, $jobCard->company_id, $jobCard->tenant_id);
    }

    public function approve(User $user, JobCard $jobCard): bool
    {
        return $this->hasPermission($user, PermissionName::JobsApprove->value)
            && $this->access->canAccessActiveOperationalCompany($user, $jobCard->company_id, $jobCard->tenant_id);
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
