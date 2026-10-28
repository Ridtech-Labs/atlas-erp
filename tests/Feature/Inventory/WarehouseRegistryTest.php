<?php

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Resources\StorageLocations\StorageLocationResource;
use App\Core\Administration\Filament\Resources\Warehouses\WarehouseResource;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Tenancy\Support\CompanyContext;
use App\Inventory\Actions\CreateStorageLocationAction;
use App\Inventory\Actions\CreateWarehouseAction;
use App\Inventory\Actions\UpdateStorageLocationAction;
use App\Inventory\Models\StorageLocation;
use App\Inventory\Models\Warehouse;

function warehouseContext($test, $user, $company, bool $support = false): void
{
    $test->actingAs($user)->withSession([
        'active_company_id' => $company->getKey(),
        'support_tenant_id' => $support ? $company->tenant_id : null,
    ]);
    app(CompanyContext::class)->set(null);
}

/** @return array<string, mixed> */
function warehouseData(array $overrides = []): array
{
    return ['code' => 'MAIN', 'name' => 'Main Warehouse', 'notes' => 'Tema operations store', 'is_active' => true, ...$overrides];
}

/** @return array<string, mixed> */
function storageLocationData(Warehouse $warehouse, array $overrides = []): array
{
    return ['warehouse_id' => $warehouse->getKey(), 'code' => 'BAY-A01', 'name' => 'Bay A01', 'is_active' => true, ...$overrides];
}

test('Warehouse creation creates one active default Storage Location and supports multiple Warehouses per company', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $administrator = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $administrator->companies()->sync([$company->getKey()]);
    warehouseContext($this, $administrator, $company);

    $first = app(CreateWarehouseAction::class)->execute(warehouseData(), $administrator);
    $second = app(CreateWarehouseAction::class)->execute(warehouseData(['code' => 'YARD', 'name' => 'Yard Store']), $administrator);

    expect($first->tenant_id)->toBe($tenant->getKey())
        ->and($first->company_id)->toBe($company->getKey())
        ->and($first->storageLocations)->toHaveCount(1)
        ->and($first->storageLocations->first()?->code)->toBe('DEFAULT')
        ->and($first->storageLocations->first()?->is_default)->toBeTrue()
        ->and($first->storageLocations->first()?->is_active)->toBeTrue()
        ->and(Warehouse::query()->where('company_id', $company->getKey())->count())->toBe(2)
        ->and($second->storageLocations()->count())->toBe(1);
});

test('Warehouse and Storage Location codes are constrained within their approved scopes', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    $operations->companies()->sync([$company->getKey()]);
    warehouseContext($this, $operations, $company);

    $warehouse = app(CreateWarehouseAction::class)->execute(warehouseData(), $operations);
    expect(fn () => app(CreateWarehouseAction::class)->execute(warehouseData(['name' => 'Duplicate']), $operations))->toThrow(BusinessException::class, 'already exists');

    $location = app(CreateStorageLocationAction::class)->execute(storageLocationData($warehouse), $operations);
    expect(fn () => app(CreateStorageLocationAction::class)->execute(storageLocationData($warehouse, ['name' => 'Duplicate bay']), $operations))->toThrow(BusinessException::class, 'already exists');

    $other = app(CreateWarehouseAction::class)->execute(warehouseData(['code' => 'YARD', 'name' => 'Yard']), $operations);
    $sameCodeElsewhere = app(CreateStorageLocationAction::class)->execute(storageLocationData($other), $operations);

    expect($location->code)->toBe($sameCodeElsewhere->code)
        ->and(StorageLocation::query()->where('code', 'BAY-A01')->count())->toBe(2);
});

test('Storage Locations cannot cross company or tenant boundaries and default locations remain active', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $otherTenant = $this->tenant();
    $company = $this->company($tenant);
    $otherCompany = $this->company($tenant);
    $foreignCompany = $this->company($otherTenant);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    $operations->companies()->sync([$company->getKey(), $otherCompany->getKey()]);
    warehouseContext($this, $operations, $company);

    $warehouse = app(CreateWarehouseAction::class)->execute(warehouseData(), $operations);
    $foreignWarehouse = Warehouse::query()->create(['tenant_id' => $otherTenant->getKey(), 'company_id' => $foreignCompany->getKey(), 'code' => 'FOREIGN', 'name' => 'Foreign', 'is_active' => true]);

    expect(fn () => app(CreateStorageLocationAction::class)->execute(storageLocationData($foreignWarehouse), $operations))->toThrow(BusinessException::class, 'active company');

    $default = $warehouse->storageLocations()->firstOrFail();
    expect(fn () => app(UpdateStorageLocationAction::class)->execute($default, storageLocationData($warehouse, ['code' => 'DEFAULT', 'name' => 'Default storage location', 'is_active' => false]), $operations))->toThrow(BusinessException::class, 'must remain active');

    warehouseContext($this, $operations, $otherCompany);
    expect(fn () => app(CreateStorageLocationAction::class)->execute(storageLocationData($warehouse), $operations))->toThrow(BusinessException::class, 'active company');
});

test('Warehouse registry permissions keep Warehouse Managers and clerks read only while Finance is denied', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $administrator = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $administrator->companies()->sync([$company->getKey()]);
    warehouseContext($this, $administrator, $company);
    $warehouse = app(CreateWarehouseAction::class)->execute(warehouseData(), $administrator);

    foreach ([RoleName::WarehouseManager, RoleName::DataEntryClerk] as $role) {
        $user = $this->tenantUser($tenant, [], [$role->value]);
        $user->companies()->sync([$company->getKey()]);
        warehouseContext($this, $user, $company);
        expect(WarehouseResource::canViewAny())->toBeTrue()
            ->and(WarehouseResource::canCreate())->toBeFalse()
            ->and(StorageLocationResource::canViewAny())->toBeTrue()
            ->and(StorageLocationResource::canCreate())->toBeFalse();
        $this->get(WarehouseResource::getUrl('view', ['record' => $warehouse]))->assertOk();
        $this->get('/admin')
            ->assertSee('Warehouses')
            ->assertSee('Storage Locations');
    }

    $finance = $this->tenantUser($tenant, [], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    warehouseContext($this, $finance, $company);
    expect(WarehouseResource::canViewAny())->toBeFalse()
        ->and(StorageLocationResource::canViewAny())->toBeFalse();
    $this->get(WarehouseResource::getUrl('index'))->assertForbidden();
    $this->get('/admin')
        ->assertDontSee('>Warehouses<', false)
        ->assertDontSee('>Storage Locations<', false);
});

test('a Super Administrator can manage Warehouses only in a validated support workspace', function () {
    $this->seedAccessControl();
    $homeTenant = $this->tenant();
    $supportTenant = $this->tenant();
    $supportCompany = $this->company($supportTenant);
    $super = $this->tenantUser($homeTenant, [], [RoleName::SuperAdministrator->value]);

    warehouseContext($this, $super, $supportCompany, true);
    $warehouse = app(CreateWarehouseAction::class)->execute(warehouseData(), $super);

    expect($warehouse->tenant_id)->toBe($supportTenant->getKey())
        ->and(WarehouseResource::canCreate())->toBeTrue();

    $this->actingAs($super);
    session()->forget(['support_tenant_id', 'active_company_id']);
    app(CompanyContext::class)->set(null);
    expect(WarehouseResource::canViewAny())->toBeFalse();
});
