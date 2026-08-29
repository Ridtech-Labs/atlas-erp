<?php

declare(strict_types=1);

namespace App\Finance\Enums;

enum BillingRecordStatus: string
{
    case Draft = 'draft';
    case Issued = 'issued';
    case Paid = 'paid';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Issued => 'Issued',
            self::Paid => 'Paid',
            self::Closed => 'Closed',
        };
    }
}
