<?php

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Pages\FinanceDashboard;
use App\CRM\Models\Client;
use App\Finance\Enums\BillingRecordStatus;
use App\Finance\Models\BillingBatch;
use App\Finance\Models\BillingRecord;
use App\Finance\Services\FinanceReportingService;

function financeReportingActor(): array
{
    test()->seedAccessControl();
    $tenant = test()->tenant();
    $company = test()->company($tenant, ['name' => 'Kadmay', 'currency' => 'GHS']);
    $finance = test()->tenantUser($tenant, ['email' => 'finance-reporting@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    $client = Client::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'legal_name' => 'GPHA']);

    return [$tenant, $company, $finance, $client];
}

function billingRecordForReport(Client $client, array $attributes = []): BillingRecord
{
    $batch = BillingBatch::factory()->create([
        'tenant_id' => $client->tenant_id,
        'company_id' => $client->company_id,
        'client_id' => $client->getKey(),
    ]);

    return BillingRecord::factory()->create(array_merge([
        'tenant_id' => $client->tenant_id,
        'company_id' => $client->company_id,
        'client_id' => $client->getKey(),
        'billing_batch_id' => $batch->getKey(),
        'status' => BillingRecordStatus::Paid,
        'batch_amount' => 100.00,
        'receipt_amount' => 100.00,
        'currency' => 'GHS',
        'issued_at' => now()->startOfMonth()->addDay(),
        'paid_at' => now()->startOfMonth()->addDays(2),
    ], $attributes));
}

test('Finance reporting uses paid dates and excludes non-realized, out-of-period, and deleted Billing Records', function () {
    [$tenant, $company, , $gpha] = financeReportingActor();
    $secondClient = Client::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'legal_name' => 'Tema Oil']);

    billingRecordForReport($gpha, ['receipt_amount' => 5850.00, 'batch_amount' => 5850.00]);
    billingRecordForReport($secondClient, ['receipt_amount' => 150.00, 'batch_amount' => 150.00, 'status' => BillingRecordStatus::Closed]);
    billingRecordForReport($gpha, ['receipt_amount' => 300.00, 'batch_amount' => 300.00, 'status' => BillingRecordStatus::Issued, 'paid_at' => null]);
    billingRecordForReport($gpha, ['receipt_amount' => 400.00, 'batch_amount' => 400.00, 'paid_at' => now()->subMonth()->startOfMonth()]);
    $deleted = billingRecordForReport($gpha, ['receipt_amount' => 900.00, 'batch_amount' => 900.00]);
    $deleted->delete();
    billingRecordForReport($gpha, ['receipt_amount' => 80.00, 'batch_amount' => 80.00, 'currency' => 'USD']);

    $report = app(FinanceReportingService::class)->dashboard($tenant->getKey(), $company->getKey());

    expect($report['revenue_mtd'])->toBe(['GHS' => '6,000.00', 'USD' => '80.00'])
        ->and($report['revenue_ytd'])->toBe(['GHS' => '6,400.00', 'USD' => '80.00'])
        ->and($report['issued_unpaid'])->toBe(['GHS' => '300.00', 'USD' => '0.00'])
        ->and($report['paid_this_month'])->toBe(3)
        ->and($report['status_counts'])->toMatchArray([
            'draft' => 0,
            'issued' => 1,
            'paid' => 3,
            'closed' => 1,
        ])
        ->and($report['top_clients']->first()['client_name'])->toBe($gpha->display_name)
        ->and($report['top_clients']->first()['total'])->toBe(6250.0);
});

