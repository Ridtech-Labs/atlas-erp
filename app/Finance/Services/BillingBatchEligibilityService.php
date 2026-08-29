<?php

declare(strict_types=1);

namespace App\Finance\Services;

use App\Finance\Models\BillingBatch;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Models\JobCardWorkEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class BillingBatchEligibilityService
{
    /**
     * @return Builder<JobCardWorkEntry>
     */
    public function eligibleWorkEntriesQuery(BillingBatch $billingBatch): Builder
    {
        return JobCardWorkEntry::query()
            ->with(['jobCard.job'])
            ->whereHas('jobCard', function (Builder $query) use ($billingBatch): void {
                $query
                    ->where('tenant_id', $billingBatch->tenant_id)
                    ->where('company_id', $billingBatch->company_id)
                    ->where('client_id', $billingBatch->client_id)
                    ->where('approval_status', JobCardApprovalStatus::Verified->value);
            })
            ->whereDoesntHave('billingBatchLine')
            ->orderBy('job_card_id')
            ->orderBy('from_time');
    }

    /**
     * @return Collection<int, JobCardWorkEntry>
     */
    public function eligibleWorkEntries(BillingBatch $billingBatch): Collection
    {
        return $this->eligibleWorkEntriesQuery($billingBatch)->get();
    }
}
