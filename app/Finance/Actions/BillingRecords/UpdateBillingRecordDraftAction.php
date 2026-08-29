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

class UpdateBillingRecordDraftAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /** @param array<string, mixed> $data */
    public function execute(BillingRecord $record, array $data, User $actor): BillingRecord
    {
        if (! $actor->hasPermissionTo(PermissionName::BillingRecordsManage->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $record->company_id, $record->tenant_id)) {
            throw new BusinessException('You are not allowed to update this Billing Record.', 403);
        }

        if ((string) $record->getRawOriginal('status') !== BillingRecordStatus::Draft->value) {
            throw new BusinessException('Issued, paid, and closed Billing Records are immutable.', 422);
        }

        $record->forceFill([
            'notes' => filled($data['notes'] ?? null) ? trim((string) $data['notes']) : null,
            'updated_by' => $actor->getKey(),
        ])->save();

        $this->logger->log('billing_record.updated', 'Billing Record draft updated', $actor, $record, [
            'tenant_id' => $record->tenant_id,
            'company_id' => $record->company_id,
            'client_id' => $record->client_id,
            'billing_record_number' => $record->record_number,
        ]);

        return $record->refresh();
    }
}
