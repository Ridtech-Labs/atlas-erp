<?php

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Resources\InventoryItems\InventoryItemResource;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Tenancy\Support\CompanyContext;
use App\Inventory\Actions\CreateInventoryItemAction;
use App\Inventory\Actions\UpdateInventoryItemAction;
use App\Inventory\Enums\InventoryItemStatus;
use App\Inventory\Enums\InventoryStockStatus;
use App\Inventory\Models\InventoryItem;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;

function inventoryContext($test, $user, $company): void
{
    $user->companies()->syncWithoutDetaching([$company->getKey()]);
    $test->actingAs($user);
    $test->withSession([
        'active_company_id' => $company->getKey(),
        'support_tenant_id' => $user->hasRole(RoleName::SuperAdministrator->value) ? $company->tenant_id : null,
    ]);

    // Mirror the next request, where ResolveTenant derives the context from session.
    app(CompanyContext::class)->set(null);
}

/** @return array<string, mixed> */
function inventoryItemData(array $overrides = []): array
{
    return [
        'code' => 'FLT-HYD-001',
        'name' => 'Hydraulic Filter',
        'category' => 'Filters',
        'unit_of_measure' => 'pcs',
        'description' => 'Hydraulic return-line filter.',
        'minimum_stock_level' => '5',
        'status' => InventoryItemStatus::Active->value,
        ...$overrides,
    ];
}

test('approved operational roles create company-scoped Inventory items at zero quantity', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);

    foreach ([RoleName::CompanyAdministrator, RoleName::OperationsManager, RoleName::DataEntryClerk] as $role) {
        $user = $this->tenantUser($tenant, [], [$role->value]);
        inventoryContext($this, $user, $company);

        $item = app(CreateInventoryItemAction::class)->execute(inventoryItemData([
            'code' => 'FLT-HYD-00'.($user->getKey()),
        ]), $user);

        expect($item->tenant_id)->toBe($tenant->getKey())
            ->and($item->company_id)->toBe($company->getKey())
            ->and($item->quantity_on_hand)->toBe('0.000')
            ->and($item->minimum_stock_level)->toBe('5.000');
    }

    expect(Activity::query()->where('event', 'inventory_item.created')->count())->toBe(3);
});

test('Inventory item code is normalized, unique within a company, and reusable in another company', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);

    inventoryContext($this, $operations, $companyA);
    $item = app(CreateInventoryItemAction::class)->execute(inventoryItemData(['code' => ' flt-hyd-001 ']), $operations);

    expect($item->code)->toBe('FLT-HYD-001');
    expect(fn () => app(CreateInventoryItemAction::class)->execute(inventoryItemData(), $operations))
        ->toThrow(BusinessException::class, 'already exists');

    inventoryContext($this, $operations, $companyB);
    $otherCompanyItem = app(CreateInventoryItemAction::class)->execute(inventoryItemData(), $operations);

    expect($otherCompanyItem->company_id)->toBe($companyB->getKey());
});

test('Inventory action layer rejects invalid unit of measure and negative minimum stock', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $clerk = $this->tenantUser($tenant, [], [RoleName::DataEntryClerk->value]);
    inventoryContext($this, $clerk, $company);

    expect(fn () => app(CreateInventoryItemAction::class)->execute(inventoryItemData(['unit_of_measure' => 'crate']), $clerk))
        ->toThrow(BusinessException::class, 'valid unit of measure');
    expect(fn () => app(CreateInventoryItemAction::class)->execute(inventoryItemData(['minimum_stock_level' => '-1']), $clerk))
        ->toThrow(BusinessException::class, 'valid non-negative minimum stock level');
});

test('Inventory updates preserve quantity on hand and audit normal master-data changes', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    inventoryContext($this, $operations, $company);
    $item = app(CreateInventoryItemAction::class)->execute(inventoryItemData(), $operations);

    $item->forceFill(['quantity_on_hand' => '8.500'])->saveQuietly();
    $updated = app(UpdateInventoryItemAction::class)->execute($item, inventoryItemData([
        'name' => 'Hydraulic Filter Assembly',
        'quantity_on_hand' => '999.000',
    ]), $operations);

    expect($updated->name)->toBe('Hydraulic Filter Assembly')
        ->and($updated->quantity_on_hand)->toBe('8.500')
        ->and($updated->updated_by)->toBe($operations->getKey());
    expect(Activity::query()->where('event', 'inventory_item.updated')->where('subject_id', $item->getKey())->exists())->toBeTrue();
});

