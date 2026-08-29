<?php

declare(strict_types=1);

namespace App\Fleet\Enums;

enum FleetAssetOperationalStatus: string
{
    case Available = 'available';
    case OutOfService = 'out_of_service';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Available => 'Available',
            self::OutOfService => 'Out of service',
            self::Inactive => 'Inactive',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Available => 'success',
            self::OutOfService => 'danger',
            self::Inactive => 'gray',
        };
    }
}
