<?php

declare(strict_types=1);

namespace App\Fleet\Services;

use App\Fleet\Enums\FleetAssetOperationalStatus;
use App\Fleet\Enums\JobAssetAssignmentStatus;
use App\Fleet\Models\FleetAsset;
use App\Fleet\Models\JobAssetAssignment;
use App\Operations\Models\Job;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;

class JobAssetAvailabilityService
{
    public function __construct(
        private readonly JobAssetAssignmentWindowService $windows,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @return Collection<int, FleetAsset>
     */
    public function availableFor(Job $job, array $data): Collection
    {
        if ($job->planned_start_date === null) {
            return new Collection;
        }

        $window = $this->windowForAvailability($job, $data);

        return FleetAsset::query()
            ->with('type')
            ->where('tenant_id', $job->tenant_id)
            ->where('company_id', $job->company_id)
            ->where('operational_status', FleetAssetOperationalStatus::Available->value)
            ->whereHas('type', fn ($query) => $query->where('is_active', true))
            ->whereDoesntHave('jobAssignments', fn ($query) => $query
                ->whereIn('status', [JobAssetAssignmentStatus::Assigned->value, JobAssetAssignmentStatus::Dispatched->value])
                ->where('planned_start_at', '<', $window['end'])
                ->where('planned_end_at', '>', $window['start']))
            ->orderBy('asset_number')
            ->get();
    }

    public function hasActiveConflict(FleetAsset $asset, CarbonImmutable $start, CarbonImmutable $end, ?int $exceptAssignmentId = null): bool
    {
        return JobAssetAssignment::query()
            ->when($exceptAssignmentId !== null, fn ($query) => $query->whereKeyNot($exceptAssignmentId))
            ->where('fleet_asset_id', $asset->getKey())
            ->whereIn('status', [JobAssetAssignmentStatus::Assigned->value, JobAssetAssignmentStatus::Dispatched->value])
            ->where('planned_start_at', '<', $end)
            ->where('planned_end_at', '>', $start)
            ->exists();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{start: CarbonImmutable, end: CarbonImmutable}
     */
    private function windowForAvailability(Job $job, array $data): array
    {
        $start = $data['planned_start_at'] ?? null;
        $end = $data['planned_end_at'] ?? null;

        // While a user is entering one side of a new window, keep the dropdown
        // useful by retaining the Job's default reservation window.
        if (blank($start) || blank($end)) {
            return $this->windows->defaultForJob($job);
        }

        return $this->windows->resolve($job, $data);
    }
}
