<?php

use App\Core\Shared\Exceptions\BusinessException;
use App\CRM\Models\Client;
use App\Finance\Enums\BillingUnit;
use App\Finance\Enums\RateAgreementStatus;
use App\Finance\Models\RateAgreement;
use App\Finance\Models\RateAgreementLine;
use App\Finance\Services\TruckingRateResolverService;
use App\Operations\Enums\JobStatus;
use App\Operations\Enums\JobType;
use App\Operations\Models\Job;
use App\Operations\Models\Waybill;
use Illuminate\Support\Str;

function truckingRateWaybill(Client $client, array $overrides = []): Waybill
{
    $job = Job::factory()->create(['tenant_id' => $client->tenant_id, 'company_id' => $client->company_id, 'client_id' => $client->getKey(), 'status' => JobStatus::InProgress, 'job_type' => JobType::Trucking]);

    return Waybill::query()->create(['uuid' => (string) Str::uuid(), 'tenant_id' => $client->tenant_id, 'company_id' => $client->company_id, 'client_id' => $client->getKey(), 'job_id' => $job->getKey(), 'waybill_number' => 'WB-'.Str::random(8), 'waybill_date' => '2026-09-10', 'driver_name' => 'Test Driver', 'truck_number' => 'GT-001', 'pickup_point' => '  TEMA  ', 'destination' => ' Takoradi ', 'number_of_trips' => 3, ...$overrides]);
}

function truckingRateLine(Client $client, array $overrides = []): RateAgreementLine
{
    $agreement = RateAgreement::factory()->create(['tenant_id' => $client->tenant_id, 'company_id' => $client->company_id, 'client_id' => $client->getKey(), 'status' => RateAgreementStatus::Active, 'effective_from' => '2026-09-01', 'effective_to' => '2026-09-30']);

    return RateAgreementLine::factory()->create(['rate_agreement_id' => $agreement->getKey(), 'billing_unit' => BillingUnit::Trip, 'pickup_point' => 'Tema', 'destination' => 'Takoradi', 'rate' => 2500, 'currency' => 'GHS', 'equipment_reference' => null, 'machine_number' => null, ...$overrides]);
}

test('trucking route rates resolve directionally with normalized route evidence', function () {
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $client = Client::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey()]);
    $line = truckingRateLine($client);
    $waybill = truckingRateWaybill($client);
    $resolved = app(TruckingRateResolverService::class)->resolveForWaybill($waybill);
    expect($resolved['rate_agreement_line_id'])->toBe($line->getKey())->and($resolved['billing_unit'])->toBe('trip')->and($resolved['rate'])->toBe(2500.0)->and($resolved['currency'])->toBe('GHS');
    expect(fn () => app(TruckingRateResolverService::class)->resolveForWaybill($waybill->replicate()->forceFill(['pickup_point' => 'Takoradi', 'destination' => 'Tema'])))->toThrow(BusinessException::class, 'No active per-trip rate');
});

test('trucking resolver rejects missing ambiguous hourly and invalid trip evidence', function () {
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $client = Client::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey()]);
    $waybill = truckingRateWaybill($client);
    truckingRateLine($client, ['billing_unit' => BillingUnit::Hourly, 'equipment_reference' => 'Tema']);
    expect(fn () => app(TruckingRateResolverService::class)->resolveForWaybill($waybill))->toThrow(BusinessException::class, 'No active per-trip rate');
    truckingRateLine($client);
    truckingRateLine($client);
    expect(fn () => app(TruckingRateResolverService::class)->resolveForWaybill($waybill))->toThrow(BusinessException::class, 'Multiple active per-trip rates');
    expect(fn () => app(TruckingRateResolverService::class)->resolveForWaybill($waybill->replicate()->forceFill(['number_of_trips' => 0])))->toThrow(BusinessException::class, 'at least one trip');
});
