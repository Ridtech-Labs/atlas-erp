<?php

declare(strict_types=1);

namespace App\Finance\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Finance\Enums\BillingRecordStatus;
use App\Finance\Models\BillingRecord;
use App\Models\User;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class BillingRecordPolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::BillingRecordsView->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function view(User $user, BillingRecord $record): bool
    {
        return $this->hasPermission($user, PermissionName::BillingRecordsView->value)
            && $this->access->canAccessOperationalCompany($user, $record->company_id, $record->tenant_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::BillingRecordsManage->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function update(User $user, BillingRecord $record): bool
    {
        return $this->hasPermission($user, PermissionName::BillingRecordsManage->value)
            && (string) $record->getRawOriginal('status') === BillingRecordStatus::Draft->value
            && $this->access->canAccessOperationalCompany($user, $record->company_id, $record->tenant_id);
    }

    public function delete(User $user, BillingRecord $record): bool
    {
        return $this->update($user, $record);
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
