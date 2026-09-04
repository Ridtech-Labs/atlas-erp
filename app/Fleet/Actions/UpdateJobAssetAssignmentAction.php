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
use App\Operations\Support\OperatorAssignmentService;
use Illuminate\Support\Facades\DB;

class UpdateJobAssetAssignmentAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly JobAssetAssignmentWindowService $windows,
        private readonly JobAssetAvailabilityService $availability,
        private readonly OperatorAssignmentService $operators,
    ) {}

    /** @param array<string, mixed> $data */
    public function execute(JobAssetAssignment $assignment, array $data, User $actor): JobAssetAssignment
    {
        if (! $actor->hasPermissionTo(PermissionName::FleetAssignmentsManage->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $assignment->company_id, $assignment->tenant_id)) {
            throw new BusinessException('You are not allowed to update this Fleet assignment.', 403);
        }

        return DB::transaction(function () use ($assignment, $data, $actor): JobAssetAssignment {
            $assignment = JobAssetAssignment::query()->with('job')->whereKey($assignment->getKey())->lockForUpdate()->firstOrFail();
            if ((string) $assignment->getRawOriginal('status') !== JobAssetAssignmentStatus::Assigned->value) {
                throw new BusinessException('Only an active Fleet assignment can be edited.', 422);
            }

            $assetId = (int) ($data['fleet_asset_id'] ?? $assignment->fleet_asset_id);
            $asset = FleetAsset::query()->with('type')->whereKey($assetId)->lockForUpdate()->first();
            if (! $asset instanceof FleetAsset
                || $asset->tenant_id !== $assignment->tenant_id
                || $asset->company_id !== $assignment->company_id
                || (string) $asset->getRawOriginal('operational_status') !== FleetAssetOperationalStatus::Available->value
                || $asset->type === null
                || ! $asset->type->is_active) {
                throw new BusinessException('The selected Fleet asset is not available for this Job.', 422);
            }

            $job = $assignment->job;
            if ($job === null) {
                throw new BusinessException('The Fleet assignment no longer has a Job context.', 422);
            }
            $window = $this->windows->resolve($job, [...$data, 'planned_start_at' => $data['planned_start_at'] ?? $assignment->planned_start_at, 'planned_end_at' => $data['planned_end_at'] ?? $assignment->planned_end_at]);
            if ($this->availability->hasActiveConflict($asset, $window['start'], $window['end'], $assignment->getKey())) {
                throw new BusinessException('This Fleet asset is already assigned during the selected planning window.', 422);
            }

            $operator = $this->operators->resolveAssignment($data['operator_user_id'] ?? $assignment->operator_user_id, $data['operator_name'] ?? $assignment->operator_name, $assignment->tenant_id, $assignment->company_id);
            $assignment->forceFill([
                'fleet_asset_id' => $asset->getKey(),
                'operator_user_id' => $operator['operator']?->getKey(),
                'operator_name' => $operator['external_name'],
                'planned_start_at' => $window['start'], 'planned_end_at' => $window['end'],
                'notes' => array_key_exists('notes', $data) ? (filled($data['notes']) ? trim((string) $data['notes']) : null) : $assignment->notes,
            ])->save();
            $this->logger->log('job_asset_assignment.updated', 'Fleet assignment updated', $actor, $assignment, ['tenant_id' => $assignment->tenant_id, 'company_id' => $assignment->company_id, 'job_id' => $assignment->job_id]);

            return $assignment->refresh();
        });
    }
}
