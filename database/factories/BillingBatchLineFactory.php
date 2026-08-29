<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Finance\Models\BillingBatch;
use App\Finance\Models\BillingBatchLine;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Models\JobCardWorkEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BillingBatchLine>
 */
class BillingBatchLineFactory extends Factory
{
    protected $model = BillingBatchLine::class;

    public function definition(): array
    {
        return [
            'billing_batch_id' => BillingBatch::factory(),
            'work_entry_id' => JobCardWorkEntry::factory(),
            'job_card_id' => JobCard::factory(),
            'job_id' => Job::factory(),
            'job_reference' => 'JOB-'.fake()->numerify('#####'),
            'activity_date' => now()->toDateString(),
            'vessel' => fake()->company(),
            'work_area' => fake()->randomElement(['Berth 2', 'Main Yard', 'Stack Zone']),
            'from_time' => '08:00:00',
            'to_time' => '16:00:00',
            'equipment_reference' => 'Reach Stacker',
            'machine_number' => 'RS-01',
            'hours' => 8.00,
            'resolved_rate' => 850.00,
            'currency' => 'GHS',
            'line_amount' => 6800.00,
            'rate_agreement_id' => null,
            'rate_agreement_line_id' => null,
        ];
    }
}
