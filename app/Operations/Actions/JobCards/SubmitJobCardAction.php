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

class SubmitJobCardAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(JobCard $jobCard, User $actor): JobCard
    {
        if (! $actor->hasPermissionTo(PermissionName::JobsSubmit->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $jobCard->company_id, $jobCard->tenant_id)) {
            throw new BusinessException('You are not allowed to submit this job card.', 403);
        }

        if (! in_array((string) $jobCard->getRawOriginal('approval_status'), [JobCardApprovalStatus::Draft->value, JobCardApprovalStatus::Returned->value], true)) {
            throw new BusinessException('Only draft or returned job cards can be submitted for approval.', 422);
        }

        $logger = $this->logger;

        return DB::transaction(function () use ($jobCard, $actor, $logger): JobCard {
            $jobCard->forceFill([
                'approval_status' => JobCardApprovalStatus::Submitted->value,
                'returned_by' => null,
                'returned_at' => null,
                'return_reason' => null,
                'updated_by' => $actor->getKey(),
            ])->save();

            $logger->log('job_card.submitted', 'Job card submitted for approval', $actor, $jobCard, [
                'tenant_id' => $jobCard->tenant_id,
                'company_id' => $jobCard->company_id,
                'job_id' => $jobCard->job_id,
            ]);

            return $jobCard->refresh();
        });
    }
}
