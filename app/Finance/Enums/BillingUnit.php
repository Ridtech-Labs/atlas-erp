<?php

declare(strict_types=1);

namespace App\Finance\Enums;

enum BillingUnit: string
{
    case Hourly = 'hourly';
    case Trip = 'trip';

    public function label(): string
    {
        return match ($this) {
            self::Hourly => 'Hourly',
            self::Trip => 'Trip',
        };
    }
}
