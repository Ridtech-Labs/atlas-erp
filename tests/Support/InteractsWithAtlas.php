<?php

namespace Tests\Support;

use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Core\Tenancy\Support\CompanyContext;
use App\Core\Tenancy\Support\TenantContext;

trait InteractsWithAtlas
{
    protected function setTenantContext(?Tenant $tenant): void
    {
        app(TenantContext::class)->set($tenant);
    }

    protected function setCompanyContext(?Company $company): void
    {
        app(CompanyContext::class)->set($company);
    }
}
