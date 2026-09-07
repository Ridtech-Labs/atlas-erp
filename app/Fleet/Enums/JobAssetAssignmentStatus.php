<?php

declare(strict_types=1);

namespace App\Fleet\Enums;

enum JobAssetAssignmentStatus: string
{
    case Assigned = 'assigned';
    case Dispatched = 'dispatched';
    case Returned = 'returned';
    case Released = 'released';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Assigned => 'Assigned',
            self::Dispatched => 'Dispatched',
            self::Returned => 'Returned',
            self::Released => 'Released',
            self::Cancelled => 'Cancelled',
        };
    }
}
