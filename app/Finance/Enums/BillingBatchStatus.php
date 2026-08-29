<?php

declare(strict_types=1);

namespace App\Finance\Enums;

enum BillingBatchStatus: string
{
    case Draft = 'draft';
    case Prepared = 'prepared';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Prepared => 'Prepared',
        };
    }
}
