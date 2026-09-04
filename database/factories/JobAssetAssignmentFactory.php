<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Fleet\Enums\JobAssetAssignmentStatus;
use App\Fleet\Models\FleetAsset;
use App\Fleet\Models\JobAssetAssignment;
use App\Operations\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<JobAssetAssignment> */
class JobAssetAssignmentFactory extends Factory
{
    protected $model = JobAssetAssignment::class;

    public function definition(): array
    {
        $start = now()->startOfDay();

        return [
            'uuid' => (string) Str::uuid(),
            'tenant_id' => 1,
            'company_id' => 1,
            'job_id' => Job::factory(),
            'fleet_asset_id' => FleetAsset::factory(),
            'planned_start_at' => $start,
            'planned_end_at' => $start->addDay(),
            'assigned_at' => now(),
            'status' => JobAssetAssignmentStatus::Assigned,
        ];
    }
}
