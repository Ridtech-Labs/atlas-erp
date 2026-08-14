<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Support;

use App\Core\Tenancy\Models\Company;

class CompanyContext
{
    private ?Company $company = null;

    public function set(?Company $company): void
    {
        $this->company = $company;
    }

    public function company(): ?Company
    {
        return $this->company;
    }

    public function id(): ?int
    {
        return $this->company?->getKey();
    }
}
