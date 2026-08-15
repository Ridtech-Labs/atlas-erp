<?php

declare(strict_types=1);

namespace App\Operations\Enums;

enum WaybillStatus: string
{
    case Recorded = 'recorded';
    case PendingVerification = 'pending_verification';
    case Verified = 'verified';
    case BillingReady = 'billing_ready';
    case Returned = 'returned';

    public function label(): string
    {
        return match ($this) {
            self::PendingVerification => 'Pending Verification',
            self::BillingReady => 'Billing Ready',
            default => ucfirst(str_replace('_', ' ', $this->value)),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Recorded => 'gray',
            self::PendingVerification => 'warning',
            self::Verified => 'success',
            self::BillingReady => 'primary',
            self::Returned => 'danger',
        };
    }
}
