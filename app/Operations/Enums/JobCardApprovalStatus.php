<?php

declare(strict_types=1);

namespace App\Operations\Enums;

enum JobCardApprovalStatus: string
{
    case Draft = 'draft';
    case Recorded = 'recorded';
    case PendingVerification = 'pending_verification';
    case Verified = 'verified';
    case BillingReady = 'billing_ready';
    case Returned = 'returned';
    case Submitted = 'submitted';
    case Approved = 'approved';

    public function label(): string
    {
        return match ($this) {
            self::PendingVerification => 'Awaiting Accounts Review',
            self::Verified => 'Accounts Reviewed',
            self::BillingReady => 'Billing Ready',
            self::Returned => 'Returned to Operations',
            self::Submitted => 'Submitted',
            self::Approved => 'Approved',
            default => ucfirst(str_replace('_', ' ', $this->value)),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Recorded => 'gray',
            self::PendingVerification => 'warning',
            self::Verified => 'success',
            self::BillingReady => 'primary',
            self::Submitted => 'warning',
            self::Approved => 'success',
            self::Returned => 'danger',
        };
    }
}
