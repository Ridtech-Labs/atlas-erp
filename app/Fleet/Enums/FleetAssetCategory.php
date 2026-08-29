<?php

declare(strict_types=1);

namespace App\Fleet\Enums;

enum FleetAssetCategory: string
{
    case HeavyMachinery = 'heavy_machinery';
    case Transport = 'transport';
    case Trailer = 'trailer';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::HeavyMachinery => 'Heavy machinery',
            self::Transport => 'Transport',
            self::Trailer => 'Trailer',
            self::Other => 'Other',
        };
    }
}
