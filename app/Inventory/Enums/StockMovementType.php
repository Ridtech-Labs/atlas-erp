<?php

declare(strict_types=1);

namespace App\Inventory\Enums;

enum StockMovementType: string
{
    case OpeningBalance = 'opening_balance';
    case Receipt = 'receipt';
    case Issue = 'issue';
    case AdjustmentIncrease = 'adjustment_increase';
    case AdjustmentDecrease = 'adjustment_decrease';

    public function label(): string
    {
        return match ($this) {
            self::OpeningBalance => 'Opening balance',
            self::Receipt => 'Stock receipt',
            self::Issue => 'Stock issue',
            self::AdjustmentIncrease => 'Adjustment increase',
            self::AdjustmentDecrease => 'Adjustment decrease',
        };
    }

    public function increasesStock(): bool
    {
        return match ($this) {
            self::OpeningBalance, self::Receipt, self::AdjustmentIncrease => true,
            self::Issue, self::AdjustmentDecrease => false,
        };
    }

    public function requiresAdjustmentPermission(): bool
    {
        return match ($this) {
            self::AdjustmentIncrease, self::AdjustmentDecrease => true,
            default => false,
        };
    }
}
