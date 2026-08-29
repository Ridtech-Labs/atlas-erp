<?php

declare(strict_types=1);

namespace App\Finance\Models;

use App\Finance\Enums\BillingUnit;
use Database\Factories\RateAgreementLineFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RateAgreementLine extends Model
{
    /** @use HasFactory<RateAgreementLineFactory> */
    use HasFactory;

    protected $fillable = [
        'rate_agreement_id',
        'equipment_reference',
        'machine_number',
        'billing_unit',
        'currency',
        'rate',
        'effective_from',
        'effective_to',
    ];

    protected function casts(): array
    {
        return [
            'billing_unit' => BillingUnit::class,
            'rate' => 'decimal:2',
            'effective_from' => 'date',
            'effective_to' => 'date',
        ];
    }

    protected static function newFactory(): RateAgreementLineFactory
    {
        return RateAgreementLineFactory::new();
    }

    /**
     * @return BelongsTo<RateAgreement, $this>
     */
    public function rateAgreement(): BelongsTo
    {
        return $this->belongsTo(RateAgreement::class);
    }

    public function effectiveFromOrAgreement(): ?string
    {
        $effectiveFrom = $this->getRawOriginal('effective_from');

        if (is_string($effectiveFrom) && $effectiveFrom !== '') {
            return substr($effectiveFrom, 0, 10);
        }

        $agreementEffectiveFrom = $this->rateAgreement?->getRawOriginal('effective_from');

        return is_string($agreementEffectiveFrom) && $agreementEffectiveFrom !== ''
            ? substr($agreementEffectiveFrom, 0, 10)
            : null;
    }

    public function effectiveToOrAgreement(): ?string
    {
        $effectiveTo = $this->getRawOriginal('effective_to');

        if (is_string($effectiveTo) && $effectiveTo !== '') {
            return substr($effectiveTo, 0, 10);
        }

        $agreementEffectiveTo = $this->rateAgreement?->getRawOriginal('effective_to');

        return is_string($agreementEffectiveTo) && $agreementEffectiveTo !== ''
            ? substr($agreementEffectiveTo, 0, 10)
            : null;
    }

    public function inheritsAgreementPeriod(): bool
    {
        return $this->effective_from === null && $this->effective_to === null;
    }
}
