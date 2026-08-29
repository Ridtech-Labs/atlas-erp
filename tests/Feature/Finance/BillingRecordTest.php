<?php

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Resources\BillingRecords\BillingRecordResource;
use App\Core\Shared\Exceptions\BusinessException;
use App\CRM\Models\Client;
use App\Finance\Actions\BillingRecords\CloseBillingRecordAction;
use App\Finance\Actions\BillingRecords\CreateBillingRecordAction;
use App\Finance\Actions\BillingRecords\IssueBillingRecordAction;
use App\Finance\Actions\BillingRecords\MarkBillingRecordPaidAction;
use App\Finance\Actions\BillingRecords\UpdateBillingRecordDraftAction;
use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Enums\BillingRecordStatus;
use App\Finance\Models\BillingBatch;
use App\Finance\Models\BillingRecord;
use App\Models\User;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Models\JobCardWorkEntry;
use Illuminate\Support\Facades\Storage;

function preparedBillingBatchForBillingRecord(Client $client, User $actor, float $amount = 4000.00): BillingBatch
{
    $job = Job::factory()->create([
        'tenant_id' => $client->tenant_id,
        'company_id' => $client->company_id,
        'client_id' => $client->getKey(),
        'status' => JobStatus::InProgress,
    ]);
    $card = JobCard::factory()->create([
        'tenant_id' => $client->tenant_id,
        'company_id' => $client->company_id,
        'client_id' => $client->getKey(),
        'job_id' => $job->getKey(),
        'approval_status' => JobCardApprovalStatus::BillingReady,
    ]);
    $entry = JobCardWorkEntry::factory()->create(['job_card_id' => $card->getKey(), 'total_hours' => 8.00]);
    $batch = BillingBatch::factory()->create([
        'tenant_id' => $client->tenant_id,
        'company_id' => $client->company_id,
        'client_id' => $client->getKey(),
        'status' => BillingBatchStatus::Prepared,
        'prepared_at' => now(),
        'prepared_by' => $actor->getKey(),
    ]);
    $batch->lines()->create([
        'work_entry_id' => $entry->getKey(),
        'job_card_id' => $card->getKey(),
        'job_id' => $job->getKey(),
        'job_reference' => $job->job_number,
        'activity_date' => '2026-08-20',
        'hours' => 8.00,
        'resolved_rate' => $amount / 8,
        'currency' => 'GHS',
        'line_amount' => $amount,
    ]);

    return $batch->fresh(['lines', 'client']);
}

function financeActorForBillingRecord(): array
{
    test()->seedAccessControl();
    $tenant = test()->tenant();
    $company = test()->company($tenant, ['name' => 'Kadmay']);
    $finance = test()->tenantUser($tenant, ['email' => 'billing-record-finance@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);
    $client = Client::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'legal_name' => 'GPHA']);

    return [$tenant, $company, $finance, $client];
}

test('finance manager and accountant can create a Billing Record from a prepared Billing Batch with trusted commercial context', function () {
    [, , $finance, $client] = financeActorForBillingRecord();
    $batch = preparedBillingBatchForBillingRecord($client, $finance, 4000.00);

    $record = app(CreateBillingRecordAction::class)->execute([
        'billing_batch_id' => $batch->getKey(),
        'client_id' => 999999,
        'batch_amount' => 1,
        'currency' => 'USD',
    ], $finance);

    expect($record->status)->toBe(BillingRecordStatus::Draft)
        ->and($record->tenant_id)->toBe($batch->tenant_id)
        ->and($record->company_id)->toBe($batch->company_id)
        ->and($record->client_id)->toBe($batch->client_id)
        ->and((float) $record->batch_amount)->toBe(4000.0)
        ->and($record->currency)->toBe('GHS')
        ->and($record->record_number)->toStartWith('BR-');

    $accountant = $this->tenantUser($client->tenant, ['email' => 'billing-record-accountant@example.test'], [RoleName::Accountant->value]);
    $accountant->companies()->sync([$client->company_id]);
    session(['active_company_id' => $client->company_id]);
    $secondBatch = preparedBillingBatchForBillingRecord($client, $finance);

    expect(app(CreateBillingRecordAction::class)->execute(['billing_batch_id' => $secondBatch->getKey()], $accountant))
        ->toBeInstanceOf(BillingRecord::class);
});

