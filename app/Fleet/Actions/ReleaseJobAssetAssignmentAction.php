<?php

declare(strict_types=1);

namespace App\Fleet\Actions;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Fleet\Enums\JobAssetAssignmentStatus;
use App\Fleet\Models\JobAssetAssignment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReleaseJobAssetAssignmentAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(JobAssetAssignment $assignment, User $actor, ?string $reason = null): JobAssetAssignment
    {
        if (! $actor->hasPermissionTo(PermissionName::FleetAssignmentsManage->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $assignment->company_id, $assignment->tenant_id)) {
            throw new BusinessException('You are not allowed to release this Fleet assignment.', 403);
        }

        return DB::transaction(function () use ($assignment, $actor, $reason): JobAssetAssignment {
            $assignment = JobAssetAssignment::query()->whereKey($assignment->getKey())->lockForUpdate()->firstOrFail();
            if ((string) $assignment->getRawOriginal('status') !== JobAssetAssignmentStatus::Assigned->value) {
                throw new BusinessException('Only an active Fleet assignment can be released.', 422);
            }
            $assignment->forceFill(['status' => JobAssetAssignmentStatus::Released, 'released_at' => now(), 'released_by' => $actor->getKey(), 'release_reason' => filled($reason) ? trim($reason) : null])->save();
            $this->logger->log('job_asset_assignment.released', 'Fleet assignment released', $actor, $assignment, ['tenant_id' => $assignment->tenant_id, 'company_id' => $assignment->company_id, 'job_id' => $assignment->job_id]);

            return $assignment->refresh();
        });
    }
}
