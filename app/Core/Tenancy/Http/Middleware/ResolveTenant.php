<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Http\Middleware;

use App\Core\Tenancy\Support\ActiveCompanyResolver;
use App\Core\Tenancy\Support\ActiveTenantResolver;
use App\Core\Tenancy\Support\CompanyContext;
use App\Core\Tenancy\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function __construct(
        private readonly ActiveTenantResolver $resolver,
        private readonly ActiveCompanyResolver $companyResolver,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $tenant = $this->resolver->resolveFor($user);
        $company = $this->companyResolver->resolveFor($user);

        app(TenantContext::class)->set($tenant);
        app(CompanyContext::class)->set($company);

        view()->share('currentTenant', $tenant);
        view()->share('currentCompany', $company);

        return $next($request);
    }
}
