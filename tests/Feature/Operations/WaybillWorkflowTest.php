<?php

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Widgets\JobWorkspaceWidget;
use App\Core\Shared\Exceptions\BusinessException;
use App\CRM\Models\Client;
use App\Operations\Actions\Waybills\CreateWaybillAction;
use App\Operations\Actions\Waybills\MarkWaybillBillingReadyAction;
use App\Operations\Actions\Waybills\ReturnWaybillForCorrectionAction;
use App\Operations\Actions\Waybills\SubmitWaybillForVerificationAction;
use App\Operations\Actions\Waybills\UpdateWaybillAction;
use App\Operations\Actions\Waybills\VerifyWaybillAction;
use App\Operations\Enums\JobStatus;
use App\Operations\Enums\JobType;
use App\Operations\Enums\WaybillStatus;
use App\Operations\Models\Job;
use App\Operations\Models\Waybill;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;

test('trucking job creates waybill successfully and assigns trusted context automatically', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'client_id' => $client->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::Trucking,
    ]);

    Storage::disk('local')->put('waybill-uploads/waybill-proof.txt', 'proof');

    $waybill = app(CreateWaybillAction::class)->execute($job, [
        'tenant_id' => 999,
        'company_id' => 999,
        'job_id' => 999,
        'client_id' => 999,
        'waybill_date' => '2026-08-14',
        'driver_name' => 'Yaw Mensah',
        'truck_number' => 'GT-4421-26',
        'number_of_trips' => 4,
        'pickup_point' => 'Tema Port',
        'destination' => 'Kadmay Yard',
        'amount_paid' => 4200,
        'amount_paid_to_driver' => 1800,
        'signature_name' => 'Driver Signed',
    ], $actor, ['waybill-uploads/waybill-proof.txt']);

    expect($waybill->tenant_id)->toBe($tenant->getKey())
        ->and($waybill->company_id)->toBe($company->getKey())
        ->and($waybill->job_id)->toBe($job->getKey())
        ->and($waybill->client_id)->toBe($client->getKey())
        ->and($waybill->created_by)->toBe($actor->getKey())
        ->and($waybill->updated_by)->toBe($actor->getKey())
        ->and($waybill->uuid)->not->toBe('')
        ->and($waybill->waybill_number)->toStartWith('WB-')
        ->and($waybill->status)->toBe(WaybillStatus::Recorded)
        ->and($waybill->getMedia('waybill-documents'))->toHaveCount(1);
});

test('cross company waybill creation is rejected inside the same tenant', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$companyA->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyB->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::Trucking,
    ]);

    expect(fn () => app(CreateWaybillAction::class)->execute($job, [
        'waybill_date' => '2026-08-14',
        'driver_name' => 'Yaw Mensah',
        'truck_number' => 'GT-4421-26',
        'number_of_trips' => 2,
    ], $actor))->toThrow(BusinessException::class);
});

test('cross tenant and platform sessions cannot create trucking waybills', function () {
    $this->seedAccessControl();

    $tenantA = $this->tenant(['name' => 'Tenant A']);
    $tenantB = $this->tenant(['name' => 'Tenant B']);
    $companyA = $this->company($tenantA, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenantB, ['name' => 'Other Logistics']);

    $crossTenantActor = $this->tenantUser($tenantA, [], [RoleName::CompanyAdministrator->value]);
    $crossTenantActor->companies()->sync([$companyA->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenantB->getKey(),
        'company_id' => $companyB->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::Trucking,
    ]);

    expect(fn () => app(CreateWaybillAction::class)->execute($job, [
        'waybill_date' => '2026-08-14',
        'driver_name' => 'Yaw Mensah',
        'truck_number' => 'GT-4421-26',
        'number_of_trips' => 2,
    ], $crossTenantActor))->toThrow(BusinessException::class);

    $platformActor = $this->tenantUser($tenantA, ['email' => 'platform@example.test'], [RoleName::SuperAdministrator->value]);
    $platformActor->companies()->sync([$companyA->getKey()]);
    session()->forget('active_company_id');

    expect(fn () => app(CreateWaybillAction::class)->execute(Job::factory()->create([
        'tenant_id' => $tenantA->getKey(),
        'company_id' => $companyA->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::Trucking,
    ]), [
        'waybill_date' => '2026-08-14',
        'driver_name' => 'Yaw Mensah',
        'truck_number' => 'GT-4421-26',
        'number_of_trips' => 2,
    ], $platformActor))->toThrow(BusinessException::class);
});

test('heavy machinery job can not create a waybill accidentally', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::HeavyMachinery,
    ]);

    expect(fn () => app(CreateWaybillAction::class)->execute($job, [
        'waybill_date' => '2026-08-14',
        'driver_name' => 'Yaw Mensah',
        'truck_number' => 'GT-4421-26',
        'number_of_trips' => 2,
    ], $actor))->toThrow(BusinessException::class, 'Waybills can only be recorded for trucking jobs.');
});

test('waybill verification succeeds with valid data and records audit actor', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    Storage::disk('local')->put('waybill-uploads/verified-proof.txt', 'proof');

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::Trucking,
    ]);

    $waybill = app(CreateWaybillAction::class)->execute($job, [
        'waybill_date' => '2026-08-14',
        'driver_name' => 'Yaw Mensah',
        'truck_number' => 'GT-4421-26',
        'number_of_trips' => 4,
        'pickup_point' => 'Tema Port',
        'destination' => 'Kadmay Yard',
        'amount_paid' => 4200,
        'amount_paid_to_driver' => 1800,
        'signature_name' => 'Driver Signed',
    ], $actor, ['waybill-uploads/verified-proof.txt']);

    $submitted = app(SubmitWaybillForVerificationAction::class)->execute($waybill, $actor);
    $verified = app(VerifyWaybillAction::class)->execute($submitted, $actor);

    $activity = Activity::query()
        ->where('event', 'waybill.verified')
        ->where('subject_type', 'waybill')
        ->where('subject_id', $verified->getKey())
        ->latest('id')
        ->first();

    expect($verified->status)->toBe(WaybillStatus::Verified)
        ->and($verified->verified_by)->toBe($actor->getKey())
        ->and($verified->verified_at)->not->toBeNull()
        ->and($activity)->not->toBeNull()
        ->and($activity?->causer_id)->toBe($actor->getKey());
});

