<?php

declare(strict_types=1);

namespace App\CRM\Enums;

enum ClientType: string
{
    case Corporate = 'corporate';
    case Individual = 'individual';
    case Government = 'government';
    case NonProfit = 'non_profit';

    public function label(): string
    {
        return match ($this) {
            self::Corporate => 'Corporate',
            self::Individual => 'Individual',
            self::Government => 'Government',
            self::NonProfit => 'Non Profit',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Corporate => 'primary',
            self::Individual => 'info',
            self::Government => 'warning',
            self::NonProfit => 'success',
        };
    }
}
