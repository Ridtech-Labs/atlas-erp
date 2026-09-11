<?php

declare(strict_types=1);

namespace App\Finance\Services;

use App\Core\Shared\Exceptions\BusinessException;
use App\Finance\Models\BillingBatch;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Enums\WaybillStatus;
use App\Operations\Models\JobCardWorkEntry;
use App\Operations\Models\Waybill;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class BillingBatchEligibilityService
{
    public function __construct(
        private readonly TruckingRateResolverService $truckingRates,
    ) {}

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

    /** @return Collection<int, Waybill> */
    public function eligibleWaybills(BillingBatch $billingBatch): Collection
    {
        $currencies = $billingBatch->lines()->pluck('currency')->unique();

        return Waybill::query()
            ->with('job')
            ->where('tenant_id', $billingBatch->tenant_id)
            ->where('company_id', $billingBatch->company_id)
            ->where('client_id', $billingBatch->client_id)
            ->where('status', WaybillStatus::Verified->value)
            ->where('number_of_trips', '>=', 1)
            ->whereHas('job', fn (Builder $query) => $query->where('job_type', 'trucking'))
            ->whereDoesntHave('billingBatchLine')
            ->orderBy('waybill_date')
            ->get()
            ->filter(function (Waybill $waybill) use ($currencies): bool {
                try {
                    $rate = $this->truckingRates->resolveForWaybill($waybill);
                } catch (BusinessException) {
                    return false;
                }

                return $currencies->isEmpty() || $currencies->contains($rate['currency']);
            })
            ->values();
    }
}
