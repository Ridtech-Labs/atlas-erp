<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Core\Tenancy\Models\Company;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use App\Models\User;
use App\Operations\Enums\JobPriority;
use App\Operations\Enums\JobShift;
use App\Operations\Enums\JobStatus;
use App\Operations\Enums\JobType;
use App\Operations\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Job>
 */
class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => null,
            'company_id' => null,
            'job_number' => 'JOB-'.fake()->unique()->numerify('#####'),
            'job_reference' => fake()->optional()->bothify('KAD-####'),
            'client_id' => null,
            'client_site_id' => null,
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'job_type' => JobType::HeavyMachinery,
            'status' => JobStatus::Draft,
            'priority' => fake()->randomElement(JobPriority::cases()),
            'currency' => 'GHS',
            'requested_start_date' => now()->toDateString(),
            'planned_start_date' => now()->addDay()->toDateString(),
            'planned_start_time' => '08:00:00',
            'planned_end_date' => now()->addDays(3)->toDateString(),
            'planned_end_time' => '17:00:00',
            'vessel' => fake()->optional()->company(),
            'work_area' => fake()->optional()->randomElement(['Berth 3', 'Yard A', 'Stack Zone']),
            'equipment_requirement' => fake()->optional()->randomElement(['Forklift', 'Reach Stacker', 'Crane']),
            'assigned_operator_id' => null,
            'assigned_operator_name' => null,
            'shift' => fake()->randomElement(JobShift::cases()),
            'actual_start_date' => null,
            'actual_end_date' => null,
            'client_reference' => fake()->optional()->bothify('REF-####'),
            'internal_reference' => fake()->optional()->bothify('INT-####'),
            'estimated_value' => fake()->optional()->randomFloat(2, 500, 50000),
            'customer_reference' => null,
            'purchase_order_number' => null,
            'scheduled_start_date' => null,
            'scheduled_end_date' => null,
            'estimated_amount' => null,
            'assigned_to' => null,
            'notes' => null,
            'approved_at' => null,
            'approved_by' => null,
            'cancelled_at' => null,
            'cancelled_by' => null,
            'cancellation_reason' => null,
            'completed_at' => null,
            'completed_by' => null,
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    public function configure(): static
    {
        return $this
            ->afterMaking(function (Job $job): void {
                $this->ensureOperationalContext($job);
            })
            ->afterCreating(function (Job $job): void {
                $this->ensureOperationalContext($job, persist: true);
            });
    }

    private function ensureOperationalContext(Job $job, bool $persist = false): void
    {
        $client = $job->client_id
            ? Client::query()->find($job->client_id)
            : null;

        if (! $client instanceof Client) {
            $client = Client::factory()->create(array_filter([
                'tenant_id' => $job->tenant_id,
                'company_id' => $job->company_id,
            ]));
        }

        $job->tenant_id = $client->tenant_id;
        $job->company_id = $client->company_id;
        $job->client_id = $client->getKey();
        $job->currency = $job->currency ?: ($client->company->currency ?? $client->tenant->currency ?? 'GHS');

        $company = Company::query()->findOrFail($client->company_id);

        $site = $job->client_site_id
            ? ClientSite::query()->find($job->client_site_id)
            : null;

        if (! $site instanceof ClientSite || $site->tenant_id !== $client->tenant_id || $site->client_id !== $client->getKey()) {
            $site = ClientSite::factory()->create([
                'tenant_id' => $client->tenant_id,
                'client_id' => $client->getKey(),
            ]);

            $job->client_site_id = $site->getKey();
        }

        $author = $job->created_by
            ? User::query()->find($job->created_by)
            : null;

        if (! $author instanceof User || $author->tenant_id !== $client->tenant_id) {
            $author = User::factory()->create(['tenant_id' => $client->tenant_id]);
        }

        $author->companies()->syncWithoutDetaching([$company->getKey()]);
        $job->created_by = $author->getKey();
        $job->updated_by = $job->updated_by && User::query()->whereKey($job->updated_by)->where('tenant_id', $client->tenant_id)->exists()
            ? $job->updated_by
            : $author->getKey();
        $job->assigned_operator_id = $job->assigned_operator_id && User::query()->whereKey($job->assigned_operator_id)->where('tenant_id', $client->tenant_id)->exists()
            ? $job->assigned_operator_id
            : null;
        $job->assigned_to = $job->assigned_operator_id;
        $job->customer_reference = $job->client_reference;
        $job->purchase_order_number = $job->internal_reference;
        $job->scheduled_start_date = $job->planned_start_date;
        $job->scheduled_end_date = $job->planned_end_date;
        $job->estimated_amount = $job->estimated_value;
        $job->notes = $job->description;

        if ($persist && $job->exists) {
            $job->saveQuietly();
        }
    }
}
