<?php

declare(strict_types=1);

namespace App\Fleet\Actions;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Fleet\Enums\FleetAssetOperationalStatus;
use App\Fleet\Enums\JobAssetAssignmentStatus;
use App\Fleet\Models\FleetAsset;
use App\Fleet\Models\JobAssetAssignment;
use App\Fleet\Services\JobAssetAssignmentWindowService;
use App\Fleet\Services\JobAssetAvailabilityService;
use App\Models\User;
use App\Operations\Models\Job;
use App\Operations\Support\OperatorAssignmentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateJobAssetAssignmentAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly JobAssetAssignmentWindowService $windows,
        private readonly JobAssetAvailabilityService $availability,
        private readonly OperatorAssignmentService $operators,
    ) {}

    /** @param array<string, mixed> $data */
    public function execute(Job $job, array $data, User $actor): JobAssetAssignment
    {
        if (! $actor->hasPermissionTo(PermissionName::FleetAssignmentsManage->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $job->company_id, $job->tenant_id)) {
            throw new BusinessException('You are not allowed to assign Fleet assets for this Job.', 403);
        }

        $assetId = (int) ($data['fleet_asset_id'] ?? 0);
        if ($assetId < 1) {
            throw new BusinessException('Select an available Fleet asset.', 422);
        }

        return DB::transaction(function () use ($job, $data, $actor, $assetId): JobAssetAssignment {
            $asset = FleetAsset::query()->with('type')->whereKey($assetId)->lockForUpdate()->first();
            if (! $asset instanceof FleetAsset
                || $asset->tenant_id !== $job->tenant_id
                || $asset->company_id !== $job->company_id
                || (string) $asset->getRawOriginal('operational_status') !== FleetAssetOperationalStatus::Available->value
                || $asset->type === null
                || ! $asset->type->is_active) {
                throw new BusinessException('The selected Fleet asset is not available for this Job.', 422);
            }

            $window = $this->windows->resolve($job, $data);
            if ($this->availability->hasActiveConflict($asset, $window['start'], $window['end'])) {
                throw new BusinessException('This Fleet asset is already assigned during the selected planning window.', 422);
            }

            $operator = $this->operators->resolveAssignment(
                $data['operator_user_id'] ?? null,
                $data['operator_name'] ?? null,
                $job->tenant_id,
                $job->company_id,
                false,
            );

            $assignment = JobAssetAssignment::query()->create([
                'uuid' => (string) Str::uuid(),
                'tenant_id' => $job->tenant_id,
                'company_id' => $job->company_id,
                'job_id' => $job->getKey(),
                'fleet_asset_id' => $asset->getKey(),
                'operator_user_id' => $operator['operator']?->getKey(),
                'operator_name' => $operator['external_name'],
                'planned_start_at' => $window['start'],
                'planned_end_at' => $window['end'],
                'assigned_at' => now(),
                'assigned_by' => $actor->getKey(),
                'status' => JobAssetAssignmentStatus::Assigned,
                'notes' => filled($data['notes'] ?? null) ? trim((string) $data['notes']) : null,
            ]);

            $this->logger->log('job_asset_assignment.assigned', sprintf('%s assigned to %s', $asset->asset_number, $job->job_number), $actor, $assignment, [
                'tenant_id' => $job->tenant_id, 'company_id' => $job->company_id, 'job_id' => $job->getKey(), 'fleet_asset_id' => $asset->getKey(),
            ]);

            return $assignment->refresh();
        });
    }
}
