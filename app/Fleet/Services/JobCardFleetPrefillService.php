<?php

declare(strict_types=1);

namespace App\Fleet\Services;

use App\Core\Shared\Exceptions\BusinessException;
use App\Fleet\Enums\JobAssetAssignmentStatus;
use App\Fleet\Models\JobAssetAssignment;
use App\Operations\Models\Job;
use Illuminate\Support\Collection;

class JobCardFleetPrefillService
{
    /**
     * @return array{equipment_reference:?string, machine_number:?string}
     */
    public function forJob(Job $job): array
    {
        $assignments = $this->activeAssignments($job);

        if ($assignments->count() !== 1) {
            return ['equipment_reference' => null, 'machine_number' => null];
        }

        return $this->identity($assignments->first());
    }

    /** @return Collection<int, JobAssetAssignment> */
    public function activeAssignments(Job $job): Collection
    {
        return $job->assetAssignments()
            ->with('asset.type')
            ->where('tenant_id', $job->tenant_id)
            ->where('company_id', $job->company_id)
            ->where('status', JobAssetAssignmentStatus::Assigned->value)
            ->get();
    }

    /** @return array{equipment_reference:?string, machine_number:?string} */
    public function selectedForJob(Job $job, ?int $fleetAssetId): array
    {
        $assignments = $this->activeAssignments($job);

        if ($assignments->isEmpty()) {
            return ['equipment_reference' => null, 'machine_number' => null];
        }

        if ($assignments->count() === 1) {
            $assignment = $assignments->first();

            if ($fleetAssetId !== null && $fleetAssetId !== $assignment->fleet_asset_id) {
                throw new BusinessException('Select the Fleet asset assigned to this Job Card.', 422);
            }

            return $this->identity($assignment);
        }

        $assignment = $assignments->firstWhere('fleet_asset_id', $fleetAssetId);
        if (! $assignment instanceof JobAssetAssignment) {
            throw new BusinessException('Select one of the active Fleet assets assigned to this Job.', 422);
        }

        return $this->identity($assignment);
    }

    /** @return array{equipment_reference:?string, machine_number:?string} */
    private function identity(?JobAssetAssignment $assignment): array
    {
        $asset = $assignment?->asset;

        return [
            'equipment_reference' => $asset?->type?->name,
            'machine_number' => $asset?->asset_number,
        ];
    }
}
