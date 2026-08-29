<?php

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Resources\FleetAssets\FleetAssetResource;
use App\Core\Administration\Filament\Resources\FleetAssetTypes\FleetAssetTypeResource;
use App\Core\Shared\Exceptions\BusinessException;
use App\Fleet\Actions\CreateFleetAssetAction;
use App\Fleet\Actions\CreateFleetAssetTypeAction;
use App\Fleet\Actions\UpdateFleetAssetAction;
use App\Fleet\Enums\FleetAssetCategory;
use App\Fleet\Enums\FleetAssetOperationalStatus;
use App\Fleet\Models\FleetAssetType;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

function fleetContext($test, $user, $company): void
{
    $user->companies()->syncWithoutDetaching([$company->getKey()]);
    $test->actingAs($user);
    $test->withSession([
        'active_company_id' => $company->getKey(),
        'support_tenant_id' => $user->hasRole(RoleName::SuperAdministrator->value) ? $company->tenant_id : null,
    ]);
}

function fleetTypeData(array $overrides = []): array
{
    return [
        'name' => 'Reach Stacker',
        'category' => FleetAssetCategory::HeavyMachinery->value,
        'is_active' => true,
        ...$overrides,
    ];
}

function fleetAssetData(FleetAssetType $type, array $overrides = []): array
{
    return [
        'fleet_asset_type_id' => $type->getKey(),
        'asset_number' => 'RS-003',
        'registration_number' => null,
        'make' => 'Kalmar',
        'model' => 'DRF450-65S5',
        'serial_number' => 'KAL-003',
        'operational_status' => FleetAssetOperationalStatus::Available->value,
        'notes' => 'Primary terminal reach stacker.',
        ...$overrides,
    ];
}

test('data entry clerk can create controlled asset types and Fleet assets in the active company', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $clerk = $this->tenantUser($tenant, [], [RoleName::DataEntryClerk->value]);
    fleetContext($this, $clerk, $company);

    $type = app(CreateFleetAssetTypeAction::class)->execute(fleetTypeData(), $clerk);
    $asset = app(CreateFleetAssetAction::class)->execute(fleetAssetData($type), $clerk);

    expect($type->tenant_id)->toBe($tenant->getKey())
        ->and($type->company_id)->toBe($company->getKey())
        ->and($type->uuid)->not->toBeNull()
        ->and($asset->tenant_id)->toBe($tenant->getKey())
        ->and($asset->company_id)->toBe($company->getKey())
        ->and($asset->asset_number)->toBe('RS-003')
        ->and($asset->operational_status)->toBe(FleetAssetOperationalStatus::Available)
        ->and($asset->type->is($type))->toBeTrue();

    expect(Activity::query()->where('event', 'fleet_asset_type.created')->exists())->toBeTrue()
        ->and(Activity::query()->where('event', 'fleet_asset.created')->exists())->toBeTrue();
});

test('Fleet type names and asset identities are unique per company but may be reused by another company', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $clerk = $this->tenantUser($tenant, [], [RoleName::DataEntryClerk->value]);
    fleetContext($this, $clerk, $companyA);

    $typeA = app(CreateFleetAssetTypeAction::class)->execute(fleetTypeData(), $clerk);
    app(CreateFleetAssetAction::class)->execute(fleetAssetData($typeA, ['registration_number' => 'GT 1234-26']), $clerk);

    expect(fn () => app(CreateFleetAssetTypeAction::class)->execute(fleetTypeData(['name' => 'reach stacker']), $clerk))
        ->toThrow(BusinessException::class, 'already exists');
    expect(fn () => app(CreateFleetAssetAction::class)->execute(fleetAssetData($typeA), $clerk))
        ->toThrow(BusinessException::class, 'asset number already exists');
    expect(fn () => app(CreateFleetAssetAction::class)->execute(fleetAssetData($typeA, ['asset_number' => 'RS-004', 'registration_number' => 'gt 1234-26']), $clerk))
        ->toThrow(BusinessException::class, 'registration number already exists');

    fleetContext($this, $clerk, $companyB);
    $typeB = app(CreateFleetAssetTypeAction::class)->execute(fleetTypeData(), $clerk);
    $assetB = app(CreateFleetAssetAction::class)->execute(fleetAssetData($typeB, ['registration_number' => 'GT 1234-26']), $clerk);

    expect($typeB->company_id)->toBe($companyB->getKey())
        ->and($assetB->company_id)->toBe($companyB->getKey());
});

test('Fleet assets reject foreign or inactive asset types and invalid operational statuses', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $clerk = $this->tenantUser($tenant, [], [RoleName::DataEntryClerk->value]);
    fleetContext($this, $clerk, $companyA);

    $foreignType = FleetAssetType::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyB->getKey(),
    ]);
    $inactiveType = FleetAssetType::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyA->getKey(),
        'is_active' => false,
    ]);

    expect(fn () => app(CreateFleetAssetAction::class)->execute(fleetAssetData($foreignType), $clerk))
        ->toThrow(BusinessException::class, 'not available');
    expect(fn () => app(CreateFleetAssetAction::class)->execute(fleetAssetData($inactiveType), $clerk))
        ->toThrow(BusinessException::class, 'not available');
    expect(fn () => app(CreateFleetAssetTypeAction::class)->execute(fleetTypeData(['name' => 'Terminal Tractor', 'category' => 'unknown']), $clerk))
        ->toThrow(BusinessException::class, 'valid Fleet asset category');
});