test('Finance reporting is tenant and company scoped and the page is denied to Operations', function () {
    [$tenant, $company, $finance, $client] = financeReportingActor();
    billingRecordForReport($client, ['receipt_amount' => 500.00, 'batch_amount' => 500.00]);

    $otherCompany = $this->company($tenant, ['name' => 'Other Kadmay']);
    $otherClient = Client::factory()->create(['tenant_id' => $tenant->getKey(), 'company_id' => $otherCompany->getKey(), 'legal_name' => 'Other client']);
    billingRecordForReport($otherClient, ['receipt_amount' => 900.00, 'batch_amount' => 900.00]);

    $otherTenant = $this->tenant(['name' => 'Other tenant']);
    $otherTenantCompany = $this->company($otherTenant, ['name' => 'Other tenant company']);
    $otherTenantClient = Client::factory()->create(['tenant_id' => $otherTenant->getKey(), 'company_id' => $otherTenantCompany->getKey(), 'legal_name' => 'Other tenant client']);
    billingRecordForReport($otherTenantClient, ['receipt_amount' => 1100.00, 'batch_amount' => 1100.00]);

    $report = app(FinanceReportingService::class)->dashboard($tenant->getKey(), $company->getKey());
    expect($report['revenue_mtd'])->toBe(['GHS' => '500.00']);

    $operations = $this->tenantUser($tenant, ['email' => 'finance-reporting-operations@example.test'], [RoleName::OperationsManager->value]);
    $operations->companies()->sync([$company->getKey()]);

    $this->actingAs($finance)
        ->withSession(['active_company_id' => $company->getKey()])
        ->get(FinanceDashboard::getUrl())
        ->assertOk()
        ->assertSee('Revenue MTD')
        ->assertSee('GHS 500.00');

    $this->actingAs($operations)
        ->withSession(['active_company_id' => $company->getKey()])
        ->get(FinanceDashboard::getUrl())
        ->assertForbidden();
});

test('Finance reporting shows a known single currency zero for issued unpaid records', function () {
    [$tenant, $company] = financeReportingActor();

    $report = app(FinanceReportingService::class)->dashboard($tenant->getKey(), $company->getKey());

    expect($report['issued_unpaid'])->toBe(['GHS' => '0.00']);
});

test('reporting period filters use paid and issued lifecycle dates instead of record creation dates', function () {
    [$tenant, $company, , $client] = financeReportingActor();
    $from = now()->startOfMonth()->toDateString();
    $until = now()->endOfMonth()->toDateString();

    $paidInside = billingRecordForReport($client, [
        'receipt_amount' => 200.00,
        'batch_amount' => 200.00,
        'created_at' => now()->subYear(),
        'paid_at' => now()->startOfMonth()->addDays(3),
    ]);
    billingRecordForReport($client, [
        'receipt_amount' => 300.00,
        'batch_amount' => 300.00,
        'created_at' => now(),
        'paid_at' => now()->subMonth()->startOfMonth(),
    ]);
    $issuedInside = billingRecordForReport($client, [
        'status' => BillingRecordStatus::Issued,
        'receipt_amount' => 150.00,
        'batch_amount' => 150.00,
        'created_at' => now()->subYear(),
        'issued_at' => now()->startOfMonth()->addDays(4),
        'paid_at' => null,
    ]);
    billingRecordForReport($client, [
        'status' => BillingRecordStatus::Issued,
        'receipt_amount' => 175.00,
        'batch_amount' => 175.00,
        'created_at' => now(),
        'issued_at' => now()->subMonth()->startOfMonth(),
        'paid_at' => null,
    ]);
    billingRecordForReport($client, [
        'status' => BillingRecordStatus::Draft,
        'receipt_amount' => null,
        'created_at' => now(),
        'issued_at' => null,
        'paid_at' => null,
    ]);

    $report = app(FinanceReportingService::class)->dashboard($tenant->getKey(), $company->getKey(), [
        'client_id' => '',
        'status' => '',
        'currency' => 'GHS',
        'from' => $from,
        'until' => $until,
    ]);

    expect($report['revenue_mtd'])->toBe(['GHS' => '200.00'])
        ->and($report['issued_unpaid'])->toBe(['GHS' => '150.00'])
        ->and($report['status_counts'])->toMatchArray(['draft' => 0, 'issued' => 1, 'paid' => 1, 'closed' => 0])
        ->and($report['recent_records']->pluck('id')->all())->toContain($paidInside->getKey(), $issuedInside->getKey())
        ->and($report['recent_records'])->toHaveCount(2)
        ->and((float) $report['top_clients']->first()['total'])->toBe(200.0);
});