test('operations and cross-company finance cannot create Billing Records', function () {
    [$tenant, $company, $finance, $client] = financeActorForBillingRecord();
    $batch = preparedBillingBatchForBillingRecord($client, $finance);
    $operations = $this->tenantUser($tenant, ['email' => 'billing-record-ops@example.test'], [RoleName::OperationsManager->value]);
    $operations->companies()->sync([$company->getKey()]);

    expect(fn () => app(CreateBillingRecordAction::class)->execute(['billing_batch_id' => $batch->getKey()], $operations))
        ->toThrow(BusinessException::class);

    $otherCompany = $this->company($tenant, ['name' => 'Other Kadmay Company']);
    $otherFinance = $this->tenantUser($tenant, ['email' => 'billing-record-other@example.test'], [RoleName::FinanceManager->value]);
    $otherFinance->companies()->sync([$otherCompany->getKey()]);
    session(['active_company_id' => $otherCompany->getKey()]);

    expect(fn () => app(CreateBillingRecordAction::class)->execute(['billing_batch_id' => $batch->getKey()], $otherFinance))
        ->toThrow(BusinessException::class);
});

test('Finance navigation resource is available to Finance Manager and denied to Operations', function () {
    [$tenant, $company, $finance] = financeActorForBillingRecord();
    $operations = $this->tenantUser($tenant, ['email' => 'billing-record-navigation-ops@example.test'], [RoleName::OperationsManager->value]);
    $operations->companies()->sync([$company->getKey()]);
    $url = BillingRecordResource::getUrl('index');

    $this->actingAs($finance)
        ->withSession(['active_company_id' => $company->getKey()])
        ->get($url)
        ->assertOk()
        ->assertSee('Billing Records')
        ->assertSee('href="'.$url.'"', false);

    $this->actingAs($operations)
        ->withSession(['active_company_id' => $company->getKey()])
        ->get($url)
        ->assertForbidden();
});

test('only non-empty prepared unlinked single-currency batches can create Billing Records', function () {
    [, , $finance, $client] = financeActorForBillingRecord();
    $draftBatch = BillingBatch::factory()->create([
        'tenant_id' => $client->tenant_id,
        'company_id' => $client->company_id,
        'client_id' => $client->getKey(),
        'status' => BillingBatchStatus::Draft,
    ]);

    expect(fn () => app(CreateBillingRecordAction::class)->execute(['billing_batch_id' => $draftBatch->getKey()], $finance))
        ->toThrow(BusinessException::class);

    $emptyPreparedBatch = BillingBatch::factory()->create([
        'tenant_id' => $client->tenant_id,
        'company_id' => $client->company_id,
        'client_id' => $client->getKey(),
        'status' => BillingBatchStatus::Prepared,
    ]);

    expect(fn () => app(CreateBillingRecordAction::class)->execute(['billing_batch_id' => $emptyPreparedBatch->getKey()], $finance))
        ->toThrow(BusinessException::class);

    $batch = preparedBillingBatchForBillingRecord($client, $finance);
    app(CreateBillingRecordAction::class)->execute(['billing_batch_id' => $batch->getKey()], $finance);

    expect(fn () => app(CreateBillingRecordAction::class)->execute(['billing_batch_id' => $batch->getKey()], $finance))
        ->toThrow(BusinessException::class);
});

test('issuance requires an exact VAT receipt amount and never permits a pricing override', function () {
    [, , $finance, $client] = financeActorForBillingRecord();
    $record = app(CreateBillingRecordAction::class)->execute(['billing_batch_id' => preparedBillingBatchForBillingRecord($client, $finance)->getKey()], $finance);

    expect(fn () => app(IssueBillingRecordAction::class)->execute($record, [], $finance, []))
        ->toThrow(BusinessException::class);

    Storage::disk('local')->put('billing-record-receipts/receipt.txt', 'receipt');

    expect(fn () => app(IssueBillingRecordAction::class)->execute($record, [
        'external_receipt_reference' => 'VAT-001',
        'issued_at' => '2026-08-29',
        'receipt_amount' => 4100.00,
        'notes' => 'This note must not authorize a commercial override.',
    ], $finance, ['billing-record-receipts/receipt.txt']))->toThrow(BusinessException::class, 'must exactly match');

    expect($record->fresh())
        ->status->toBe(BillingRecordStatus::Draft)
        ->receipt_amount->toBeNull()
        ->external_receipt_reference->toBeNull()
        ->paid_at->toBeNull()
        ->and($record->fresh()->getMedia('vat-receipt'))->toHaveCount(0);

    expect(fn () => app(IssueBillingRecordAction::class)->execute($record, [
        'external_receipt_reference' => 'VAT-001',
        'issued_at' => '2026-08-29',
        'receipt_amount' => 3900.00,
        'notes' => 'This note must not authorize a commercial override either.',
    ], $finance, ['billing-record-receipts/receipt.txt']))->toThrow(BusinessException::class, 'must exactly match');

    $issued = app(IssueBillingRecordAction::class)->execute($record, [
        'external_receipt_reference' => 'VAT-001',
        'issued_at' => '2026-08-29',
        'receipt_amount' => 4000.00,
        'notes' => 'External VAT receipt recorded by Finance.',
    ], $finance, ['billing-record-receipts/receipt.txt']);

    expect($issued->status)->toBe(BillingRecordStatus::Issued)
        ->and($issued->receiptAmountMatchesBatch())->toBeTrue()
        ->and((float) $issued->receipt_amount)->toBe(4000.0)
        ->and($issued->getMedia('vat-receipt'))->toHaveCount(1);
});

