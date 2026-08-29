<?php

declare(strict_types=1);

namespace App\Finance\Actions\BillingBatches;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Models\BillingBatchLine;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RemoveBillingBatchLineAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(BillingBatchLine $line, User $actor): void
    {
        $batch = $line->billingBatch()->firstOrFail();

        if (! $actor->hasPermissionTo(PermissionName::BillingBatchesManage->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $batch->company_id, $batch->tenant_id)) {
            throw new BusinessException('You are not allowed to manage this Billing Batch.', 403);
        }

        if ((string) $batch->getRawOriginal('status') !== BillingBatchStatus::Draft->value) {
            throw new BusinessException('Prepared Billing Batches are immutable through normal workflows.', 422);
        }

        $logger = $this->logger;

        DB::transaction(function () use ($line, $batch, $actor, $logger): void {
            $line->delete();

            $batch->forceFill([
                'period_start' => $batch->lines()->min('activity_date'),
                'period_end' => $batch->lines()->max('activity_date'),
                'updated_by' => $actor->getKey(),
            ])->saveQuietly();

            $logger->log('billing_batch.line_removed', 'Billing Batch line removed', $actor, $batch, [
                'tenant_id' => $batch->tenant_id,
                'company_id' => $batch->company_id,
                'client_id' => $batch->client_id,
                'work_entry_id' => $line->work_entry_id,
            ]);
        });
    }
}
