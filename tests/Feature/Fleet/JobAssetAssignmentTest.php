<?php

use App\Administration\Enums\RoleName;
use App\Core\Shared\Exceptions\BusinessException;
use App\Fleet\Actions\CancelJobAssetAssignmentAction;
use App\Fleet\Actions\CreateFleetAssetAction;
use App\Fleet\Actions\CreateFleetAssetTypeAction;
use App\Fleet\Actions\CreateJobAssetAssignmentAction;
use App\Fleet\Actions\ReleaseJobAssetAssignmentAction;
use App\Fleet\Actions\UpdateJobAssetAssignmentAction;
use App\Fleet\Enums\FleetAssetCategory;
use App\Fleet\Enums\JobAssetAssignmentStatus;
use App\Fleet\Models\FleetAsset;
use App\Fleet\Models\JobAssetAssignment;
use App\Fleet\Services\JobAssetAvailabilityService;
use App\Operations\Models\Job;
use Illuminate\Support\Facades\Gate;

function assignmentContext($test, $user, $company): void
{
    $user->companies()->syncWithoutDetaching([$company->getKey()]);
    $test->actingAs($user)->withSession(['active_company_id' => $company->getKey()]);
}

function assignmentJob($tenant, $company, array $attributes = []): Job
{
    return Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'planned_start_date' => '2026-09-01',
        'planned_end_date' => '2026-09-01',
        ...$attributes,
    ]);
}

function assignmentAsset($actor, string $number = 'RS-003'): FleetAsset
{
    $type = app(CreateFleetAssetTypeAction::class)->execute([
        'name' => 'Reach Stacker '.$number,
        'category' => FleetAssetCategory::HeavyMachinery->value,
        'is_active' => true,
    ], $actor);

    return app(CreateFleetAssetAction::class)->execute([
        'fleet_asset_type_id' => $type->getKey(),
        'asset_number' => $number,
        'operational_status' => 'available',
    ], $actor);
}

test('Data Entry Clerk remains the Fleet master-data role but has view-only Job Asset Assignments', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $clerk = $this->tenantUser($tenant, [], [RoleName::DataEntryClerk->value]);
    assignmentContext($this, $clerk, $company);
    $asset = assignmentAsset($clerk);
    $job = assignmentJob($tenant, $company);

    expect(Gate::forUser($clerk)->allows('viewAny', JobAssetAssignment::class))->toBeTrue()
        ->and(Gate::forUser($clerk)->allows('create', JobAssetAssignment::class))->toBeFalse()
        ->and($asset->asset_number)->toBe('RS-003')
        ->and(fn () => app(CreateJobAssetAssignmentAction::class)->execute($job, ['fleet_asset_id' => $asset->getKey()], $clerk))
        ->toThrow(BusinessException::class, 'not allowed');
});

test('Operations Manager can create update release and cancel assignments while Data Entry Clerk forged attempts are rejected', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $clerk = $this->tenantUser($tenant, [], [RoleName::DataEntryClerk->value]);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    assignmentContext($this, $clerk, $company);
    $asset = assignmentAsset($clerk);
    $job = assignmentJob($tenant, $company);
    assignmentContext($this, $operations, $company);
    $assignment = app(CreateJobAssetAssignmentAction::class)->execute($job, ['fleet_asset_id' => $asset->getKey()], $operations);
    $updated = app(UpdateJobAssetAssignmentAction::class)->execute($assignment, ['planned_start_at' => '2026-09-01 08:00', 'planned_end_at' => '2026-09-01 12:00'], $operations);

    expect($updated->status)->toBe(JobAssetAssignmentStatus::Assigned)
        ->and(Gate::forUser($operations)->allows('update', $updated))->toBeTrue();
    assignmentContext($this, $clerk, $company);
    expect(fn () => app(UpdateJobAssetAssignmentAction::class)->execute($updated, ['notes' => 'Forged change'], $clerk))->toThrow(BusinessException::class, 'not allowed')
        ->and(fn () => app(ReleaseJobAssetAssignmentAction::class)->execute($updated, $clerk))->toThrow(BusinessException::class, 'not allowed')
        ->and(fn () => app(CancelJobAssetAssignmentAction::class)->execute($updated, $clerk))->toThrow(BusinessException::class, 'not allowed');

    assignmentContext($this, $operations, $company);
    expect(app(ReleaseJobAssetAssignmentAction::class)->execute($updated, $operations)->status)->toBe(JobAssetAssignmentStatus::Released);
    $second = app(CreateJobAssetAssignmentAction::class)->execute($job, ['fleet_asset_id' => $asset->getKey()], $operations);
    expect(app(CancelJobAssetAssignmentAction::class)->execute($second, $operations)->status)->toBe(JobAssetAssignmentStatus::Cancelled);
});

