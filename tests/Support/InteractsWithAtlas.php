<?php

namespace Tests\Support;

use App\Core\Tenancy\Models\Tenant;
use App\Core\Tenancy\Support\TenantContext;

trait InteractsWithAtlas
{
    protected function setTenantContext(?Tenant $tenant): void
    {
        app(TenantContext::class)->set($tenant);
    }
}
