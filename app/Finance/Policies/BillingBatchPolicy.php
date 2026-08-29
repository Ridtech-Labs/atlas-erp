<?php

declare(strict_types=1);

namespace App\Finance\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Finance\Models\BillingBatch;
use App\Models\User;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class BillingBatchPolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::BillingBatchesView->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function view(User $user, BillingBatch $billingBatch): bool
    {
        return $this->hasPermission($user, PermissionName::BillingBatchesView->value)
            && $this->access->canAccessOperationalCompany($user, $billingBatch->company_id, $billingBatch->tenant_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::BillingBatchesManage->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function update(User $user, BillingBatch $billingBatch): bool
    {
        return $this->hasPermission($user, PermissionName::BillingBatchesManage->value)
            && $this->access->canAccessOperationalCompany($user, $billingBatch->company_id, $billingBatch->tenant_id);
    }

    public function delete(User $user, BillingBatch $billingBatch): bool
    {
        return $this->update($user, $billingBatch);
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
