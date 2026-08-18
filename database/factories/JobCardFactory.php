<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<JobCard>
 */
class JobCardFactory extends Factory
{
    protected $model = JobCard::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'card_number' => 'JC-'.fake()->unique()->numerify('#####'),
            'tenant_id' => null,
            'company_id' => null,
            'job_id' => Job::factory(),
            'client_id' => null,
            'client_site_id' => null,
            'card_date' => now()->toDateString(),
            'shift' => 'night',
            'equipment_reference' => 'Forklift FL-12',
            'operator_id' => null,
            'operated_by' => null,
            'supervising_officer_name' => fake()->name(),
            'header_hours' => null,
            'officer_remarks' => fake()->optional()->sentence(),
            'approval_status' => JobCardApprovalStatus::Draft,
            'approved_by' => null,
            'approved_at' => null,
            'submitted_by' => null,
            'submitted_at' => null,
            'returned_by' => null,
            'returned_at' => null,
            'return_reason' => null,
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    public function configure(): static
    {
        return $this
            ->afterMaking(function (JobCard $card): void {
                $this->ensureContext($card);
            })
            ->afterCreating(function (JobCard $card): void {
                $this->ensureContext($card, persist: true);
            });
    }

    private function ensureContext(JobCard $card, bool $persist = false): void
    {
        $job = $card->job_id ? Job::query()->find($card->job_id) : null;

        if (! $job instanceof Job) {
            $job = Job::factory()->create(array_filter([
                'tenant_id' => $card->tenant_id,
                'company_id' => $card->company_id,
                'client_id' => $card->client_id,
                'client_site_id' => $card->client_site_id,
            ], static fn (mixed $value): bool => $value !== null));
        }

        $card->tenant_id = $job->tenant_id;
        $card->company_id = $job->company_id ?? 0;
        $card->client_id = $job->client_id;
        $card->client_site_id = $job->client_site_id;

        if ($persist && $card->exists) {
            $card->saveQuietly();
        }
    }
}
