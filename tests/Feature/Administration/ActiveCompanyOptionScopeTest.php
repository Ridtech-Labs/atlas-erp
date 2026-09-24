<?php

use App\Administration\Support\ActiveCompanyOptionScope;
use App\Core\Tenancy\Models\Tenant;
use App\CRM\Models\Client;
use App\Finance\Models\BillingBatch;
use App\Finance\Models\RateAgreement;
use App\Fleet\Models\FleetAssetType;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Models\Waybill;
use Illuminate\Support\Str;

test('active-company option scope excludes foreign company and tenant contextual values', function () {
    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $foreignCompany = $this->company($tenant, ['name' => 'Other company']);
    $foreignTenant = Tenant::factory()->create();
    $foreignTenantCompany = $this->company($foreignTenant, ['name' => 'Other tenant company']);
    $actor = $this->tenantUser($tenant);
    $actor->companies()->syncWithoutDetaching([$company->getKey()]);
    $this->actingAs($actor)->withSession(['active_company_id' => $company->getKey()]);

    $currentClient = Client::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'legal_name' => 'Kadmay Client']);
    $foreignCompanyClient = Client::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $foreignCompany->getKey(), 'legal_name' => 'Other Company Client']);
    $foreignTenantClient = Client::factory()->create(['tenant_id' => $foreignTenant->getKey(), 'company_id' => $foreignTenantCompany->getKey(), 'legal_name' => 'Other Tenant Client']);

    $scope = app(ActiveCompanyOptionScope::class);

    expect($scope->apply(Client::query())->pluck('id')->all())
        ->toContain($currentClient->getKey())
        ->not->toContain($foreignCompanyClient->getKey(), $foreignTenantClient->getKey());
});

test('active-company option scope protects operational Fleet and Finance lookup queries', function () {
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $foreignCompany = $this->company($tenant);
    $actor = $this->tenantUser($tenant);
    $actor->companies()->syncWithoutDetaching([$company->getKey()]);
    $this->actingAs($actor)->withSession(['active_company_id' => $company->getKey()]);

    $models = [Job::class, FleetAssetType::class, RateAgreement::class, BillingBatch::class];
    $scope = app(ActiveCompanyOptionScope::class);

    foreach ($models as $model) {
        $current = $model::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey()]);
        $foreign = $model::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $foreignCompany->getKey()]);

        expect($scope->apply($model::query())->pluck('id')->all())
            ->toContain($current->getKey())
            ->not->toContain($foreign->getKey());
    }

    $currentJob = Job::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey()]);
    $foreignJob = Job::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $foreignCompany->getKey()]);
    $currentWaybill = Waybill::query()->create(['uuid' => (string) Str::uuid(), 'tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'job_id' => $currentJob->getKey(), 'client_id' => $currentJob->client_id, 'waybill_date' => now(), 'driver_name' => 'Kadmay Driver', 'truck_number' => 'KAD-001', 'number_of_trips' => 1]);
    $foreignWaybill = Waybill::query()->create(['uuid' => (string) Str::uuid(), 'tenant_id' => $tenant->getKey(), 'company_id' => $foreignCompany->getKey(), 'job_id' => $foreignJob->getKey(), 'client_id' => $foreignJob->client_id, 'waybill_date' => now(), 'driver_name' => 'Foreign Driver', 'truck_number' => 'FOR-001', 'number_of_trips' => 1]);
    $currentCard = JobCard::query()->create(['uuid' => (string) Str::uuid(), 'tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'job_id' => $currentJob->getKey(), 'client_id' => $currentJob->client_id, 'card_date' => now(), 'shift' => 'day']);
    $foreignCard = JobCard::query()->create(['uuid' => (string) Str::uuid(), 'tenant_id' => $tenant->getKey(), 'company_id' => $foreignCompany->getKey(), 'job_id' => $foreignJob->getKey(), 'client_id' => $foreignJob->client_id, 'card_date' => now(), 'shift' => 'day']);

    expect($scope->apply(Waybill::query())->pluck('id')->all())
        ->toContain($currentWaybill->getKey())
        ->not->toContain($foreignWaybill->getKey());
    expect($scope->apply(JobCard::query())->pluck('id')->all())
        ->toContain($currentCard->getKey())
        ->not->toContain($foreignCard->getKey());
});
