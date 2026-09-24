<?php

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Resources\Personnel\PersonnelResource;
use App\Core\Shared\Exceptions\BusinessException;
use App\Fleet\Actions\CreateFleetAssetAction;
use App\Fleet\Actions\CreateFleetAssetTypeAction;
use App\Fleet\Actions\CreateJobAssetAssignmentAction;
use App\Fleet\Enums\FleetAssetCategory;
use App\Operations\Actions\JobCards\CreateJobCardAction;
use App\Operations\Actions\JobCards\UpdateJobCardAction;
use App\Operations\Actions\Jobs\UpdateJobAction;
use App\Operations\Actions\Waybills\CreateWaybillAction;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Enums\JobStatus;
use App\Operations\Enums\JobType;
use App\Operations\Models\Job;
use App\Operations\Models\Personnel;
use App\Operations\Support\OperatorAssignmentService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

function personnelRecord($tenant, $company, array $attributes = []): Personnel
{
    return Personnel::query()->create([
        'uuid' => (string) Str::uuid(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'first_name' => 'Personnel',
        'last_name' => Str::random(8),
        'status' => 'active',
        'can_operate_equipment' => false,
        'can_drive' => false,
        ...$attributes,
    ]);
}

function personnelContext($test, $user, $company): void
{
    $user->companies()->syncWithoutDetaching([$company->getKey()]);
    $test->actingAs($user)->withSession(['active_company_id' => $company->getKey()]);
}

test('Personnel options are company-scoped, capability-scoped, and exclude inactive records', function () {
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $foreignCompany = $this->company($tenant);
    $foreignTenant = $this->tenant();
    $foreignTenantCompany = $this->company($foreignTenant);
    $operator = personnelRecord($tenant, $company, ['first_name' => 'Mohammed', 'can_operate_equipment' => true]);
    $driver = personnelRecord($tenant, $company, ['first_name' => 'Driver', 'can_drive' => true]);
    $dual = personnelRecord($tenant, $company, ['first_name' => 'Dual', 'can_operate_equipment' => true, 'can_drive' => true]);
    $inactive = personnelRecord($tenant, $company, ['first_name' => 'Inactive', 'can_operate_equipment' => true, 'status' => 'inactive']);
    $foreignCompanyOperator = personnelRecord($tenant, $foreignCompany, ['can_operate_equipment' => true]);
    $foreignTenantOperator = personnelRecord($foreignTenant, $foreignTenantCompany, ['can_operate_equipment' => true]);
    $service = app(OperatorAssignmentService::class);

    expect($service->companyOperatorOptions($tenant->id, $company->id))
        ->toHaveKeys([$operator->id, $dual->id])
        ->not->toHaveKeys([$driver->id, $inactive->id, $foreignCompanyOperator->id, $foreignTenantOperator->id]);
    expect($service->companyDriverOptions($tenant->id, $company->id))
        ->toHaveKeys([$driver->id, $dual->id])
        ->not->toHaveKeys([$operator->id, $inactive->id]);
});

test('Personnel policy preserves operational roles and denies Finance without active company context bypass', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $personnel = personnelRecord($tenant, $company, ['can_operate_equipment' => true]);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    $administrator = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $clerk = $this->tenantUser($tenant, [], [RoleName::DataEntryClerk->value]);
    $finance = $this->tenantUser($tenant, [], [RoleName::FinanceManager->value]);

    personnelContext($this, $operations, $company);
    expect(Gate::forUser($operations)->allows('update', $personnel))->toBeTrue();
    personnelContext($this, $administrator, $company);
    expect(Gate::forUser($administrator)->allows('update', $personnel))->toBeTrue();
    personnelContext($this, $clerk, $company);
    expect(Gate::forUser($clerk)->allows('update', $personnel))->toBeTrue();
    personnelContext($this, $finance, $company);
    expect(Gate::forUser($finance)->allows('view', $personnel))->toBeFalse();
});

test('Personnel sidebar entry is available only for authorized active-company users', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    $finance = $this->tenantUser($tenant, [], [RoleName::FinanceManager->value]);
    personnelContext($this, $operations, $company);
    expect(PersonnelResource::canViewAny())->toBeTrue();
    expect(view('filament.hooks.sidebar-navigation')->render())
        ->toContain('Personnel')
        ->toContain('/admin/personnel');
    personnelContext($this, $finance, $company);
    expect(PersonnelResource::canViewAny())->toBeFalse();
    expect(view('filament.hooks.sidebar-navigation')->render())->not->toContain('>Personnel<');
});

