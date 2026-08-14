<?php

declare(strict_types=1);

namespace App\Operations\Actions\JobCards;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Models\JobCard;
use Illuminate\Support\Facades\DB;

class ApproveJobCardAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(JobCard $jobCard, User $actor, ?string $remarks = null): JobCard
    {
        if (! $actor->hasPermissionTo(PermissionName::JobsApprove->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $jobCard->company_id, $jobCard->tenant_id)) {
            throw new BusinessException('You are not allowed to approve this job card.', 403);
        }

        if ((string) $jobCard->getRawOriginal('approval_status') !== JobCardApprovalStatus::Submitted->value) {
            throw new BusinessException('Only submitted job cards can be approved.', 422);
        }

        $logger = $this->logger;

        return DB::transaction(function () use ($jobCard, $actor, $remarks, $logger): JobCard {
            $jobCard->forceFill([
                'approval_status' => JobCardApprovalStatus::Approved->value,
                'approved_by' => $actor->getKey(),
                'approved_at' => now(),
                'returned_by' => null,
                'returned_at' => null,
                'return_reason' => null,
                'officer_remarks' => $remarks ?? $jobCard->officer_remarks,
                'updated_by' => $actor->getKey(),
            ])->save();

            $logger->log('job_card.approved', 'Job card approved', $actor, $jobCard, [
                'tenant_id' => $jobCard->tenant_id,
                'company_id' => $jobCard->company_id,
                'job_id' => $jobCard->job_id,
            ]);

            return $jobCard->refresh();
        });
    }
}
