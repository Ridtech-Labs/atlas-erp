<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Support;

use App\Core\Tenancy\Models\Tenant;

class TenantContext
{
    private ?Tenant $tenant = null;

    public function set(?Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    public function tenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function id(): ?int
    {
        return $this->tenant?->getKey();
    }
}
