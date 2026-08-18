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

class ReturnJobCardAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(JobCard $jobCard, User $actor, string $reason): JobCard
    {
        if (! $actor->hasPermissionTo(PermissionName::JobCardsVerify->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $jobCard->company_id, $jobCard->tenant_id)) {
            throw new BusinessException('You are not allowed to return this client Job Card to Operations.', 403);
        }

        if ($jobCard->wasSubmittedBy($actor)) {
            throw new BusinessException('You cannot return a client Job Card to Operations when you submitted it to Accounts.', 403);
        }

        $reason = trim($reason);

        if ($reason === '') {
            throw new BusinessException('A return reason is required.', 422);
        }

        if ((string) $jobCard->getRawOriginal('approval_status') !== JobCardApprovalStatus::PendingVerification->value) {
            throw new BusinessException('Only client Job Cards awaiting Accounts review can be returned to Operations.', 422);
        }

        $logger = $this->logger;

        return DB::transaction(function () use ($jobCard, $actor, $reason, $logger): JobCard {
            $jobCard->forceFill([
                'approval_status' => JobCardApprovalStatus::Returned->value,
                'returned_by' => $actor->getKey(),
                'returned_at' => now(),
                'return_reason' => $reason,
                'verified_by' => null,
                'verified_at' => null,
                'updated_by' => $actor->getKey(),
            ])->save();

            $logger->log('job_card.returned', 'Client Job Card returned to Operations', $actor, $jobCard, [
                'tenant_id' => $jobCard->tenant_id,
                'company_id' => $jobCard->company_id,
                'job_id' => $jobCard->job_id,
            ]);

            return $jobCard->refresh();
        });
    }
}
