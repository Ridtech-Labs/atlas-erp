<?php

declare(strict_types=1);

namespace App\Finance\Enums;

enum RateAgreementStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Expired => 'Expired',
        };
    }
}
