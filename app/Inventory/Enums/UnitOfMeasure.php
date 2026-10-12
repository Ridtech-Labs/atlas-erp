<?php

declare(strict_types=1);

namespace App\Inventory\Enums;

enum UnitOfMeasure: string
{
    case Pieces = 'pcs';
    case Litre = 'litre';
    case Kilogram = 'kilogram';
    case Metre = 'metre';
    case Set = 'set';
    case Box = 'box';
    case Roll = 'roll';
    case Drum = 'drum';
    case Gallon = 'gallon';

    public function label(): string
    {
        return match ($this) {
            self::Pieces => 'Pieces (pcs)',
            self::Litre => 'Litres (L)',
            self::Kilogram => 'Kilograms (kg)',
            self::Metre => 'Metres (m)',
            self::Set => 'Sets',
            self::Box => 'Boxes',
            self::Roll => 'Rolls',
            self::Drum => 'Drums',
            self::Gallon => 'Gallons',
        };
    }
}
