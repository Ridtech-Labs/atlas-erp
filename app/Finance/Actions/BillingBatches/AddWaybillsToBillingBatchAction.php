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
use App\Finance\Services\TruckingRateResolverService;
use App\Models\User;
use App\Operations\Enums\WaybillStatus;
use App\Operations\Models\Job;
use App\Operations\Models\Waybill;
use Illuminate\Support\Facades\DB;

class AddWaybillsToBillingBatchAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly TruckingRateResolverService $rates,
    ) {}

    /** @param list<int> $waybillIds */
    public function execute(BillingBatch $batch, array $waybillIds, User $actor): BillingBatch
    {
        if (! $actor->hasPermissionTo(PermissionName::BillingBatchesManage->value) || ! $this->access->canAccessActiveOperationalCompany($actor, $batch->company_id, $batch->tenant_id)) {
            throw new BusinessException('You are not allowed to manage this Billing Batch.', 403);
        }
        if ((string) $batch->getRawOriginal('status') !== BillingBatchStatus::Draft->value || $waybillIds === []) {
            throw new BusinessException('Only draft Billing Batches can accept selected Waybills.', 422);
        }

        return DB::transaction(function () use ($batch, $waybillIds, $actor): BillingBatch {
            $currencies = $batch->lines()->pluck('currency')->unique();
            $waybills = Waybill::query()->with('job')->whereIn('id', $waybillIds)->lockForUpdate()->get();
            if ($waybills->count() !== count(array_unique($waybillIds))) {
                throw new BusinessException('One or more selected Waybills could not be found.', 422);
            }
            foreach ($waybills as $waybill) {
                $job = $waybill->job;
                if (! $job instanceof Job || $waybill->tenant_id !== $batch->tenant_id || $waybill->company_id !== $batch->company_id || $waybill->client_id !== $batch->client_id || ! $job->isTrucking() || (string) $waybill->getRawOriginal('status') !== WaybillStatus::Verified->value || (int) $waybill->number_of_trips < 1) {
                    throw new BusinessException('Only verified Trucking Waybills for this Billing Batch client can be added.', 422);
                }
                if ($waybill->billingBatchLine()->exists()) {
                    throw new BusinessException('A Waybill can only be included in one Billing Batch.', 422);
                }
                $rate = $this->rates->resolveForWaybill($waybill);
                if ($currencies->isNotEmpty() && ! $currencies->contains($rate['currency'])) {
                    throw new BusinessException('Mixed-currency Billing Batches are not supported yet. Create a separate batch for each currency.', 422);
                }
                $quantity = (float) $waybill->number_of_trips;
                $unitRate = (float) $rate['rate'];
                $batch->lines()->create(['source_type' => BillingSourceType::TruckingWaybill->value, 'source_id' => $waybill->getKey(), 'source_reference' => $waybill->waybill_number, 'job_id' => $waybill->job_id, 'job_reference' => $job->job_number, 'activity_date' => $waybill->waybill_date, 'pickup_point' => $waybill->pickup_point, 'destination' => $waybill->destination, 'truck_number' => $waybill->truck_number, 'driver_name' => $waybill->driver_name, 'client_reference' => $waybill->client_reference, 'hours' => null, 'quantity' => $quantity, 'billing_unit' => 'trip', 'resolved_rate' => $unitRate, 'currency' => $rate['currency'], 'line_amount' => round($quantity * $unitRate, 2), 'rate_agreement_id' => $rate['rate_agreement_id'], 'rate_agreement_line_id' => $rate['rate_agreement_line_id']]);
                $currencies = collect([$rate['currency']]);
            }
            $batch->forceFill(['period_start' => $batch->lines()->min('activity_date'), 'period_end' => $batch->lines()->max('activity_date'), 'updated_by' => $actor->getKey()])->saveQuietly();
            $this->logger->log('billing_batch.waybills_added', 'Verified Waybills added to Billing Batch', $actor, $batch, ['tenant_id' => $batch->tenant_id, 'company_id' => $batch->company_id, 'waybill_ids' => $waybillIds]);

            return $batch->refresh();
        });
    }
}
