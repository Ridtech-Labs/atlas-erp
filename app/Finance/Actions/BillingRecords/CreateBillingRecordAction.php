<?php

declare(strict_types=1);

namespace App\Finance\Actions\BillingRecords;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Shared\Services\TenantSequenceService;
use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Enums\BillingRecordStatus;
use App\Finance\Models\BillingBatch;
use App\Finance\Models\BillingRecord;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateBillingRecordAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly TenantSequenceService $sequences,
    ) {}

    /** @param array<string, mixed> $data */
    public function execute(array $data, User $actor): BillingRecord
    {
        if (! $actor->hasPermissionTo(PermissionName::BillingRecordsManage->value)) {
            throw new BusinessException('You are not allowed to manage Billing Records.', 403);
        }

        $billingBatchId = $data['billing_batch_id'] ?? null;

        if (! is_numeric($billingBatchId)) {
            throw new BusinessException('Select a prepared Billing Batch.', 422);
        }

        return DB::transaction(function () use ($data, $actor, $billingBatchId): BillingRecord {
            $batch = BillingBatch::query()->lockForUpdate()->find((int) $billingBatchId);

            if (! $batch instanceof BillingBatch
                || ! $this->access->canAccessActiveOperationalCompany($actor, $batch->company_id, $batch->tenant_id)) {
                throw new BusinessException('You are not allowed to create a Billing Record for the selected Billing Batch.', 403);
            }

            if ((string) $batch->getRawOriginal('status') !== BillingBatchStatus::Prepared->value) {
                throw new BusinessException('Only prepared Billing Batches can create Billing Records.', 422);
            }

            if (! $batch->lines()->exists()) {
                throw new BusinessException('A Billing Batch must contain billable lines before a Billing Record can be created.', 422);
            }

            if ($batch->hasMixedCurrencies()) {
                throw new BusinessException('Mixed-currency Billing Batches cannot create a Billing Record.', 422);
            }

            if (BillingRecord::withTrashed()->where('billing_batch_id', $batch->getKey())->exists()) {
                throw new BusinessException('This Billing Batch already has a Billing Record.', 422);
            }

            $currency = array_key_first($batch->subtotalAmountsByCurrency());

            if (! is_string($currency) || $currency === '') {
                throw new BusinessException('The prepared Billing Batch has no commercial currency to snapshot.', 422);
            }

            $record = BillingRecord::query()->create([
                'tenant_id' => $batch->tenant_id,
                'company_id' => $batch->company_id,
                'client_id' => $batch->client_id,
                'billing_batch_id' => $batch->getKey(),
                'record_number' => sprintf('BR-%05d', $this->sequences->nextValue($batch->tenant_id, 'billing_record_number')),
                'status' => BillingRecordStatus::Draft->value,
                'batch_amount' => $batch->subtotalAmount(),
                'currency' => $currency,
                'notes' => filled($data['notes'] ?? null) ? trim((string) $data['notes']) : null,
                'created_by' => $actor->getKey(),
                'updated_by' => $actor->getKey(),
            ]);

            $this->logger->log('billing_record.created', 'Billing Record draft created', $actor, $record, [
                'tenant_id' => $record->tenant_id,
                'company_id' => $record->company_id,
                'client_id' => $record->client_id,
                'billing_batch_number' => $batch->batch_number,
                'billing_record_number' => $record->record_number,
                'batch_amount' => $record->batch_amount,
                'currency' => $record->currency,
            ]);

            return $record->refresh();
        });
    }
}
