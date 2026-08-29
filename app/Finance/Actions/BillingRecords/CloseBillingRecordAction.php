<?php

declare(strict_types=1);

namespace App\Finance\Actions\BillingRecords;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Finance\Enums\BillingRecordStatus;
use App\Finance\Models\BillingRecord;
use App\Models\User;

class CloseBillingRecordAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(BillingRecord $record, User $actor): BillingRecord
    {
        if (! $actor->hasPermissionTo(PermissionName::BillingRecordsManage->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $record->company_id, $record->tenant_id)) {
            throw new BusinessException('You are not allowed to close this Billing Record.', 403);
        }

        if ((string) $record->getRawOriginal('status') !== BillingRecordStatus::Paid->value) {
            throw new BusinessException('Only paid Billing Records can be closed.', 422);
        }

        $record->forceFill([
            'status' => BillingRecordStatus::Closed->value,
            'closed_by' => $actor->getKey(),
            'updated_by' => $actor->getKey(),
        ])->save();

        $this->logger->log('billing_record.closed', 'Billing Record closed', $actor, $record, [
            'tenant_id' => $record->tenant_id,
            'company_id' => $record->company_id,
            'client_id' => $record->client_id,
            'billing_record_number' => $record->record_number,
            'previous_status' => BillingRecordStatus::Paid->value,
            'new_status' => BillingRecordStatus::Closed->value,
        ]);

        return $record->refresh();
    }
}
