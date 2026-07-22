<?php

declare(strict_types=1);

namespace App\CRM\Policies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\CRM\Models\Client;
use App\Models\User;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class ClientPolicy
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::ClientsViewAny->value);
    }

    public function view(User $user, Client $client): bool
    {
        return $this->hasPermission($user, PermissionName::ClientsView->value)
            && $this->access->canAccessTenant($user, $client->tenant_id);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, PermissionName::ClientsCreate->value);
    }

    public function update(User $user, Client $client): bool
    {
        return $this->hasPermission($user, PermissionName::ClientsUpdate->value)
            && $this->access->canAccessTenant($user, $client->tenant_id);
    }

    public function delete(User $user, Client $client): bool
    {
        return $this->hasPermission($user, PermissionName::ClientsDelete->value)
            && $this->access->canAccessTenant($user, $client->tenant_id);
    }

    public function restore(User $user, Client $client): bool
    {
        return $this->hasPermission($user, PermissionName::ClientsRestore->value)
            && $this->access->canAccessTenant($user, $client->tenant_id);
    }

    public function forceDelete(User $user, Client $client): bool
    {
        return $this->hasPermission($user, PermissionName::ClientsForceDelete->value)
            && $this->access->canAccessTenant($user, $client->tenant_id);
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
