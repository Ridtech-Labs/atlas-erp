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
use Carbon\CarbonImmutable;

class MarkBillingRecordPaidAction
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
            throw new BusinessException('You are not allowed to record payment for this Billing Record.', 403);
        }

        if ((string) $record->getRawOriginal('status') !== BillingRecordStatus::Issued->value) {
            throw new BusinessException('Only issued Billing Records can be marked paid.', 422);
        }

        if (! filled($data['paid_at'] ?? null)) {
            throw new BusinessException('Record the payment date before marking this Billing Record paid.', 422);
        }

        $paymentReference = trim((string) ($data['payment_reference'] ?? ''));

        if ($paymentReference === '') {
            throw new BusinessException('Record the payment reference before marking this Billing Record paid.', 422);
        }

        $record->forceFill([
            'status' => BillingRecordStatus::Paid->value,
            'paid_at' => CarbonImmutable::parse((string) $data['paid_at'])->toDateString(),
            'payment_reference' => $paymentReference,
            'paid_by' => $actor->getKey(),
            'updated_by' => $actor->getKey(),
        ])->save();

        $this->logger->log('billing_record.paid', 'Billing Record payment recorded', $actor, $record, [
            'tenant_id' => $record->tenant_id,
            'company_id' => $record->company_id,
            'client_id' => $record->client_id,
            'billing_record_number' => $record->record_number,
            'payment_reference' => $record->payment_reference,
            'previous_status' => BillingRecordStatus::Issued->value,
            'new_status' => BillingRecordStatus::Paid->value,
        ]);

        return $record->refresh();
    }
}
