<?php

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Resources\InventoryItems\InventoryItemResource;
use App\Core\Tenancy\Support\CompanyContext;
use App\Inventory\Actions\CreateInventoryItemAction;
use App\Inventory\Actions\RecordStockMovementAction;
use App\Inventory\Enums\InventoryItemStatus;
use App\Inventory\Enums\StockMovementType;
use App\Inventory\Models\InventoryItem;
use App\Inventory\Services\InventoryOverviewService;
use App\Models\User;
use Carbon\CarbonImmutable;

function inventoryOverviewContext($test, User $user, $company): void
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
function inventoryOverviewItemData(array $overrides = []): array
{
    return [
        'code' => 'INV-OVERVIEW-001',
        'name' => 'Overview item',
        'category' => 'Consumables',
        'unit_of_measure' => 'pcs',
        'minimum_stock_level' => '5',
        'status' => InventoryItemStatus::Active->value,
        ...$overrides,
    ];
}

test('Inventory overview uses the existing stock thresholds and excludes inactive items', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    inventoryOverviewContext($this, $operations, $company);

    $out = app(CreateInventoryItemAction::class)->execute(inventoryOverviewItemData(['code' => 'OUT-001', 'name' => 'Out item']), $operations);
    $low = app(CreateInventoryItemAction::class)->execute(inventoryOverviewItemData(['code' => 'LOW-001', 'name' => 'Low item']), $operations);
    $in = app(CreateInventoryItemAction::class)->execute(inventoryOverviewItemData(['code' => 'IN-001', 'name' => 'In item']), $operations);
    $inactive = app(CreateInventoryItemAction::class)->execute(inventoryOverviewItemData(['code' => 'OLD-001', 'status' => InventoryItemStatus::Inactive->value]), $operations);

    $low->forceFill(['quantity_on_hand' => '5.000'])->saveQuietly();
    $in->forceFill(['quantity_on_hand' => '5.001'])->saveQuietly();
    $inactive->forceFill(['quantity_on_hand' => '0.000'])->saveQuietly();

    $overview = app(InventoryOverviewService::class)->forCompany($tenant->getKey(), $company->getKey());

    expect($overview['summary'])->toBe([
        'active_items' => 3,
        'in_stock_items' => 1,
        'low_stock_items' => 1,
        'out_of_stock_items' => 1,
    ])->and($overview['attention_items']->pluck('code')->all())->toBe(['OUT-001', 'LOW-001']);
});

test('Inventory overview lists the ten newest company-scoped immutable movements', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $foreignCompany = $this->company($tenant);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    inventoryOverviewContext($this, $operations, $company);

    $item = app(CreateInventoryItemAction::class)->execute(inventoryOverviewItemData(['code' => 'LEDGER-LOCAL']), $operations);
    $ledger = app(RecordStockMovementAction::class);

    foreach (range(1, 11) as $number) {
        $ledger->execute($item, StockMovementType::Receipt, [
            'quantity' => '1',
            'reference' => "LOCAL-{$number}",
            'occurred_at' => CarbonImmutable::parse("2026-01-{$number} 10:00:00"),
        ], $operations);
    }

    inventoryOverviewContext($this, $operations, $foreignCompany);
    $foreignItem = app(CreateInventoryItemAction::class)->execute(inventoryOverviewItemData(['code' => 'LEDGER-FOREIGN']), $operations);
    $ledger->execute($foreignItem, StockMovementType::Receipt, [
        'quantity' => '1',
        'reference' => 'FOREIGN-LATEST',
        'occurred_at' => CarbonImmutable::parse('2026-12-31 10:00:00'),
    ], $operations);

    $overview = app(InventoryOverviewService::class)->forCompany($tenant->getKey(), $company->getKey());

    expect($overview['recent_movements'])->toHaveCount(10)
        ->and($overview['recent_movements']->pluck('reference')->all())->toBe([
            'LOCAL-11', 'LOCAL-10', 'LOCAL-9', 'LOCAL-8', 'LOCAL-7',
            'LOCAL-6', 'LOCAL-5', 'LOCAL-4', 'LOCAL-3', 'LOCAL-2',
        ]);
});

test('Inventory overview is available to Inventory roles, denies Finance, and is read-only', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant);

    foreach ([RoleName::CompanyAdministrator, RoleName::OperationsManager, RoleName::DataEntryClerk] as $role) {
        $user = $this->tenantUser($tenant, [], [$role->value]);
        inventoryOverviewContext($this, $user, $company);

        $this->get(InventoryItemResource::getUrl('index'))
            ->assertOk()
            ->assertSee('Inventory Overview')
            ->assertDontSee('Receive Stock')
            ->assertDontSee('Issue Stock')
            ->assertDontSee('Adjust Stock');
    }

    $finance = $this->tenantUser($tenant, [], [RoleName::FinanceManager->value]);
    inventoryOverviewContext($this, $finance, $company);
    $this->get(InventoryItemResource::getUrl('index'))->assertForbidden();
});

test('Inventory attention query remains scoped to the active tenant and company', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $otherTenant = $this->tenant();
    $company = $this->company($tenant);
    $otherCompany = $this->company($otherTenant);
    $operations = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    inventoryOverviewContext($this, $operations, $company);

    $local = app(CreateInventoryItemAction::class)->execute(inventoryOverviewItemData(['code' => 'LOCAL-ATTENTION']), $operations);
    $local->forceFill(['quantity_on_hand' => '0.000'])->saveQuietly();

    $foreign = InventoryItem::factory()->create([
        'tenant_id' => $otherTenant->getKey(),
        'company_id' => $otherCompany->getKey(),
        'code' => 'FOREIGN-ATTENTION',
        'status' => InventoryItemStatus::Active,
        'quantity_on_hand' => '0.000',
        'minimum_stock_level' => '5.000',
    ]);

    $overview = app(InventoryOverviewService::class)->forCompany($tenant->getKey(), $company->getKey());

    expect($overview['attention_items']->pluck('code')->all())->toBe(['LOCAL-ATTENTION'])
        ->and($overview['attention_items']->pluck('id')->all())->not->toContain($foreign->getKey());
});
