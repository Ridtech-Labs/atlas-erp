<?php

declare(strict_types=1);

namespace App\Operations\Enums;

enum JobType: string
{
    case HeavyMachinery = 'heavy_machinery';
    case Trucking = 'trucking';

    public function label(): string
    {
        return match ($this) {
            self::HeavyMachinery => 'Heavy Machinery',
            self::Trucking => 'Trucking / Haulage',
        };
    }
}
