<?php

declare(strict_types=1);

namespace Database\Factories;

use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use App\Models\User;
use App\Operations\Enums\JobPriority;
use App\Operations\Enums\JobStatus;
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
        $client = Client::factory()->create();
        $site = ClientSite::factory()->create([
            'tenant_id' => $client->tenant_id,
            'client_id' => $client->getKey(),
        ]);
        $user = User::factory()->create(['tenant_id' => $client->tenant_id]);

        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => $client->tenant_id,
            'job_number' => 'JOB-'.fake()->unique()->numerify('#####'),
            'client_id' => $client->getKey(),
            'client_site_id' => $site->getKey(),
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'status' => JobStatus::Draft,
            'priority' => fake()->randomElement(JobPriority::cases()),
            'requested_start_date' => now()->toDateString(),
            'planned_start_date' => now()->addDay()->toDateString(),
            'planned_end_date' => now()->addDays(3)->toDateString(),
            'actual_start_date' => null,
            'actual_end_date' => null,
            'client_reference' => fake()->optional()->bothify('REF-####'),
            'internal_reference' => fake()->optional()->bothify('INT-####'),
            'estimated_value' => fake()->optional()->randomFloat(2, 500, 50000),
            'approved_at' => null,
            'approved_by' => null,
            'cancelled_at' => null,
            'cancelled_by' => null,
            'cancellation_reason' => null,
            'completed_at' => null,
            'completed_by' => null,
            'created_by' => $user->getKey(),
            'updated_by' => $user->getKey(),
        ];
    }
}