test('Inventory derives out of stock, low stock, and in stock without persisting a stock status', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    inventoryContext($this, $operations, $company);

    $out = app(CreateInventoryItemAction::class)->execute(inventoryItemData(['code' => 'OUT-001']), $operations);
    $low = app(CreateInventoryItemAction::class)->execute(inventoryItemData(['code' => 'LOW-001']), $operations);
    $in = app(CreateInventoryItemAction::class)->execute(inventoryItemData(['code' => 'IN-001']), $operations);
    $low->forceFill(['quantity_on_hand' => '5.000'])->saveQuietly();
    $in->forceFill(['quantity_on_hand' => '5.001'])->saveQuietly();

    expect($out->fresh()->stockStatus())->toBe(InventoryStockStatus::OutOfStock)
        ->and($low->fresh()->stockStatus())->toBe(InventoryStockStatus::LowStock)
        ->and($in->fresh()->stockStatus())->toBe(InventoryStockStatus::InStock)
        ->and(InventoryItem::outOfStock()->count())->toBe(1)
        ->and(InventoryItem::lowStock()->count())->toBe(1)
        ->and(InventoryItem::inStock()->count())->toBe(1);
});

test('inactive Inventory items remain visible historical master data', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $administrator = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    inventoryContext($this, $administrator, $company);

    $item = app(CreateInventoryItemAction::class)->execute(inventoryItemData([
        'status' => InventoryItemStatus::Inactive->value,
    ]), $administrator);

    expect($item->status)->toBe(InventoryItemStatus::Inactive)
        ->and(Gate::forUser($administrator)->allows('view', $item))->toBeTrue();
});

test('Inventory navigation and direct routes are available only to authorized users in the active company', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $clerk = $this->tenantUser($tenant, [], [RoleName::DataEntryClerk->value]);
    $finance = $this->tenantUser($tenant, [], [RoleName::FinanceManager->value]);
    inventoryContext($this, $clerk, $companyA);
    $item = app(CreateInventoryItemAction::class)->execute(inventoryItemData(), $clerk);

    expect(InventoryItemResource::canViewAny())->toBeTrue()
        ->and(InventoryItemResource::canCreate())->toBeTrue();
    $this->get('/admin')->assertOk()->assertSee('Inventory')->assertSee(InventoryItemResource::getUrl('index'), escape: false);
    $this->get(InventoryItemResource::getUrl('index'))->assertOk();
    $this->get(InventoryItemResource::getUrl('create'))->assertOk();
    $this->get(InventoryItemResource::getUrl('edit', ['record' => $item]))->assertOk();

    inventoryContext($this, $clerk, $companyB);
    expect(Gate::forUser($clerk)->allows('view', $item))->toBeFalse();
    $this->get(InventoryItemResource::getUrl('edit', ['record' => $item]))->assertNotFound();

    inventoryContext($this, $finance, $companyA);
    expect(InventoryItemResource::canViewAny())->toBeFalse()
        ->and(InventoryItemResource::canCreate())->toBeFalse()
        ->and(Gate::forUser($finance)->allows('view', $item))->toBeFalse();
    $this->get('/admin')->assertDontSee('href="'.InventoryItemResource::getUrl('index').'"', false);
    $this->get(InventoryItemResource::getUrl('index'))->assertForbidden();
});

test('Inventory records remain inaccessible across tenants and scoped super administrators need active company context', function () {
    $this->seedAccessControl();

    $tenantA = $this->tenant();
    $tenantB = $this->tenant();
    $companyA = $this->company($tenantA);
    $companyB = $this->company($tenantB);
    $operations = $this->tenantUser($tenantA, [], [RoleName::OperationsManager->value]);
    inventoryContext($this, $operations, $companyA);
    $item = app(CreateInventoryItemAction::class)->execute(inventoryItemData(), $operations);

    $foreignOperations = $this->tenantUser($tenantB, [], [RoleName::OperationsManager->value]);
    inventoryContext($this, $foreignOperations, $companyB);
    expect(Gate::forUser($foreignOperations)->allows('view', $item))->toBeFalse();

    $super = $this->tenantUser($tenantA, [], [RoleName::SuperAdministrator->value]);
    $this->actingAs($super)->withSession([]);
    expect(InventoryItemResource::canViewAny())->toBeFalse();

    inventoryContext($this, $super, $companyA);
    expect(InventoryItemResource::canViewAny())->toBeTrue()
        ->and(Gate::forUser($super)->allows('view', $item))->toBeTrue();
});