test('payment and closure require the approved forward lifecycle and financial history remains immutable', function () {
    [, , $finance, $client] = financeActorForBillingRecord();
    $record = app(CreateBillingRecordAction::class)->execute(['billing_batch_id' => preparedBillingBatchForBillingRecord($client, $finance)->getKey()], $finance);
    Storage::disk('local')->put('billing-record-receipts/lifecycle.txt', 'receipt');
    $record = app(IssueBillingRecordAction::class)->execute($record, [
        'external_receipt_reference' => 'VAT-002',
        'issued_at' => '2026-08-29',
        'receipt_amount' => 4000.00,
    ], $finance, ['billing-record-receipts/lifecycle.txt']);

    expect(fn () => app(MarkBillingRecordPaidAction::class)->execute($record, [], $finance))->toThrow(BusinessException::class);
    expect(fn () => app(CloseBillingRecordAction::class)->execute($record, $finance))->toThrow(BusinessException::class);
    expect(fn () => app(UpdateBillingRecordDraftAction::class)->execute($record, ['notes' => 'rewrite'], $finance))->toThrow(BusinessException::class);

    $paid = app(MarkBillingRecordPaidAction::class)->execute($record, [
        'paid_at' => '2026-08-30',
        'payment_reference' => 'BANK-123',
    ], $finance);
    $closed = app(CloseBillingRecordAction::class)->execute($paid, $finance);

    expect($closed->status)->toBe(BillingRecordStatus::Closed)
        ->and($closed->payment_reference)->toBe('BANK-123')
        ->and((float) $closed->batch_amount)->toBe(4000.0)
        ->and((float) $closed->receipt_amount)->toBe(4000.0)
        ->and($closed->receiptAmountMatchesBatch())->toBeTrue();
});

test('external VAT receipt references are unique within a company and billing snapshots stay unchanged', function () {
    [, , $finance, $client] = financeActorForBillingRecord();
    $first = app(CreateBillingRecordAction::class)->execute(['billing_batch_id' => preparedBillingBatchForBillingRecord($client, $finance, 4000.00)->getKey()], $finance);
    $second = app(CreateBillingRecordAction::class)->execute(['billing_batch_id' => preparedBillingBatchForBillingRecord($client, $finance, 5200.00)->getKey()], $finance);
    Storage::disk('local')->put('billing-record-receipts/unique-first.txt', 'first');
    Storage::disk('local')->put('billing-record-receipts/unique-second.txt', 'second');

    app(IssueBillingRecordAction::class)->execute($first, [
        'external_receipt_reference' => 'VAT-UNIQUE-001',
        'issued_at' => '2026-08-29',
        'receipt_amount' => 4000.00,
    ], $finance, ['billing-record-receipts/unique-first.txt']);

    expect(fn () => app(IssueBillingRecordAction::class)->execute($second, [
        'external_receipt_reference' => 'VAT-UNIQUE-001',
        'issued_at' => '2026-08-29',
        'receipt_amount' => 5200.00,
    ], $finance, ['billing-record-receipts/unique-second.txt']))->toThrow(BusinessException::class);

    $first->billingBatch->lines()->update(['line_amount' => 9999.00]);

    expect((float) $first->fresh()->batch_amount)->toBe(4000.0);
});

test('VAT receipt download is protected by the Billing Record policy and receipt reference is searchable', function () {
    [$tenant, $company, $finance, $client] = financeActorForBillingRecord();
    $record = app(CreateBillingRecordAction::class)->execute(['billing_batch_id' => preparedBillingBatchForBillingRecord($client, $finance)->getKey()], $finance);
    $record->addMediaFromString('receipt')->usingFileName('receipt.txt')->toMediaCollection('vat-receipt');
    $media = $record->getFirstMedia('vat-receipt');
    $record->forceFill(['external_receipt_reference' => 'VAT-FIND-001'])->save();

    $this->actingAs($finance)
        ->get(route('atlas.billing-records.receipts.show', ['billingRecord' => $record, 'media' => $media]))
        ->assertOk();

    $operations = $this->tenantUser($tenant, ['email' => 'billing-record-document-ops@example.test'], [RoleName::OperationsManager->value]);
    $operations->companies()->sync([$company->getKey()]);
    $this->actingAs($operations)
        ->get(route('atlas.billing-records.receipts.show', ['billingRecord' => $record, 'media' => $media]))
        ->assertForbidden();

    expect(BillingRecord::query()->where('external_receipt_reference', 'VAT-FIND-001')->exists())->toBeTrue();
});
