<?php

declare(strict_types=1);

namespace App\Operations\Enums;

enum JobCardApprovalStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Approved = 'approved';
    case Returned = 'returned';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Submitted => 'Submitted',
            self::Approved => 'Approved',
            self::Returned => 'Returned',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Submitted => 'warning',
            self::Approved => 'success',
            self::Returned => 'danger',
        };
    }
}
