<?php

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Resources\InventoryItems\InventoryItemResource;
use App\Core\Administration\Filament\Resources\InventoryItems\Pages\ViewInventoryItem;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Tenancy\Support\CompanyContext;
use App\Fleet\Models\FleetAsset;
use App\Inventory\Actions\CreateInventoryItemAction;
use App\Inventory\Actions\RecordStockMovementAction;
use App\Inventory\Enums\InventoryItemStatus;
use App\Inventory\Enums\StockMovementType;
use App\Inventory\Models\StockMovement;
use App\Models\User;
use App\Operations\Models\Personnel;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;

function stockMovementContext($test, User $user, $company): void
{
    $user->companies()->syncWithoutDetaching([$company->getKey()]);
    $test->actingAs($user);
    $test->withSession([
        'active_company_id' => $company->getKey(),
        'support_tenant_id' => $user->hasRole(RoleName::SuperAdministrator->value) ? $company->tenant_id : null,
    ]);
    app(CompanyContext::class)->set(null);
}

/** @return array<string, mixed> */
function stockMovementItemData(array $overrides = []): array
{
    return [
        'code' => 'FLT-HYD-001',
        'name' => 'Hydraulic Filter',
        'category' => 'Filters',
        'unit_of_measure' => 'pcs',
        'minimum_stock_level' => '5',
        'status' => InventoryItemStatus::Active->value,
        ...$overrides,
    ];
}

/** @return array<string, mixed> */
function stockMovementData(array $overrides = []): array
{
    return [
        'quantity' => '1',
        'occurred_at' => now(),
        'reference' => 'QA-REF',
        'notes' => null,
        ...$overrides,
    ];
}

test('opening balance, receipts, issues, and adjustments maintain an exact immutable ledger', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $administrator = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    stockMovementContext($this, $administrator, $company);
    $item = app(CreateInventoryItemAction::class)->execute(stockMovementItemData(), $administrator);
    $ledger = app(RecordStockMovementAction::class);

    $opening = $ledger->execute($item, StockMovementType::OpeningBalance, stockMovementData(['quantity' => '20']), $administrator);
    $issue = $ledger->execute($item, StockMovementType::Issue, stockMovementData(['quantity' => '3']), $administrator);
    $decrease = $ledger->execute($item, StockMovementType::AdjustmentDecrease, stockMovementData(['quantity' => '2', 'notes' => 'Count correction']), $administrator);
    $increase = $ledger->execute($item, StockMovementType::AdjustmentIncrease, stockMovementData(['quantity' => '0.125', 'notes' => 'Count correction']), $administrator);

    expect($opening->quantity_before)->toBe('0.000')
        ->and($opening->quantity_after)->toBe('20.000')
        ->and($issue->signed_quantity_delta)->toBe('-3.000')
        ->and($decrease->quantity_after)->toBe('15.000')
        ->and($increase->quantity_after)->toBe('15.125')
        ->and($item->fresh()->quantity_on_hand)->toBe('15.125')
        ->and(StockMovement::query()->where('inventory_item_id', $item->getKey())->count())->toBe(4)
        ->and(Activity::query()->where('event', 'stock_movement.recorded')->count())->toBe(4);
});

test('opening balance is available only once before every other movement', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    stockMovementContext($this, $operations, $company);
    $item = app(CreateInventoryItemAction::class)->execute(stockMovementItemData(), $operations);
    $ledger = app(RecordStockMovementAction::class);

    $ledger->execute($item, StockMovementType::Receipt, stockMovementData(['quantity' => '2']), $operations);

    expect(fn () => $ledger->execute($item, StockMovementType::OpeningBalance, stockMovementData(['quantity' => '10']), $operations))
        ->toThrow(BusinessException::class, 'only once');
});

test('data entry clerk can receive and issue stock but cannot adjust, while Finance cannot record movements', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    $clerk = $this->tenantUser($tenant, [], [RoleName::DataEntryClerk->value]);
    $finance = $this->tenantUser($tenant, [], [RoleName::FinanceManager->value]);
    stockMovementContext($this, $operations, $company);
    $item = app(CreateInventoryItemAction::class)->execute(stockMovementItemData(), $operations);
    $ledger = app(RecordStockMovementAction::class);
    $ledger->execute($item, StockMovementType::Receipt, stockMovementData(['quantity' => '20']), $operations);

    stockMovementContext($this, $clerk, $company);
    $ledger->execute($item, StockMovementType::Issue, stockMovementData(['quantity' => '3']), $clerk);
    expect(fn () => $ledger->execute($item, StockMovementType::AdjustmentIncrease, stockMovementData(['quantity' => '1', 'notes' => 'Count correction']), $clerk))
        ->toThrow(BusinessException::class, 'not allowed');

    stockMovementContext($this, $finance, $company);
    expect(fn () => $ledger->execute($item, StockMovementType::Receipt, stockMovementData(), $finance))
        ->toThrow(BusinessException::class, 'not allowed');
});

