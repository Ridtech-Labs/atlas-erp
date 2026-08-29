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

class DeleteBillingRecordAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(BillingRecord $record, User $actor): void
    {
        if (! $actor->hasPermissionTo(PermissionName::BillingRecordsManage->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $record->company_id, $record->tenant_id)) {
            throw new BusinessException('You are not allowed to delete this Billing Record.', 403);
        }

        if ((string) $record->getRawOriginal('status') !== BillingRecordStatus::Draft->value) {
            throw new BusinessException('Only draft Billing Records can be deleted.', 422);
        }

        $this->logger->log('billing_record.deleted', 'Billing Record draft deleted', $actor, $record, [
            'tenant_id' => $record->tenant_id,
            'company_id' => $record->company_id,
            'client_id' => $record->client_id,
            'billing_record_number' => $record->record_number,
        ]);

        $record->delete();
    }
}
