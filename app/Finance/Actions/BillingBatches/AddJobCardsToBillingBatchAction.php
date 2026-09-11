<?php

declare(strict_types=1);

namespace App\Finance\Actions\BillingBatches;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Enums\BillingSourceType;
use App\Finance\Models\BillingBatch;
use App\Finance\Services\RateResolverService;
use App\Models\User;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Models\JobCardWorkEntry;
use Illuminate\Support\Facades\DB;

class AddJobCardsToBillingBatchAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly RateResolverService $rates,
    ) {}

    /**
     * @param  list<int>  $workEntryIds
     */
    public function execute(BillingBatch $billingBatch, array $workEntryIds, User $actor): BillingBatch
    {
        if (! $actor->hasPermissionTo(PermissionName::BillingBatchesManage->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $billingBatch->company_id, $billingBatch->tenant_id)) {
            throw new BusinessException('You are not allowed to manage this Billing Batch.', 403);
        }

        if ((string) $billingBatch->getRawOriginal('status') !== BillingBatchStatus::Draft->value) {
            throw new BusinessException('Only draft Billing Batches can accept reviewed Work Entries.', 422);
        }

        if ($workEntryIds === []) {
            throw new BusinessException('Select at least one reviewed Work Entry to add to the batch.', 422);
        }

        $rates = $this->rates;
        $logger = $this->logger;

        return DB::transaction(function () use ($billingBatch, $workEntryIds, $actor, $rates, $logger): BillingBatch {
            $workEntries = JobCardWorkEntry::query()
                ->with(['jobCard.job'])
                ->whereIn('id', $workEntryIds)
                ->lockForUpdate()
                ->get();

            $batchCurrencies = $billingBatch->lines()->pluck('currency')->unique()->values();

            foreach ($workEntries as $workEntry) {
                $jobCard = $workEntry->jobCard()->with('job')->firstOrFail();

                if ($jobCard->tenant_id !== $billingBatch->tenant_id
                    || $jobCard->company_id !== $billingBatch->company_id
                    || $jobCard->client_id !== $billingBatch->client_id) {
                    throw new BusinessException('A Billing Batch can only include Work Entries for its own client and company.', 422);
                }

                if ((string) $jobCard->getRawOriginal('approval_status') !== JobCardApprovalStatus::Verified->value) {
                    throw new BusinessException('Only Accounts-reviewed Work Entries can be added to a Billing Batch.', 422);
                }

                if ($workEntry->billingBatchLine()->exists()) {
                    throw new BusinessException('A Work Entry can only be included in one active Billing Batch.', 422);
                }

                $resolvedRate = $rates->resolveForWorkEntry($workEntry);
                $currency = (string) $resolvedRate['currency'];

                if ($batchCurrencies->isNotEmpty() && ! $batchCurrencies->contains($currency)) {
                    throw new BusinessException('Mixed-currency Billing Batches are not supported yet. Create a separate batch for each currency.', 422);
                }

                $hours = round((float) $workEntry->total_hours, 2);
                $rate = round((float) $resolvedRate['rate'], 2);

                $billingBatch->lines()->create([
                    'source_type' => BillingSourceType::HeavyMachineryWorkEntry->value,
                    'source_id' => $workEntry->getKey(),
                    'source_reference' => $jobCard->card_number,
                    'work_entry_id' => $workEntry->getKey(),
                    'job_card_id' => $jobCard->getKey(),
                    'job_id' => $jobCard->job_id,
                    'job_reference' => $jobCard->job?->job_number,
                    'activity_date' => $jobCard->card_date,
                    'vessel' => $workEntry->vessel,
                    'work_area' => $workEntry->work_area,
                    'from_time' => $workEntry->from_time,
                    'to_time' => $workEntry->to_time,
                    'equipment_reference' => $jobCard->equipment_reference,
                    'machine_number' => $jobCard->machine_number,
                    'hours' => $hours,
                    'quantity' => $hours,
                    'billing_unit' => 'hourly',
                    'resolved_rate' => $rate,
                    'currency' => $currency,
                    'line_amount' => round($hours * $rate, 2),
                    'rate_agreement_id' => $resolvedRate['rate_agreement_id'],
                    'rate_agreement_line_id' => $resolvedRate['rate_agreement_line_id'],
                ]);

                $batchCurrencies = collect([$currency]);
            }

            $periodStart = $billingBatch->lines()->min('activity_date');
            $periodEnd = $billingBatch->lines()->max('activity_date');

            $billingBatch->forceFill([
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'updated_by' => $actor->getKey(),
            ])->saveQuietly();

            $logger->log('billing_batch.lines_added', 'Reviewed Work Entries added to Billing Batch', $actor, $billingBatch, [
                'tenant_id' => $billingBatch->tenant_id,
                'company_id' => $billingBatch->company_id,
                'client_id' => $billingBatch->client_id,
                'work_entry_ids' => $workEntryIds,
            ]);

            return $billingBatch->refresh();
        });
    }
}
