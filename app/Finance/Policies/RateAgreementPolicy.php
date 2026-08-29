<?php

declare(strict_types=1);

namespace App\Finance\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Finance\Models\RateAgreement;
use App\Models\User;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class RateAgreementPolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::RateAgreementsView->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function view(User $user, RateAgreement $rateAgreement): bool
    {
        return $this->hasPermission($user, PermissionName::RateAgreementsView->value)
            && $this->access->canAccessOperationalCompany($user, $rateAgreement->company_id, $rateAgreement->tenant_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::RateAgreementsManage->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function update(User $user, RateAgreement $rateAgreement): bool
    {
        return $this->hasPermission($user, PermissionName::RateAgreementsManage->value)
            && $this->access->canAccessOperationalCompany($user, $rateAgreement->company_id, $rateAgreement->tenant_id);
    }

    public function delete(User $user, RateAgreement $rateAgreement): bool
    {
        return $this->update($user, $rateAgreement);
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
