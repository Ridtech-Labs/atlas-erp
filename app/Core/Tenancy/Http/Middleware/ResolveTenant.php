<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Http\Middleware;

use App\Core\Tenancy\Models\Tenant;
use App\Core\Tenancy\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $tenant = $user?->tenant;

        if (! $tenant instanceof Tenant) {
            $tenant = null;
        }

        app(TenantContext::class)->set($tenant);

        view()->share('currentTenant', $tenant);

        return $next($request);
    }
}
