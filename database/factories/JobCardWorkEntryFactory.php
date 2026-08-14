<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Operations\Models\JobCard;
use App\Operations\Models\JobCardWorkEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobCardWorkEntry>
 */
class JobCardWorkEntryFactory extends Factory
{
    protected $model = JobCardWorkEntry::class;

    public function definition(): array
    {
        return [
            'job_card_id' => JobCard::factory(),
            'vessel' => fake()->company(),
            'work_area' => fake()->randomElement(['Berth', 'Yard', 'Stack Area']),
            'from_time' => '22:00:00',
            'to_time' => '06:00:00',
            'normal_hours' => 8,
            'overtime_hours' => 0,
            'total_hours' => 8,
            'officer_name' => fake()->name(),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
