<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Finance\Enums\BillingUnit;
use App\Finance\Models\RateAgreement;
use App\Finance\Models\RateAgreementLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RateAgreementLine>
 */
class RateAgreementLineFactory extends Factory
{
    protected $model = RateAgreementLine::class;

    public function definition(): array
    {
        return [
            'rate_agreement_id' => RateAgreement::factory(),
            'equipment_reference' => 'Reach Stacker',
            'machine_number' => null,
            'billing_unit' => BillingUnit::Hourly,
            'currency' => 'GHS',
            'rate' => 850.00,
            'effective_from' => now()->startOfMonth()->toDateString(),
            'effective_to' => null,
        ];
    }
}
