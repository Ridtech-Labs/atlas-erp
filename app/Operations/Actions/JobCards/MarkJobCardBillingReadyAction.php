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

class MarkJobCardBillingReadyAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(JobCard $jobCard, User $actor): JobCard
    {
        if (! $actor->hasPermissionTo(PermissionName::JobCardsBill->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $jobCard->company_id, $jobCard->tenant_id)) {
            throw new BusinessException('You are not allowed to mark this client Job Card as billing ready.', 403);
        }

        if ((string) $jobCard->getRawOriginal('approval_status') !== JobCardApprovalStatus::Verified->value) {
            throw new BusinessException('Only verified client Job Cards can move to billing ready.', 422);
        }

        if ($jobCard->total_hours === null || $jobCard->hourly_rate === null) {
            throw new BusinessException('Total hours and an hourly rate are required before a client Job Card can become billing ready.', 422);
        }

        return DB::transaction(function () use ($jobCard, $actor): JobCard {
            $jobCard->syncBillingFigures();

            $jobCard->forceFill([
                'approval_status' => JobCardApprovalStatus::BillingReady->value,
                'billing_ready_by' => $actor->getKey(),
                'billing_ready_at' => now(),
                'billable_amount' => $jobCard->calculateBillableAmount(),
                'updated_by' => $actor->getKey(),
            ])->save();

            $this->logger->log('job_card.billing_ready', 'Client Job Card marked billing ready', $actor, $jobCard, [
                'tenant_id' => $jobCard->tenant_id,
                'company_id' => $jobCard->company_id,
                'job_id' => $jobCard->job_id,
            ]);

            return $jobCard->refresh();
        });
    }
}
