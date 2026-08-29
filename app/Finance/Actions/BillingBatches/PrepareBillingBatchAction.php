<?php

declare(strict_types=1);

namespace App\Finance\Actions\BillingBatches;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Models\BillingBatch;
use App\Models\User;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Models\JobCard;
use App\Operations\Models\JobCardWorkEntry;
use Illuminate\Support\Facades\DB;

class PrepareBillingBatchAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(BillingBatch $billingBatch, User $actor): BillingBatch
    {
        if (! $actor->hasPermissionTo(PermissionName::BillingBatchesManage->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $billingBatch->company_id, $billingBatch->tenant_id)) {
            throw new BusinessException('You are not allowed to prepare this Billing Batch.', 403);
        }

        if ((string) $billingBatch->getRawOriginal('status') !== BillingBatchStatus::Draft->value) {
            throw new BusinessException('Only draft Billing Batches can be prepared.', 422);
        }

        if (! $billingBatch->lines()->exists()) {
            throw new BusinessException('Add reviewed Work Entries before preparing this Billing Batch.', 422);
        }

        $logger = $this->logger;

        return DB::transaction(function () use ($billingBatch, $actor, $logger): BillingBatch {
            $periodStart = $billingBatch->lines()->min('activity_date');
            $periodEnd = $billingBatch->lines()->max('activity_date');

            $billingBatch->forceFill([
                'status' => BillingBatchStatus::Prepared->value,
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'prepared_by' => $actor->getKey(),
                'prepared_at' => now(),
                'updated_by' => $actor->getKey(),
            ])->save();

            $jobCardIds = $billingBatch->lines()->distinct()->pluck('job_card_id');

            JobCard::query()
                ->whereIn('id', $jobCardIds)
                ->where('approval_status', JobCardApprovalStatus::Verified->value)
                ->get()
                ->each(function (JobCard $jobCard) use ($actor): void {
                    $totalEntries = JobCardWorkEntry::query()
                        ->where('job_card_id', $jobCard->getKey())
                        ->count();

                    $preparedEntries = JobCardWorkEntry::query()
                        ->where('job_card_id', $jobCard->getKey())
                        ->whereHas('billingBatchLine.billingBatch', fn ($query) => $query->where('status', BillingBatchStatus::Prepared->value))
                        ->count();

                    if ($totalEntries === 0 || $preparedEntries !== $totalEntries) {
                        return;
                    }

                    $jobCard->forceFill([
                        'approval_status' => JobCardApprovalStatus::BillingReady->value,
                        'billing_ready_by' => $actor->getKey(),
                        'billing_ready_at' => now(),
                        'updated_by' => $actor->getKey(),
                    ])->save();
                });

            $logger->log('billing_batch.prepared', 'Billing Batch prepared', $actor, $billingBatch, [
                'tenant_id' => $billingBatch->tenant_id,
                'company_id' => $billingBatch->company_id,
                'client_id' => $billingBatch->client_id,
                'batch_number' => $billingBatch->batch_number,
            ]);

            return $billingBatch->refresh();
        });
    }
}
