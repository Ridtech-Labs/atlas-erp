<?php

declare(strict_types=1);

namespace App\Operations\Enums;

enum JobShift: string
{
    case Day = 'day';
    case Night = 'night';
    case Swing = 'swing';
    case Custom = 'custom';

    public function label(): string
    {
        return match ($this) {
            self::Day => 'Day Shift',
            self::Night => 'Night Shift',
            self::Swing => 'Swing Shift',
            self::Custom => 'Custom Shift',
        };
    }
}
