<?php

declare(strict_types=1);

namespace App\Core\Shared\Enums;

enum TenantStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';
}
