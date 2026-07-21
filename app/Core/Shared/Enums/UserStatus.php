<?php

declare(strict_types=1);

namespace App\Core\Shared\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';
    case Invited = 'invited';
}
