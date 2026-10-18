<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Services;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;

class SupportTenantWorkspaceService
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    public function canEnter(User $actor, Tenant $tenant): bool
    {
        return $this->access->isSuperAdministrator($actor)
            && $this->access->isPlatformSession($actor)
            && $tenant->isActive()
            && $this->access->canAccessTenant($actor, $tenant->getKey());
    }

    public function enter(User $actor, Tenant $tenant): void
    {
        abort_unless($this->canEnter($actor, $tenant), 403);

        $this->regenerateContext($tenant->getKey());
    }

    public function canExit(User $actor): bool
    {
        return $this->access->isSuperAdministrator($actor)
            && $this->access->hasActiveTenantContext($actor);
    }

    public function exit(User $actor): void
    {
        abort_unless($this->canExit($actor), 403);

        $this->regenerateContext();
    }

    private function regenerateContext(?int $tenantId = null): void
    {
        $session = session();

        $session->forget(['support_tenant_id', 'active_company_id']);
        $session->regenerate();

        if ($tenantId !== null) {
            $session->put('support_tenant_id', $tenantId);
        }
    }
}
