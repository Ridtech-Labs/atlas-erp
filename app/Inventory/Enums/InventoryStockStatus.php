<?php

declare(strict_types=1);

namespace App\Inventory\Enums;

enum InventoryStockStatus: string
{
    case OutOfStock = 'out_of_stock';
    case LowStock = 'low_stock';
    case InStock = 'in_stock';

    public function label(): string
    {
        return match ($this) {
            self::OutOfStock => 'Out of stock',
            self::LowStock => 'Low stock',
            self::InStock => 'In stock',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::OutOfStock => 'danger',
            self::LowStock => 'warning',
            self::InStock => 'success',
        };
    }
}