test('Fleet asset status changes use the protected action path and are audited', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $clerk = $this->tenantUser($tenant, [], [RoleName::DataEntryClerk->value]);
    fleetContext($this, $clerk, $company);
    $type = app(CreateFleetAssetTypeAction::class)->execute(fleetTypeData(), $clerk);
    $asset = app(CreateFleetAssetAction::class)->execute(fleetAssetData($type), $clerk);

    $updated = app(UpdateFleetAssetAction::class)->execute($asset, fleetAssetData($type, [
        'operational_status' => FleetAssetOperationalStatus::OutOfService->value,
    ]), $clerk);

    expect($updated->operational_status)->toBe(FleetAssetOperationalStatus::OutOfService)
        ->and($updated->updated_by)->toBe($clerk->getKey());
    expect(Activity::query()->where('event', 'fleet_asset.status_changed')->where('subject_id', $asset->getKey())->exists())->toBeTrue();
});

test('Fleet policies and action layer deny Finance management while allowing approved operational visibility', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    $clerk = $this->tenantUser($tenant, [], [RoleName::DataEntryClerk->value]);
    $finance = $this->tenantUser($tenant, [], [RoleName::FinanceManager->value]);
    foreach ([$operations, $clerk, $finance] as $user) {
        $user->companies()->syncWithoutDetaching([$company->getKey()]);
    }
    fleetContext($this, $clerk, $company);
    $type = app(CreateFleetAssetTypeAction::class)->execute(fleetTypeData(), $clerk);
    $asset = app(CreateFleetAssetAction::class)->execute(fleetAssetData($type), $clerk);

    expect(Gate::forUser($operations)->allows('view', $asset))->toBeTrue()
        ->and(Gate::forUser($operations)->allows('update', $asset))->toBeTrue()
        ->and(Gate::forUser($clerk)->allows('view', $asset))->toBeTrue()
        ->and(Gate::forUser($clerk)->allows('update', $asset))->toBeTrue()
        ->and(Gate::forUser($finance)->allows('view', $asset))->toBeFalse()
        ->and(Gate::forUser($finance)->allows('update', $asset))->toBeFalse();

    fleetContext($this, $finance, $company);
    expect(fn () => app(UpdateFleetAssetAction::class)->execute($asset, fleetAssetData($type), $finance))
        ->toThrow(BusinessException::class, 'not allowed');
});

test('Fleet resources and navigation are visible only to authorized active-company users', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $clerk = $this->tenantUser($tenant, [], [RoleName::DataEntryClerk->value]);
    $finance = $this->tenantUser($tenant, [], [RoleName::FinanceManager->value]);

    fleetContext($this, $clerk, $company);
    $type = app(CreateFleetAssetTypeAction::class)->execute(fleetTypeData(), $clerk);
    $asset = app(CreateFleetAssetAction::class)->execute(fleetAssetData($type), $clerk);

    expect(FleetAssetResource::canViewAny())->toBeTrue()
        ->and(FleetAssetTypeResource::canViewAny())->toBeTrue()
        ->and(FleetAssetResource::canCreate())->toBeTrue()
        ->and(FleetAssetTypeResource::canCreate())->toBeTrue();
    $this->get('/admin')->assertOk()->assertSee('Fleet Assets')->assertSee(FleetAssetResource::getUrl('index'), escape: false);
    $this->get(FleetAssetResource::getUrl('index'))->assertOk();
    $this->get(FleetAssetTypeResource::getUrl('create'))->assertOk();
    $this->get(FleetAssetResource::getUrl('create'))->assertOk();
    $this->get(FleetAssetResource::getUrl('edit', ['record' => $asset]))->assertOk();

    fleetContext($this, $finance, $company);
    expect(FleetAssetResource::canViewAny())->toBeFalse()
        ->and(FleetAssetTypeResource::canViewAny())->toBeFalse()
        ->and(FleetAssetResource::canCreate())->toBeFalse()
        ->and(FleetAssetTypeResource::canCreate())->toBeFalse();
    $this->get('/admin')->assertDontSee('href="'.FleetAssetResource::getUrl('index').'"', false);
    $this->get(FleetAssetResource::getUrl('index'))->assertForbidden();
    $this->get(FleetAssetResource::getUrl('create'))->assertForbidden();
});

test('Fleet records remain inaccessible across company boundaries and super administrators need active context', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $clerk = $this->tenantUser($tenant, [], [RoleName::DataEntryClerk->value]);
    fleetContext($this, $clerk, $companyA);
    $type = app(CreateFleetAssetTypeAction::class)->execute(fleetTypeData(), $clerk);
    $asset = app(CreateFleetAssetAction::class)->execute(fleetAssetData($type), $clerk);

    fleetContext($this, $clerk, $companyB);
    expect(Gate::forUser($clerk)->allows('view', $asset))->toBeFalse()
        ->and(FleetAssetResource::getEloquentQuery()->whereKey($asset->getKey())->exists())->toBeFalse();

    $superAdmin = $this->tenantUser($tenant, [], [RoleName::SuperAdministrator->value]);
    $this->actingAs($superAdmin)->withSession(['active_company_id' => null]);
    expect(Gate::forUser($superAdmin)->allows('view', $asset))->toBeFalse();

    fleetContext($this, $superAdmin, $companyA);
    expect(Gate::forUser($superAdmin)->allows('view', $asset))->toBeTrue();
});

test('the approved role catalog and canonical seeding do not introduce a Fleet Manager role', function () {
    $this->seedAccessControl();

    expect(RoleName::values())->not->toContain('Fleet Manager')
        ->and(Role::query()->where('name', 'Fleet Manager')->exists())->toBeFalse();
});
