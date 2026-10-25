<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Http\Middleware;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Support\ActiveTenantResolver;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureTenantLogoutRedirect
{
    public function __construct(
        private readonly ActiveTenantResolver $tenantResolver,
        private readonly AdministrationAccessService $access,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($request->routeIs('filament.admin.auth.logout')
            && $user instanceof User
            && ! $this->access->isSuperAdministrator($user)) {
            $tenant = $this->tenantResolver->resolveFor($user);

            if ($tenant?->isActive()) {
                $request->attributes->set('atlas.logout_tenant_slug', $tenant->slug);
            }
        }

        return $next($request);
    }
}
