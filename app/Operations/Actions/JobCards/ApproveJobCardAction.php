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
use App\Operations\Support\JobCardEvidenceValidator;
use Illuminate\Support\Facades\DB;

class ApproveJobCardAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly JobCardEvidenceValidator $validator,
    ) {}

    public function execute(JobCard $jobCard, User $actor, ?string $remarks = null): JobCard
    {
        if (! $actor->hasPermissionTo(PermissionName::JobCardsVerify->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $jobCard->company_id, $jobCard->tenant_id)) {
            throw new BusinessException('You are not allowed to review this client Job Card for billing.', 403);
        }

        if ($jobCard->wasSubmittedBy($actor)) {
            throw new BusinessException('You cannot review a client Job Card for billing when you submitted it to Accounts.', 403);
        }

        if ((string) ($jobCard->getRawOriginal('approval_status') ?? '') !== JobCardApprovalStatus::PendingVerification->value) {
            throw new BusinessException('Only client Job Cards awaiting Accounts review can be reviewed for billing.', 422);
        }

        $this->validator->validateForAccountsReview($jobCard);

        $logger = $this->logger;

        return DB::transaction(function () use ($jobCard, $actor, $remarks, $logger): JobCard {
            $jobCard->forceFill([
                'approval_status' => JobCardApprovalStatus::Verified->value,
                'verified_by' => $actor->getKey(),
                'verified_at' => now(),
                'returned_by' => null,
                'returned_at' => null,
                'return_reason' => null,
                'verification_notes' => $remarks ?? $jobCard->verification_notes,
                'updated_by' => $actor->getKey(),
            ])->save();

            $logger->log('job_card.verified', 'Client Job Card reviewed for billing', $actor, $jobCard, [
                'tenant_id' => $jobCard->tenant_id,
                'company_id' => $jobCard->company_id,
                'job_id' => $jobCard->job_id,
            ]);

            return $jobCard->refresh();
        });
    }
}
