<?php

declare(strict_types=1);

namespace App\CRM\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\CRM\Models\ClientContact;
use App\Models\User;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class ClientContactPolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::ClientContactsViewAny->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function view(User $user, ClientContact $contact): bool
    {
        return $this->hasPermission($user, PermissionName::ClientContactsView->value)
            && $this->access->canAccessOperationalCompany($user, $contact->company_id, $contact->tenant_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::ClientContactsCreate->value)
            && $this->access->hasActiveCompanyContext($user);
    }

    public function update(User $user, ClientContact $contact): bool
    {
        return $this->hasPermission($user, PermissionName::ClientContactsUpdate->value)
            && $this->access->canAccessOperationalCompany($user, $contact->company_id, $contact->tenant_id);
    }

    public function delete(User $user, ClientContact $contact): bool
    {
        return $this->hasPermission($user, PermissionName::ClientContactsDelete->value)
            && $this->access->canAccessOperationalCompany($user, $contact->company_id, $contact->tenant_id);
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
