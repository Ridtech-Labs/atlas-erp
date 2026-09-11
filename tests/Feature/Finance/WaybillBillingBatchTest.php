<?php

use App\Administration\Enums\RoleName;
use App\Core\Shared\Exceptions\BusinessException;
use App\CRM\Models\Client;
use App\Finance\Actions\BillingBatches\AddWaybillsToBillingBatchAction;
use App\Finance\Actions\BillingBatches\PrepareBillingBatchAction;
use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Enums\BillingSourceType;
use App\Finance\Enums\BillingUnit;
use App\Finance\Enums\RateAgreementStatus;
use App\Finance\Models\BillingBatch;
use App\Finance\Models\RateAgreement;
use App\Finance\Models\RateAgreementLine;
use App\Finance\Services\BillingBatchEligibilityService;
use App\Operations\Enums\JobStatus;
use App\Operations\Enums\JobType;
use App\Operations\Enums\WaybillStatus;
use App\Operations\Models\Job;
use App\Operations\Models\Waybill;
use Illuminate\Support\Str;

function truckingBillingBatch(Client $client): BillingBatch
{
    return BillingBatch::factory()->create([
        'tenant_id' => $client->tenant_id,
        'company_id' => $client->company_id,
        'client_id' => $client->getKey(),
        'status' => BillingBatchStatus::Draft,
    ]);
}

function phase4bWaybill(Client $client, array $overrides = []): Waybill
{
    $job = Job::factory()->create(['tenant_id' => $client->tenant_id, 'company_id' => $client->company_id, 'client_id' => $client->getKey(), 'status' => JobStatus::InProgress, 'job_type' => JobType::Trucking]);

    return Waybill::query()->create(['uuid' => (string) Str::uuid(), 'tenant_id' => $client->tenant_id, 'company_id' => $client->company_id, 'client_id' => $client->getKey(), 'job_id' => $job->getKey(), 'waybill_number' => 'WB-'.Str::random(8), 'waybill_date' => '2026-09-10', 'driver_name' => 'Test Driver', 'truck_number' => 'GT-001', 'pickup_point' => 'Tema', 'destination' => 'Takoradi', 'number_of_trips' => 3, ...$overrides]);
}

function phase4bRateLine(Client $client): RateAgreementLine
{
    $agreement = RateAgreement::factory()->create(['tenant_id' => $client->tenant_id, 'company_id' => $client->company_id, 'client_id' => $client->getKey(), 'status' => RateAgreementStatus::Active, 'effective_from' => '2026-09-01', 'effective_to' => '2026-09-30']);

    return RateAgreementLine::factory()->create(['rate_agreement_id' => $agreement->getKey(), 'billing_unit' => BillingUnit::Trip, 'pickup_point' => 'Tema', 'destination' => 'Takoradi', 'rate' => 2500, 'currency' => 'GHS', 'equipment_reference' => null, 'machine_number' => null]);
}

test('finance snapshots a verified trucking waybill and prepares the commercial handoff', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $client = Client::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey()]);
    $finance = $this->tenantUser($tenant, [], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);
    $line = phase4bRateLine($client);
    $waybill = phase4bWaybill($client, ['status' => WaybillStatus::Verified, 'amount_paid' => 0, 'amount_paid_to_driver' => 0, 'client_reference' => 'GPHA-REF']);
    $batch = truckingBillingBatch($client);
    app(AddWaybillsToBillingBatchAction::class)->execute($batch, [$waybill->getKey()], $finance);
    $snapshot = $batch->lines()->firstOrFail();
    expect($snapshot->source_type)->toBe(BillingSourceType::TruckingWaybill->value)->and($snapshot->source_id)->toBe($waybill->getKey())->and($snapshot->hours)->toBeNull()->and((float) $snapshot->quantity)->toBe(3.0)->and($snapshot->billing_unit)->toBe('trip')->and((float) $snapshot->resolved_rate)->toBe(2500.0)->and((float) $snapshot->line_amount)->toBe(7500.0)->and($snapshot->currency)->toBe('GHS')->and($snapshot->pickup_point)->toBe('Tema')->and($snapshot->destination)->toBe('Takoradi')->and($snapshot->rate_agreement_line_id)->toBe($line->getKey());
    expect($waybill->fresh()->status)->toBe(WaybillStatus::Verified);
    $line->update(['rate' => 2900]);
    expect((float) $snapshot->fresh()->resolved_rate)->toBe(2500.0)->and((float) $snapshot->fresh()->line_amount)->toBe(7500.0);

    app(PrepareBillingBatchAction::class)->execute($batch->fresh(), $finance);
    expect($waybill->fresh()->status)->toBe(WaybillStatus::BillingReady);
});

test('trucking eligibility is tenant, company, client, state, trip, source, and rate scoped', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $client = Client::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey()]);
    phase4bRateLine($client);
    $batch = truckingBillingBatch($client);
    $eligible = phase4bWaybill($client, ['status' => WaybillStatus::Verified]);
    $recorded = phase4bWaybill($client, ['status' => WaybillStatus::Recorded]);
    $zeroTrips = phase4bWaybill($client, ['status' => WaybillStatus::Verified, 'number_of_trips' => 0]);
    $missingRate = phase4bWaybill($client, ['status' => WaybillStatus::Verified, 'pickup_point' => 'Accra']);
    $heavyJob = Job::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'client_id' => $client->getKey(), 'status' => JobStatus::InProgress, 'job_type' => JobType::HeavyMachinery]);
    $heavyWaybill = Waybill::query()->create([
        'uuid' => (string) Str::uuid(), 'tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'client_id' => $client->getKey(), 'job_id' => $heavyJob->getKey(), 'waybill_number' => 'WB-'.Str::random(8), 'waybill_date' => '2026-09-10', 'driver_name' => 'Test Driver', 'truck_number' => 'GT-002', 'pickup_point' => 'Tema', 'destination' => 'Takoradi', 'number_of_trips' => 1, 'status' => WaybillStatus::Verified,
    ]);

    expect(app(BillingBatchEligibilityService::class)->eligibleWaybills($batch)->pluck('id')->all())
        ->toBe([$eligible->getKey()]);

    foreach ([$recorded, $zeroTrips, $missingRate, $heavyWaybill] as $ineligibleWaybill) {
        expect(fn () => app(AddWaybillsToBillingBatchAction::class)->execute($batch, [$ineligibleWaybill->getKey()], $this->tenantUser($tenant, [], [RoleName::FinanceManager->value])))
            ->toThrow(BusinessException::class);
    }
});

test('trucking waybill billing rejects duplicates and rolls back missing-rate selections', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $client = Client::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey()]);
    $finance = $this->tenantUser($tenant, [], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);
    phase4bRateLine($client);
    $valid = phase4bWaybill($client, ['status' => WaybillStatus::Verified]);
    $missing = phase4bWaybill($client, ['status' => WaybillStatus::Verified, 'pickup_point' => 'Accra']);
    $batch = truckingBillingBatch($client);
    expect(fn () => app(AddWaybillsToBillingBatchAction::class)->execute($batch, [$valid->getKey(), $missing->getKey()], $finance))->toThrow(BusinessException::class, 'No active per-trip rate');
    expect($batch->lines()->count())->toBe(0)->and($valid->fresh()->status)->toBe(WaybillStatus::Verified);
    app(AddWaybillsToBillingBatchAction::class)->execute($batch, [$valid->getKey()], $finance);
    expect(fn () => app(AddWaybillsToBillingBatchAction::class)->execute(truckingBillingBatch($client), [$valid->getKey()], $finance))->toThrow(BusinessException::class, 'only be included in one');
});