test('waybill verification rejects invalid state and incomplete data', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::Trucking,
    ]);

    $waybill = app(CreateWaybillAction::class)->execute($job, [
        'waybill_date' => '2026-08-14',
        'driver_name' => 'Yaw Mensah',
        'truck_number' => 'GT-4421-26',
        'number_of_trips' => 2,
    ], $actor);

    expect(fn () => app(VerifyWaybillAction::class)->execute($waybill, $actor))
        ->toThrow(BusinessException::class, 'Only Waybills pending verification can be verified.');

    $submitted = app(SubmitWaybillForVerificationAction::class)->execute($waybill, $actor);

    expect(fn () => app(VerifyWaybillAction::class)->execute($submitted, $actor))
        ->toThrow(BusinessException::class);
});

test('waybill billing ready requires verified state and repeated transitions are rejected safely', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    Storage::disk('local')->put('waybill-uploads/billing-proof.txt', 'proof');

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::Trucking,
    ]);

    $waybill = app(CreateWaybillAction::class)->execute($job, [
        'waybill_date' => '2026-08-14',
        'driver_name' => 'Yaw Mensah',
        'truck_number' => 'GT-4421-26',
        'number_of_trips' => 4,
        'pickup_point' => 'Tema Port',
        'destination' => 'Kadmay Yard',
        'amount_paid' => 4200,
        'amount_paid_to_driver' => 1800,
        'signature_name' => 'Driver Signed',
    ], $actor, ['waybill-uploads/billing-proof.txt']);

    expect(fn () => app(MarkWaybillBillingReadyAction::class)->execute($waybill, $actor))
        ->toThrow(BusinessException::class, 'Only verified Waybills can move to billing ready.');

    $verified = app(VerifyWaybillAction::class)->execute(
        app(SubmitWaybillForVerificationAction::class)->execute($waybill, $actor),
        $actor,
    );

    $billingReady = app(MarkWaybillBillingReadyAction::class)->execute($verified, $actor);

    expect($billingReady->status)->toBe(WaybillStatus::BillingReady);

    expect(fn () => app(MarkWaybillBillingReadyAction::class)->execute($billingReady, $actor))
        ->toThrow(BusinessException::class, 'Only verified Waybills can move to billing ready.');

    expect(fn () => app(SubmitWaybillForVerificationAction::class)->execute($billingReady, $actor))
        ->toThrow(BusinessException::class);
});

test('returned waybill can be corrected and resubmitted but pending verification waybills are not directly editable', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    Storage::disk('local')->put('waybill-uploads/return-proof.txt', 'proof');

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::Trucking,
    ]);

    $waybill = app(CreateWaybillAction::class)->execute($job, [
        'waybill_date' => '2026-08-14',
        'driver_name' => 'Yaw Mensah',
        'truck_number' => 'GT-4421-26',
        'number_of_trips' => 4,
        'pickup_point' => 'Tema Port',
        'destination' => 'Kadmay Yard',
        'amount_paid' => 4200,
        'amount_paid_to_driver' => 1800,
        'signature_name' => 'Driver Signed',
    ], $actor, ['waybill-uploads/return-proof.txt']);

    $submitted = app(SubmitWaybillForVerificationAction::class)->execute($waybill, $actor);

    expect(fn () => app(UpdateWaybillAction::class)->execute($submitted, ['driver_name' => 'Changed'], $actor))
        ->toThrow(BusinessException::class, 'This Waybill must be returned for correction before it can be edited.');

    $returned = app(ReturnWaybillForCorrectionAction::class)->execute($submitted, $actor, 'Add the correct destination.');

    $updated = app(UpdateWaybillAction::class)->execute($returned, [
        'destination' => 'Kadmay Main Yard',
        'amount_paid' => 4500,
    ], $actor);

    expect($updated->status)->toBe(WaybillStatus::Returned)
        ->and($updated->destination)->toBe('Kadmay Main Yard')
        ->and((float) $updated->amount_paid)->toBe(4500.0);

    $resubmitted = app(SubmitWaybillForVerificationAction::class)->execute($updated, $actor);

    expect($resubmitted->status)->toBe(WaybillStatus::PendingVerification);
});

test('job workspace reflects trucking waybill statuses correctly', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::Trucking,
    ]);

    $waybill = Waybill::withoutEvents(fn () => Waybill::query()->create([
        'uuid' => (string) Str::uuid(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'job_id' => $job->getKey(),
        'client_id' => $job->client_id,
        'waybill_number' => 'WB-00001',
        'waybill_date' => now()->toDateString(),
        'driver_name' => 'Kwame Boateng',
        'truck_number' => 'AS-2214-26',
        'number_of_trips' => 3,
        'pickup_point' => 'Tema Port',
        'destination' => 'Kadmay Yard',
        'signature_name' => 'Signed',
        'status' => WaybillStatus::Verified,
    ]));

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('Waybills')
        ->assertSee('Prepare Waybill Billing')
        ->assertSee('Billing basis');

    $billingReady = app(MarkWaybillBillingReadyAction::class)->execute($waybill, $actor);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job->fresh()])
        ->assertSee('View Billing Basis')
        ->assertSee($billingReady->waybill_number);
});
