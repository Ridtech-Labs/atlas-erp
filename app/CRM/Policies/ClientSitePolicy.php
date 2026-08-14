<?php

declare(strict_types=1);

namespace App\CRM\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\CRM\Models\ClientSite;
use App\Models\User;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class ClientSitePolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::ClientSitesViewAny->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function view(User $user, ClientSite $site): bool
    {
        return $this->hasPermission($user, PermissionName::ClientSitesView->value)
            && $this->access->canAccessOperationalCompany($user, $site->company_id, $site->tenant_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::ClientSitesCreate->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function update(User $user, ClientSite $site): bool
    {
        return $this->hasPermission($user, PermissionName::ClientSitesUpdate->value)
            && $this->access->canAccessOperationalCompany($user, $site->company_id, $site->tenant_id);
    }

    public function delete(User $user, ClientSite $site): bool
    {
        return $this->hasPermission($user, PermissionName::ClientSitesDelete->value)
            && $this->access->canAccessOperationalCompany($user, $site->company_id, $site->tenant_id);
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