test('Fleet assignment persists Personnel rather than a linked Atlas User and rejects invalid operator identities', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $foreignCompany = $this->company($tenant);
    $actor = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    $linkedUser = $this->tenantUser($tenant);
    personnelContext($this, $actor, $company);
    $operator = personnelRecord($tenant, $company, ['can_operate_equipment' => true, 'user_id' => $linkedUser->id]);
    $driver = personnelRecord($tenant, $company, ['can_drive' => true]);
    $inactive = personnelRecord($tenant, $company, ['can_operate_equipment' => true, 'status' => 'inactive']);
    $foreign = personnelRecord($tenant, $foreignCompany, ['can_operate_equipment' => true]);
    $type = app(CreateFleetAssetTypeAction::class)->execute(['name' => 'Reach Stacker', 'category' => FleetAssetCategory::HeavyMachinery->value, 'is_active' => true], $actor);
    $asset = app(CreateFleetAssetAction::class)->execute(['fleet_asset_type_id' => $type->id, 'asset_number' => 'PERSONNEL-RS', 'operational_status' => 'available'], $actor);
    $job = Job::factory()->create(['tenant_id' => $tenant->id, 'company_id' => $company->id, 'planned_start_date' => '2026-10-01', 'planned_end_date' => '2026-10-01']);
    $assignment = app(CreateJobAssetAssignmentAction::class)->execute($job, ['fleet_asset_id' => $asset->id, 'personnel_id' => $operator->id], $actor);

    expect($assignment->personnel_id)->toBe($operator->id)->and($assignment->operator_user_id)->toBeNull();
    foreach ([$driver, $inactive, $foreign] as $invalid) {
        expect(fn () => app(OperatorAssignmentService::class)->resolveAssignment($invalid->id, null, $tenant->id, $company->id))->toThrow(BusinessException::class);
    }
    expect(fn () => app(OperatorAssignmentService::class)->resolveAssignment($operator->id, 'External person', $tenant->id, $company->id))->toThrow(BusinessException::class);
});

test('Job and Waybill actions persist Personnel references and reject wrong capability', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $actor = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    personnelContext($this, $actor, $company);
    $operator = personnelRecord($tenant, $company, ['can_operate_equipment' => true]);
    $driver = personnelRecord($tenant, $company, ['can_drive' => true]);
    $job = Job::factory()->create(['tenant_id' => $tenant->id, 'company_id' => $company->id, 'status' => JobStatus::Draft, 'job_type' => JobType::HeavyMachinery]);
    $updated = app(UpdateJobAction::class)->execute($job, ['assigned_personnel_id' => $operator->id], $actor);
    expect($updated->assigned_personnel_id)->toBe($operator->id)->and($updated->assigned_operator_id)->toBeNull();

    $trucking = Job::factory()->create(['tenant_id' => $tenant->id, 'company_id' => $company->id, 'status' => JobStatus::InProgress, 'job_type' => JobType::Trucking]);
    $waybill = app(CreateWaybillAction::class)->execute($trucking, ['waybill_date' => '2026-10-02', 'driver_personnel_id' => $driver->id, 'truck_number' => 'GT-PERSONNEL', 'number_of_trips' => 1], $actor);
    expect($waybill->driver_personnel_id)->toBe($driver->id)->and($waybill->driver_name)->toBe($driver->full_name);
    expect(fn () => app(OperatorAssignmentService::class)->resolveDriverAssignment($operator->id, null, $tenant->id, $company->id))->toThrow(BusinessException::class);
});

test('Personnel display precedence preserves legacy evidence without backfill', function () {
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $person = personnelRecord($tenant, $company, ['first_name' => 'Current', 'last_name' => 'Operator', 'can_operate_equipment' => true]);
    $job = Job::factory()->create(['tenant_id' => $tenant->id, 'company_id' => $company->id, 'assigned_personnel_id' => $person->id, 'assigned_operator_name' => 'Legacy external']);
    expect($job->fresh()->plannedOperatorName())->toBe('Current Operator');
    $legacy = Job::factory()->create(['tenant_id' => $tenant->id, 'company_id' => $company->id, 'assigned_personnel_id' => null, 'assigned_operator_name' => 'Legacy external']);
    expect($legacy->fresh()->plannedOperatorName())->toBe('Legacy external');
});

test('Job Card Personnel operators persist without legacy User references and locked cards remain immutable', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    personnelContext($this, $actor, $company);
    $one = personnelRecord($tenant, $company, ['first_name' => 'Operator', 'last_name' => 'One', 'can_operate_equipment' => true]);
    $two = personnelRecord($tenant, $company, ['first_name' => 'Operator', 'last_name' => 'Two', 'can_operate_equipment' => true]);
    $driver = personnelRecord($tenant, $company, ['can_drive' => true]);
    $job = Job::factory()->create(['tenant_id' => $tenant->id, 'company_id' => $company->id, 'status' => JobStatus::InProgress, 'job_type' => JobType::HeavyMachinery]);
    $card = app(CreateJobCardAction::class)->execute($job, ['card_date' => '2026-10-02', 'shift' => 'day', 'operator_personnel_id' => $one->id, 'operators' => [['personnel_id' => $one->id], ['personnel_id' => $two->id]]], $actor);

    expect($card->operator_personnel_id)->toBe($one->id)->and($card->operator_id)->toBeNull()
        ->and($card->operators()->pluck('personnel_id')->all())->toBe([$one->id, $two->id]);
    expect(fn () => app(OperatorAssignmentService::class)->resolveAssignment($driver->id, null, $tenant->id, $company->id))->toThrow(BusinessException::class);
    $card->update(['approval_status' => JobCardApprovalStatus::PendingVerification]);
    expect(fn () => app(UpdateJobCardAction::class)->execute($card, ['operator_personnel_id' => $two->id], $actor))->toThrow(BusinessException::class, 'read-only');
});