test('stock movements reject invalid quantities, inactive items, and negative balances without partial writes', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    stockMovementContext($this, $operations, $company);
    $item = app(CreateInventoryItemAction::class)->execute(stockMovementItemData(), $operations);
    $ledger = app(RecordStockMovementAction::class);

    expect(fn () => $ledger->execute($item, StockMovementType::Receipt, stockMovementData(['quantity' => '0']), $operations))
        ->toThrow(BusinessException::class, 'greater than zero');
    expect(fn () => $ledger->execute($item, StockMovementType::Receipt, stockMovementData(['quantity' => '-1']), $operations))
        ->toThrow(BusinessException::class, 'greater than zero');

    $ledger->execute($item, StockMovementType::Receipt, stockMovementData(['quantity' => '1.250']), $operations);
    expect(fn () => $ledger->execute($item, StockMovementType::Issue, stockMovementData(['quantity' => '1.251']), $operations))
        ->toThrow(BusinessException::class, 'below zero');
    expect($item->fresh()->quantity_on_hand)->toBe('1.250')
        ->and(StockMovement::query()->where('inventory_item_id', $item->getKey())->count())->toBe(1);

    $inactive = app(CreateInventoryItemAction::class)->execute(stockMovementItemData(['code' => 'INACTIVE-001', 'status' => 'inactive']), $operations);
    expect(fn () => $ledger->execute($inactive, StockMovementType::Receipt, stockMovementData(), $operations))
        ->toThrow(BusinessException::class, 'Inactive');
});

test('stock movement context is restricted to the same company and tenant', function () {
    $this->seedAccessControl();
    $tenantA = $this->tenant();
    $tenantB = $this->tenant();
    $companyA = $this->company($tenantA);
    $companyB = $this->company($tenantB);
    $operations = $this->tenantUser($tenantA, [], [RoleName::OperationsManager->value]);
    stockMovementContext($this, $operations, $companyA);
    $item = app(CreateInventoryItemAction::class)->execute(stockMovementItemData(), $operations);
    $foreignAsset = FleetAsset::factory()->create(['tenant_id' => $tenantB->getKey(), 'company_id' => $companyB->getKey()]);
    $foreignPersonnel = Personnel::query()->create([
        'tenant_id' => $tenantB->getKey(),
        'company_id' => $companyB->getKey(),
        'first_name' => 'Foreign',
        'status' => 'active',
        'can_operate_equipment' => true,
        'can_drive' => false,
    ]);
    $ledger = app(RecordStockMovementAction::class);

    expect(fn () => $ledger->execute($item, StockMovementType::Receipt, stockMovementData(['fleet_asset_id' => $foreignAsset->getKey()]), $operations))
        ->toThrow(BusinessException::class, 'Fleet asset must belong');
    expect(fn () => $ledger->execute($item, StockMovementType::Receipt, stockMovementData(['personnel_id' => $foreignPersonnel->getKey()]), $operations))
        ->toThrow(BusinessException::class, 'Personnel must belong');
    expect($item->fresh()->quantity_on_hand)->toBe('0.000')
        ->and(StockMovement::query()->where('inventory_item_id', $item->getKey())->count())->toBe(0);
});

test('stock movement records and direct Eloquent balance edits are immutable', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $administrator = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    stockMovementContext($this, $administrator, $company);
    $item = app(CreateInventoryItemAction::class)->execute(stockMovementItemData(), $administrator);
    $movement = app(RecordStockMovementAction::class)->execute($item, StockMovementType::Receipt, stockMovementData(['quantity' => '5']), $administrator);

    $movement->reference = 'CHANGED';
    expect(fn () => $movement->save())->toThrow(LogicException::class, 'immutable');
    expect(fn () => $movement->delete())->toThrow(LogicException::class, 'immutable');

    $item = $item->fresh();
    $item->quantity_on_hand = '99.000';
    expect(fn () => $item->save())->toThrow(LogicException::class, 'maintained only');
    expect($item->fresh()->quantity_on_hand)->toBe('5.000');
});

test('movement history is visible to Inventory operators while adjustment controls and direct access remain protected', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    $clerk = $this->tenantUser($tenant, [], [RoleName::DataEntryClerk->value]);
    $finance = $this->tenantUser($tenant, [], [RoleName::FinanceManager->value]);
    stockMovementContext($this, $operations, $company);
    $item = app(CreateInventoryItemAction::class)->execute(stockMovementItemData(), $operations);
    app(RecordStockMovementAction::class)->execute($item, StockMovementType::Receipt, stockMovementData(['quantity' => '5']), $operations);

    stockMovementContext($this, $clerk, $company);
    session(['active_company_id' => $company->getKey()]);
    Livewire::test(ViewInventoryItem::class, ['record' => $item->getKey()])
        ->assertSee('Movement History')
        ->assertSee('Receive Stock')
        ->assertDontSee('Adjust Stock');

    stockMovementContext($this, $finance, $company);
    $this->get(InventoryItemResource::getUrl('view', ['record' => $item]))->assertForbidden();
});

test('the ledger action uses a transaction-bound row lock for balance updates', function () {
    $source = file_get_contents(app_path('Inventory/Actions/RecordStockMovementAction.php'));

    expect($source)->toContain('lockForUpdate()')
        ->and($source)->toContain('DB::transaction');
});
