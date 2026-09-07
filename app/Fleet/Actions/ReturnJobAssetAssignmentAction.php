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

class ReturnJobAssetAssignmentAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(JobAssetAssignment $assignment, User $actor, ?string $notes = null): JobAssetAssignment
    {
        if (! $actor->hasPermissionTo(PermissionName::FleetDispatchReturn->value) || ! $this->access->canAccessActiveOperationalCompany($actor, $assignment->company_id, $assignment->tenant_id)) {
            throw new BusinessException('You are not allowed to return this Fleet asset.', 403);
        }

        return DB::transaction(function () use ($assignment, $actor, $notes): JobAssetAssignment {
            $assignment = JobAssetAssignment::query()->whereKey($assignment->getKey())->lockForUpdate()->firstOrFail();
            if ((string) $assignment->getRawOriginal('status') !== JobAssetAssignmentStatus::Dispatched->value) {
                throw new BusinessException('Only a dispatched Fleet asset can be returned.', 422);
            }
            $assignment->forceFill(['status' => JobAssetAssignmentStatus::Returned, 'returned_at' => now(), 'returned_by' => $actor->getKey(), 'return_notes' => filled($notes) ? trim($notes) : null])->save();
            $this->logger->log('job_asset_assignment.returned', 'Fleet asset returned', $actor, $assignment, ['tenant_id' => $assignment->tenant_id, 'company_id' => $assignment->company_id, 'job_id' => $assignment->job_id, 'fleet_asset_id' => $assignment->fleet_asset_id]);

            return $assignment->refresh();
        });
    }
}
