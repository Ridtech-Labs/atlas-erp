<?php

declare(strict_types=1);

namespace App\Operations\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Models\User;
use App\Operations\Models\Job;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class JobPolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::JobsViewAny->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function view(User $user, Job $job): bool
    {
        return $this->hasPermission($user, PermissionName::JobsView->value)
            && $this->access->canAccessActiveOperationalCompany($user, $job->company_id, $job->tenant_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::JobsCreate->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function update(User $user, Job $job): bool
    {
        return $this->hasPermission($user, PermissionName::JobsUpdate->value)
            && $this->access->canAccessActiveOperationalCompany($user, $job->company_id, $job->tenant_id);
    }

    public function delete(User $user, Job $job): bool
    {
        return $this->hasPermission($user, PermissionName::JobsDelete->value)
            && $this->access->canAccessActiveOperationalCompany($user, $job->company_id, $job->tenant_id);
    }

    public function submit(User $user, Job $job): bool
    {
        return $this->hasPermission($user, PermissionName::JobsSubmit->value)
            && $this->access->canAccessActiveOperationalCompany($user, $job->company_id, $job->tenant_id);
    }

    public function approve(User $user, Job $job): bool
    {
        return $this->hasPermission($user, PermissionName::JobsApprove->value)
            && $this->access->canAccessActiveOperationalCompany($user, $job->company_id, $job->tenant_id);
    }

    public function schedule(User $user, Job $job): bool
    {
        return $this->hasPermission($user, PermissionName::JobsSchedule->value)
            && $this->access->canAccessActiveOperationalCompany($user, $job->company_id, $job->tenant_id);
    }

    public function start(User $user, Job $job): bool
    {
        return $this->hasPermission($user, PermissionName::JobsStart->value)
            && $this->access->canAccessActiveOperationalCompany($user, $job->company_id, $job->tenant_id);
    }

    public function hold(User $user, Job $job): bool
    {
        return $this->hasPermission($user, PermissionName::JobsHold->value)
            && $this->access->canAccessActiveOperationalCompany($user, $job->company_id, $job->tenant_id);
    }

    public function resume(User $user, Job $job): bool
    {
        return $this->hasPermission($user, PermissionName::JobsResume->value)
            && $this->access->canAccessActiveOperationalCompany($user, $job->company_id, $job->tenant_id);
    }

    public function complete(User $user, Job $job): bool
    {
        return $this->hasPermission($user, PermissionName::JobsComplete->value)
            && $this->access->canAccessActiveOperationalCompany($user, $job->company_id, $job->tenant_id);
    }

    public function cancel(User $user, Job $job): bool
    {
        return $this->hasPermission($user, PermissionName::JobsCancel->value)
            && $this->access->canAccessActiveOperationalCompany($user, $job->company_id, $job->tenant_id);
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
