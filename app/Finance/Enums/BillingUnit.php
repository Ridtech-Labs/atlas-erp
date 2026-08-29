<?php

declare(strict_types=1);

namespace App\Finance\Enums;

enum BillingUnit: string
{
    case Hourly = 'hourly';

    public function label(): string
    {
        return match ($this) {
            self::Hourly => 'Hourly',
        };
    }
}
