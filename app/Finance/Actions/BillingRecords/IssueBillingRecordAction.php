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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IssueBillingRecordAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @param  list<string>  $attachmentPaths
     */
    public function execute(BillingRecord $record, array $data, User $actor, array $attachmentPaths): BillingRecord
    {
        if (! $actor->hasPermissionTo(PermissionName::BillingRecordsManage->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $record->company_id, $record->tenant_id)) {
            throw new BusinessException('You are not allowed to issue this Billing Record.', 403);
        }

        if ((string) $record->getRawOriginal('status') !== BillingRecordStatus::Draft->value) {
            throw new BusinessException('Only draft Billing Records can be issued.', 422);
        }

        $reference = trim((string) ($data['external_receipt_reference'] ?? ''));
        $issuedAt = $data['issued_at'] ?? null;
        $receiptAmount = $data['receipt_amount'] ?? null;
        $notes = filled($data['notes'] ?? null) ? trim((string) $data['notes']) : $record->notes;

        if ($reference === '') {
            throw new BusinessException('Record the external VAT receipt reference before issuing this Billing Record.', 422);
        }

        if (BillingRecord::withTrashed()
            ->where('tenant_id', $record->tenant_id)
            ->where('company_id', $record->company_id)
            ->where('external_receipt_reference', $reference)
            ->whereKeyNot($record->getKey())
            ->exists()) {
            throw new BusinessException('This external VAT receipt reference is already recorded for the active company.', 422);
        }

        if (! filled($issuedAt)) {
            throw new BusinessException('Record the VAT receipt issue date before issuing this Billing Record.', 422);
        }

        $amount = $this->normalizeMoney($receiptAmount);
        $batchAmount = $this->normalizeMoney($record->batch_amount);

        if ($amount === null || $batchAmount === null) {
            throw new BusinessException('Record a valid VAT receipt amount before issuing this Billing Record.', 422);
        }

        if ($amount !== $batchAmount) {
            throw new BusinessException('The VAT receipt amount must exactly match the system-calculated Billing Batch amount before this Billing Record can be issued.', 422);
        }

        if ($attachmentPaths === [] && $record->getMedia('vat-receipt')->isEmpty()) {
            throw new BusinessException('Attach the external VAT receipt before issuing this Billing Record.', 422);
        }

        return DB::transaction(function () use ($record, $actor, $attachmentPaths, $reference, $issuedAt, $amount, $notes): BillingRecord {
            foreach ($attachmentPaths as $path) {
                if ($path !== '') {
                    $record->addMedia(Storage::disk('local')->path($path))
                        ->preservingOriginal()
                        ->toMediaCollection('vat-receipt');
                }
            }

            $record->forceFill([
                'status' => BillingRecordStatus::Issued->value,
                'external_receipt_reference' => $reference,
                'issued_at' => CarbonImmutable::parse((string) $issuedAt)->toDateString(),
                'receipt_amount' => $amount,
                'notes' => $notes,
                'issued_by' => $actor->getKey(),
                'updated_by' => $actor->getKey(),
            ])->save();

            $this->logger->log('billing_record.issued', 'External VAT receipt recorded', $actor, $record, [
                'tenant_id' => $record->tenant_id,
                'company_id' => $record->company_id,
                'client_id' => $record->client_id,
                'billing_record_number' => $record->record_number,
                'billing_batch_number' => $record->billingBatch->batch_number,
                'external_receipt_reference' => $record->external_receipt_reference,
                'batch_amount' => $record->batch_amount,
                'receipt_amount' => $record->receipt_amount,
                'variance' => '0.00',
                'currency' => $record->currency,
                'previous_status' => BillingRecordStatus::Draft->value,
                'new_status' => BillingRecordStatus::Issued->value,
            ]);

            return $record->refresh();
        });
    }

    private function normalizeMoney(mixed $amount): ?string
    {
        if (! is_scalar($amount)) {
            return null;
        }

        $value = trim((string) $amount);

        if (! preg_match('/^(0|[1-9]\d*)(?:\.(\d{1,2}))?$/', $value, $matches)) {
            return null;
        }

        return sprintf('%s.%02d', $matches[1], (int) ($matches[2] ?? 0));
    }
}
