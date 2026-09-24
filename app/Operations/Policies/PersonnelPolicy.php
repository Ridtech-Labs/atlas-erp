<?php

declare(strict_types=1);

namespace App\Operations\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Models\User;
use App\Operations\Models\Personnel;

class PersonnelPolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionName::FleetAssetsView->value) && $this->access->hasActiveCompanyContext($user);
    }

    public function view(User $user, Personnel $personnel): bool
    {
        return $this->viewAny($user) && $this->access->canAccessActiveOperationalCompany($user, $personnel->company_id, $personnel->tenant_id);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionName::FleetAssetsManage->value) && $this->access->hasActiveCompanyContext($user);
    }

    public function update(User $user, Personnel $personnel): bool
    {
        return $this->create($user) && $this->access->canAccessActiveOperationalCompany($user, $personnel->company_id, $personnel->tenant_id);
    }

    public function delete(User $user, Personnel $personnel): bool
    {
        return $this->update($user, $personnel);
    }
}