test('Company Administrator retains assignment management while Finance and other companies remain denied', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $companyA = $this->company($tenant);
    $companyB = $this->company($tenant);
    $clerk = $this->tenantUser($tenant, [], [RoleName::DataEntryClerk->value]);
    $admin = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $finance = $this->tenantUser($tenant, [], [RoleName::FinanceManager->value]);
    assignmentContext($this, $clerk, $companyA);
    $asset = assignmentAsset($clerk);
    $job = assignmentJob($tenant, $companyA);
    assignmentContext($this, $admin, $companyA);
    expect(Gate::forUser($admin)->allows('create', JobAssetAssignment::class))->toBeTrue();
    $assignment = app(CreateJobAssetAssignmentAction::class)->execute($job, ['fleet_asset_id' => $asset->getKey()], $admin);

    assignmentContext($this, $finance, $companyA);
    expect(Gate::forUser($finance)->allows('viewAny', JobAssetAssignment::class))->toBeFalse()
        ->and(Gate::forUser($finance)->allows('update', $assignment))->toBeFalse();
    assignmentContext($this, $admin, $companyB);
    expect(Gate::forUser($admin)->allows('view', $assignment))->toBeFalse()
        ->and(fn () => app(UpdateJobAssetAssignmentAction::class)->execute($assignment, ['notes' => 'Cross-company'], $admin))->toThrow(BusinessException::class, 'not allowed');
});

test('availability excludes overlapping active reservations and unavailable or foreign assets but restores released and cancelled assets', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $companyA = $this->company($tenant);
    $companyB = $this->company($tenant);
    $clerk = $this->tenantUser($tenant, [], [RoleName::DataEntryClerk->value]);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    assignmentContext($this, $clerk, $companyA);
    $reservedAsset = assignmentAsset($clerk, 'RS-RESERVED');
    $freeAsset = assignmentAsset($clerk, 'RS-FREE');
    $outOfServiceAsset = assignmentAsset($clerk, 'RS-OOS');
    $outOfServiceAsset->update(['operational_status' => 'out_of_service']);
    $inactiveTypeAsset = assignmentAsset($clerk, 'RS-INACTIVE');
    $inactiveTypeAsset->type->update(['is_active' => false]);
    assignmentContext($this, $clerk, $companyB);
    $foreignAsset = assignmentAsset($clerk, 'RS-FOREIGN');
    assignmentContext($this, $operations, $companyA);
    $jobOne = assignmentJob($tenant, $companyA);
    $jobTwo = assignmentJob($tenant, $companyA);
    $assignment = app(CreateJobAssetAssignmentAction::class)->execute($jobOne, [
        'fleet_asset_id' => $reservedAsset->getKey(),
        'planned_start_at' => '2026-09-01 08:00',
        'planned_end_at' => '2026-09-01 12:00',
    ], $operations);
    $availability = app(JobAssetAvailabilityService::class);
    $overlap = $availability->availableFor($jobTwo, ['planned_start_at' => '2026-09-01 09:00', 'planned_end_at' => '2026-09-01 11:00'])->modelKeys();
    $adjacent = $availability->availableFor($jobTwo, ['planned_start_at' => '2026-09-01 12:00', 'planned_end_at' => '2026-09-01 14:00'])->modelKeys();

    expect($overlap)->toContain($freeAsset->getKey())
        ->not->toContain($reservedAsset->getKey())
        ->not->toContain($outOfServiceAsset->getKey())
        ->not->toContain($inactiveTypeAsset->getKey())
        ->not->toContain($foreignAsset->getKey())
        ->and($adjacent)->toContain($reservedAsset->getKey())
        ->and(fn () => app(CreateJobAssetAssignmentAction::class)->execute($jobTwo, [
            'fleet_asset_id' => $reservedAsset->getKey(), 'planned_start_at' => '2026-09-01 09:00', 'planned_end_at' => '2026-09-01 11:00',
        ], $operations))->toThrow(BusinessException::class, 'already assigned');

    app(ReleaseJobAssetAssignmentAction::class)->execute($assignment, $operations);
    expect($availability->availableFor($jobTwo, ['planned_start_at' => '2026-09-01 09:00', 'planned_end_at' => '2026-09-01 11:00'])->modelKeys())->toContain($reservedAsset->getKey());
    $cancelled = app(CreateJobAssetAssignmentAction::class)->execute($jobOne, ['fleet_asset_id' => $reservedAsset->getKey(), 'planned_start_at' => '2026-09-01 08:00', 'planned_end_at' => '2026-09-01 12:00'], $operations);
    app(CancelJobAssetAssignmentAction::class)->execute($cancelled, $operations);
    expect($availability->availableFor($jobTwo, ['planned_start_at' => '2026-09-01 09:00', 'planned_end_at' => '2026-09-01 11:00'])->modelKeys())->toContain($reservedAsset->getKey());
});
