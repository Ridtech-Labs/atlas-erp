<?php

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Resources\BillingBatches\BillingBatchResource;
use App\Core\Administration\Filament\Resources\BillingBatches\Pages\ViewBillingBatch;
use App\Core\Administration\Filament\Resources\RateAgreements\RateAgreementResource;
use App\Core\Administration\Filament\Resources\RateAgreements\Schemas\RateAgreementForm;
use App\Core\Shared\Exceptions\BusinessException;
use App\CRM\Models\Client;
use App\Finance\Actions\BillingBatches\AddJobCardsToBillingBatchAction;
use App\Finance\Actions\BillingBatches\CreateBillingBatchAction;
use App\Finance\Actions\BillingBatches\PrepareBillingBatchAction;
use App\Finance\Actions\BillingBatches\RemoveBillingBatchLineAction;
use App\Finance\Actions\RateAgreements\CreateRateAgreementAction;
use App\Finance\Actions\RateAgreements\UpdateRateAgreementAction;
use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Enums\RateAgreementStatus;
use App\Finance\Models\RateAgreement;
use App\Finance\Services\BillingBatchEligibilityService;
use App\Finance\Services\RateResolverService;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Models\JobCardWorkEntry;
use Filament\Notifications\Notification;
use Livewire\Livewire;

function financeVerifiedJobCard(Client $client, array $cardOverrides = [], array $entryOverrides = []): JobCard
{
    $job = Job::factory()->create([
        'tenant_id' => $client->tenant_id,
        'company_id' => $client->company_id,
        'client_id' => $client->getKey(),
        'status' => JobStatus::InProgress,
        'equipment_requirement' => $cardOverrides['equipment_reference'] ?? 'Reach Stacker',
    ]);

    $jobCard = JobCard::factory()->create([
        'tenant_id' => $client->tenant_id,
        'company_id' => $client->company_id,
        'job_id' => $job->getKey(),
        'client_id' => $client->getKey(),
        'client_site_id' => $job->client_site_id,
        'card_date' => '2026-08-10',
        'equipment_reference' => 'Reach Stacker',
        'machine_number' => 'RS-01',
        'approval_status' => JobCardApprovalStatus::Verified,
        'total_hours' => 8.00,
        ...$cardOverrides,
    ]);

    JobCardWorkEntry::factory()->create([
        'job_card_id' => $jobCard->getKey(),
        'from_time' => '08:00:00',
        'to_time' => '16:00:00',
        'normal_hours' => 8.00,
        'overtime_hours' => 0.00,
        'total_hours' => 8.00,
        ...$entryOverrides,
    ]);

    return $jobCard->fresh(['job', 'workEntries']);
}

test('finance manager can create and update a rate agreement in the active company', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-rates@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'legal_name' => 'Tema Oil Refinery',
    ]);

    $agreement = app(CreateRateAgreementAction::class)->execute([
        'client_id' => $client->getKey(),
        'name' => 'Tema refinery standard tariff',
        'reference' => 'RA-KAD-2026-001',
        'effective_from' => '2026-08-01',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [
            [
                'equipment_reference' => 'Reach Stacker',
                'billing_unit' => 'hourly',
                'currency' => 'GHS',
                'rate' => 850.00,
            ],
        ],
    ], $finance);

    expect($agreement->tenant_id)->toBe($tenant->getKey())
        ->and($agreement->company_id)->toBe($company->getKey())
        ->and($agreement->client_id)->toBe($client->getKey())
        ->and($agreement->lines)->toHaveCount(1);

    $updated = app(UpdateRateAgreementAction::class)->execute($agreement, [
        'name' => 'Tema refinery revised tariff',
        'reference' => 'RA-KAD-2026-002',
        'effective_from' => '2026-08-01',
        'effective_to' => '2026-12-31',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [
            [
                'equipment_reference' => 'Reach Stacker',
                'billing_unit' => 'hourly',
                'currency' => 'GHS',
                'rate' => 900.00,
            ],
        ],
    ], $finance);

    expect($updated->name)->toBe('Tema refinery revised tariff')
        ->and((float) $updated->lines()->firstOrFail()->rate)->toBe(900.0);
});

test('operations manager cannot manage rate agreements and cross-company finance access is denied', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $companyA = $this->company($tenant, ['name' => 'Kadmay']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $operations = $this->tenantUser($tenant, ['email' => 'ops-rates@example.test'], [RoleName::OperationsManager->value]);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-cross@example.test'], [RoleName::FinanceManager->value]);
    $operations->companies()->sync([$companyA->getKey()]);
    $finance->companies()->sync([$companyA->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    $clientA = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyA->getKey(),
    ]);
    $clientB = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyB->getKey(),
    ]);

    expect(fn () => app(CreateRateAgreementAction::class)->execute([
        'client_id' => $clientA->getKey(),
        'name' => 'Unauthorized operations tariff',
        'effective_from' => '2026-08-01',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [[
            'equipment_reference' => 'Reach Stacker',
            'billing_unit' => 'hourly',
            'currency' => 'GHS',
            'rate' => 850.00,
        ]],
    ], $operations))->toThrow(BusinessException::class);

    expect(fn () => app(CreateRateAgreementAction::class)->execute([
        'client_id' => $clientB->getKey(),
        'name' => 'Cross-company tariff',
        'effective_from' => '2026-08-01',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [[
            'equipment_reference' => 'Reach Stacker',
            'billing_unit' => 'hourly',
            'currency' => 'GHS',
            'rate' => 850.00,
        ]],
    ], $finance))->toThrow(BusinessException::class);
});

test('rate resolution respects effective dates client scope and rejects missing or ambiguous matches', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-resolver@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $clientA = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'legal_name' => 'Tema Refinery',
    ]);
    $clientB = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'legal_name' => 'GPHA',
    ]);

    app(CreateRateAgreementAction::class)->execute([
        'client_id' => $clientA->getKey(),
        'name' => 'Tema base rate',
        'effective_from' => '2026-08-01',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [[
            'equipment_reference' => 'Reach Stacker',
            'billing_unit' => 'hourly',
            'currency' => 'GHS',
            'rate' => 850.00,
        ]],
    ], $finance);

    app(CreateRateAgreementAction::class)->execute([
        'client_id' => $clientA->getKey(),
        'name' => 'Tema future rate',
        'effective_from' => '2026-09-01',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [[
            'equipment_reference' => 'Reach Stacker',
            'billing_unit' => 'hourly',
            'currency' => 'GHS',
            'rate' => 950.00,
        ]],
    ], $finance);

    app(CreateRateAgreementAction::class)->execute([
        'client_id' => $clientB->getKey(),
        'name' => 'GPHA rate',
        'effective_from' => '2026-08-01',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [[
            'equipment_reference' => 'Reach Stacker',
            'billing_unit' => 'hourly',
            'currency' => 'GHS',
            'rate' => 1200.00,
        ]],
    ], $finance);

    $temaCard = financeVerifiedJobCard($clientA);
    $gphaCard = financeVerifiedJobCard($clientB);

    $temaResolved = app(RateResolverService::class)->resolveForJobCard($temaCard);
    $gphaResolved = app(RateResolverService::class)->resolveForJobCard($gphaCard);

    expect($temaResolved['rate'])->toBe(850.0)
        ->and($gphaResolved['rate'])->toBe(1200.0);

    $missingClient = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);
    $missingCard = financeVerifiedJobCard($missingClient, ['equipment_reference' => 'Crane']);

    expect(fn () => app(RateResolverService::class)->resolveForJobCard($missingCard))
        ->toThrow(BusinessException::class, 'No applicable billing rate exists for this Job Card evidence.');

    RateAgreement::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'client_id' => $clientA->getKey(),
        'name' => 'Tema ambiguous rate',
        'effective_from' => '2026-08-01',
        'status' => RateAgreementStatus::Active,
    ])->lines()->create([
        'equipment_reference' => 'Reach Stacker',
        'billing_unit' => 'hourly',
        'currency' => 'GHS',
        'rate' => 875.00,
    ]);

    expect(fn () => app(RateResolverService::class)->resolveForJobCard($temaCard->fresh()))
        ->toThrow(BusinessException::class, 'Multiple billing rates match this Job Card evidence. Resolve the ambiguity before billing.');
});

test('rate resolution prefers an exact machine override before falling back to the equipment class', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-machine@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);

    app(CreateRateAgreementAction::class)->execute([
        'client_id' => $client->getKey(),
        'name' => 'Forklift tariffs',
        'effective_from' => '2026-08-01',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [
            [
                'equipment_reference' => 'Forklift 4.5T/5T',
                'billing_unit' => 'hourly',
                'currency' => 'GHS',
                'rate' => 650.00,
            ],
            [
                'equipment_reference' => 'Forklift 4.5T/5T',
                'machine_number' => 'KGL007',
                'billing_unit' => 'hourly',
                'currency' => 'GHS',
                'rate' => 720.00,
            ],
        ],
    ], $finance);

    $overrideCard = financeVerifiedJobCard($client, [
        'equipment_reference' => 'Forklift 4.5T/5T',
        'machine_number' => 'KGL007',
    ]);
    $classOnlyCard = financeVerifiedJobCard($client, [
        'equipment_reference' => 'Forklift 4.5T/5T',
        'machine_number' => 'KGL008',
    ]);

    expect(app(RateResolverService::class)->resolveForJobCard($overrideCard)['rate'])->toBe(720.0)
        ->and(app(RateResolverService::class)->resolveForJobCard($classOnlyCard)['rate'])->toBe(650.0);
});

test('rate agreement lines inherit the parent agreement period when line dates are left blank', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-inherit@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);

    $agreement = app(CreateRateAgreementAction::class)->execute([
        'client_id' => $client->getKey(),
        'name' => 'Inherited line dates',
        'effective_from' => '2026-08-01',
        'effective_to' => '2026-08-31',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [[
            'equipment_reference' => 'Reach Stacker',
            'billing_unit' => 'hourly',
            'currency' => 'GHS',
            'rate' => 850.00,
            'effective_from' => '2026-08-01',
            'effective_to' => '2026-08-31',
        ]],
    ], $finance);

    $line = $agreement->lines()->with('rateAgreement')->firstOrFail();
    $resolved = app(RateResolverService::class)->resolveForJobCard(financeVerifiedJobCard($client, ['card_date' => '2026-08-10']));

    expect($line->fresh()->effective_from)->toBeNull()
        ->and($line->fresh()->effective_to)->toBeNull()
        ->and($line->fresh()->inheritsAgreementPeriod())->toBeTrue()
        ->and($line->fresh()->effectiveFromOrAgreement())->toBe('2026-08-01')
        ->and($line->fresh()->effectiveToOrAgreement())->toBe('2026-08-31')
        ->and($resolved['rate'])->toBe(850.0);
});

test('rate agreement line date overrides constrain resolution without changing the parent agreement period', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-line-override@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);

    $agreement = app(CreateRateAgreementAction::class)->execute([
        'client_id' => $client->getKey(),
        'name' => 'Mid-month equipment override',
        'effective_from' => '2026-08-01',
        'effective_to' => '2026-08-31',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [[
            'equipment_reference' => 'Reach Stacker',
            'billing_unit' => 'hourly',
            'currency' => 'GHS',
            'rate' => 850.00,
            'effective_from' => '2026-08-15',
            'effective_to' => '2026-08-20',
        ]],
    ], $finance);

    $line = $agreement->lines()->firstOrFail();
    $inRangeCard = financeVerifiedJobCard($client, ['card_date' => '2026-08-16']);
    $outOfRangeCard = financeVerifiedJobCard($client, ['card_date' => '2026-08-10']);

    expect($line->effective_from?->toDateString())->toBe('2026-08-15')
        ->and($line->effective_to?->toDateString())->toBe('2026-08-20')
        ->and($line->inheritsAgreementPeriod())->toBeFalse()
        ->and(app(RateResolverService::class)->resolveForJobCard($inRangeCard)['rate'])->toBe(850.0);

    expect(fn () => app(RateResolverService::class)->resolveForJobCard($outOfRangeCard))
        ->toThrow(BusinessException::class, 'No applicable billing rate exists for this Job Card evidence.');
});

test('rate agreement lines must target either an equipment class or a specific machine', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-line-validation@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);

    expect(fn () => app(CreateRateAgreementAction::class)->execute([
        'client_id' => $client->getKey(),
        'name' => 'Invalid tariff',
        'effective_from' => '2026-08-01',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [[
            'billing_unit' => 'hourly',
            'currency' => 'GHS',
            'rate' => 850.00,
        ]],
    ], $finance))->toThrow(BusinessException::class, 'Each Rate Agreement line must target either an equipment class/type or a specific machine.');
});

test('rate agreement create repeater rows resolve suggestions from the selected top level client', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-suggestions@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $gpha = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'legal_name' => 'Ghana Ports & Harbours Authority',
        'trading_name' => 'GPHA',
    ]);
    $otherClient = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'legal_name' => 'Tema Oil Refinery',
    ]);
    $otherTenant = $this->tenant();
    $otherCompany = $this->company($otherTenant, ['name' => 'Other company']);
    $otherTenantClient = Client::factory()->create([
        'tenant_id' => $otherTenant->getKey(),
        'company_id' => $otherCompany->getKey(),
        'legal_name' => 'External Client',
    ]);

    Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'client_id' => $gpha->getKey(),
        'equipment_requirement' => 'Reach Stacker',
    ]);
    Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'client_id' => $gpha->getKey(),
        'equipment_requirement' => 'Forklift FL-2',
    ]);
    Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'client_id' => $gpha->getKey(),
        'equipment_requirement' => 'TLT-KAD-0303',
    ]);
    Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'client_id' => $otherClient->getKey(),
        'equipment_requirement' => 'Tema-only loader',
    ]);
    Job::factory()->create([
        'tenant_id' => $otherTenant->getKey(),
        'company_id' => $otherCompany->getKey(),
        'client_id' => $otherTenantClient->getKey(),
        'equipment_requirement' => 'Cross-tenant crane',
    ]);

    JobCard::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'job_id' => Job::factory()->create([
            'tenant_id' => $tenant->getKey(),
            'company_id' => $company->getKey(),
            'client_id' => $gpha->getKey(),
            'equipment_requirement' => 'Reach Stacker',
        ])->getKey(),
        'client_id' => $gpha->getKey(),
        'equipment_reference' => 'Reach Stacker',
        'machine_number' => 'RS-01',
    ]);
    JobCard::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'job_id' => Job::factory()->create([
            'tenant_id' => $tenant->getKey(),
            'company_id' => $company->getKey(),
            'client_id' => $gpha->getKey(),
            'equipment_requirement' => 'TLT-KAD-0303',
        ])->getKey(),
        'client_id' => $gpha->getKey(),
        'equipment_reference' => 'TLT-KAD-0303',
        'machine_number' => 'TLT-01',
    ]);
    JobCard::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'job_id' => Job::factory()->create([
            'tenant_id' => $tenant->getKey(),
            'company_id' => $company->getKey(),
            'client_id' => $otherClient->getKey(),
            'equipment_requirement' => 'Tema-only loader',
        ])->getKey(),
        'client_id' => $otherClient->getKey(),
        'equipment_reference' => 'Tema-only loader',
        'machine_number' => 'TM-01',
    ]);

    $resolveClientId = new ReflectionMethod(RateAgreementForm::class, 'resolveSelectedClientId');
    $resolveClientId->setAccessible(true);

    $resolvedClientId = $resolveClientId->invoke(null, [$gpha->getKey(), null, null], null);

    $suggestionsMethod = new ReflectionMethod(RateAgreementForm::class, 'equipmentClassSuggestions');
    $suggestionsMethod->setAccessible(true);

    $suggestions = $suggestionsMethod->invoke(null, $resolvedClientId);

    expect($resolvedClientId)->toBe($gpha->getKey())
        ->and($suggestions)->toEqual([
            'Forklift FL-2',
            'Reach Stacker',
            'TLT-KAD-0303',
        ])
        ->and($suggestions)->not->toContain('Tema-only loader')
        ->and($suggestions)->not->toContain('Cross-tenant crane');
});

test('rate agreement edit suggestions fall back to the record client when no create state is available', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-edit-suggestions@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'legal_name' => 'GPHA',
    ]);

    $agreement = RateAgreement::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'client_id' => $client->getKey(),
    ]);

    Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'client_id' => $client->getKey(),
        'equipment_requirement' => 'Reach Stacker',
    ]);

    $resolveClientId = new ReflectionMethod(RateAgreementForm::class, 'resolveSelectedClientId');
    $resolveClientId->setAccessible(true);

    $resolvedClientId = $resolveClientId->invoke(null, [null, null, null], $agreement);

    $suggestionsMethod = new ReflectionMethod(RateAgreementForm::class, 'equipmentClassSuggestions');
    $suggestionsMethod->setAccessible(true);

    $suggestions = $suggestionsMethod->invoke(null, $resolvedClientId);

    expect($resolvedClientId)->toBe($client->getKey())
        ->and($suggestions)->toContain('Reach Stacker');
});

test('free text equipment values remain permitted for rate agreement lines', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-free-text@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);

    $agreement = app(CreateRateAgreementAction::class)->execute([
        'client_id' => $client->getKey(),
        'name' => 'Custom free text tariff',
        'effective_from' => '2026-08-01',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [[
            'equipment_reference' => 'Unlisted Custom Asset',
            'billing_unit' => 'hourly',
            'currency' => 'GHS',
            'rate' => 500.00,
        ]],
    ], $finance);

    expect($agreement->lines()->firstOrFail()->equipment_reference)->toBe('Unlisted Custom Asset');
});

test('finance manager can create a billing batch from verified evidence and snapshot commercial values', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-batch@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);

    $rateAgreement = app(CreateRateAgreementAction::class)->execute([
        'client_id' => $client->getKey(),
        'name' => 'Kadmay August tariff',
        'effective_from' => '2026-08-01',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [[
            'equipment_reference' => 'Reach Stacker',
            'billing_unit' => 'hourly',
            'currency' => 'GHS',
            'rate' => 850.00,
        ]],
    ], $finance);

    $jobCardA = financeVerifiedJobCard($client, ['card_date' => '2026-08-10']);
    $jobCardB = financeVerifiedJobCard($client, ['card_date' => '2026-08-12'], ['total_hours' => 6.50, 'normal_hours' => 6.50]);
    $entryA = $jobCardA->workEntries()->firstOrFail();
    $entryB = $jobCardB->workEntries()->firstOrFail();

    $batch = app(CreateBillingBatchAction::class)->execute([
        'client_id' => $client->getKey(),
        'notes' => 'Mid-month heavy machinery billing',
    ], $finance);

    expect($jobCardA->hourly_rate)->toBeNull()
        ->and($jobCardA->rate_currency)->toBeNull()
        ->and($jobCardA->billable_amount)->toBeNull()
        ->and($jobCardB->hourly_rate)->toBeNull()
        ->and($jobCardB->rate_currency)->toBeNull()
        ->and($jobCardB->billable_amount)->toBeNull();

    $batch = app(AddJobCardsToBillingBatchAction::class)->execute($batch, [$entryA->getKey(), $entryB->getKey()], $finance);

    expect($batch->lines()->count())->toBe(2)
        ->and($batch->fresh()->period_start?->toDateString())->toBe('2026-08-10')
        ->and($batch->fresh()->period_end?->toDateString())->toBe('2026-08-12');

    $firstLine = $batch->lines()->orderBy('activity_date')->firstOrFail();
    $secondLine = $batch->lines()->orderByDesc('activity_date')->firstOrFail();

    expect((float) $firstLine->hours)->toBe(8.0)
        ->and((float) $firstLine->resolved_rate)->toBe(850.0)
        ->and((float) $firstLine->line_amount)->toBe(6800.0)
        ->and((float) $secondLine->hours)->toBe(6.5)
        ->and((float) $batch->subtotalHours())->toBe(14.5)
        ->and((float) $batch->subtotalAmount())->toBe(12325.0);

    $rateAgreement->lines()->firstOrFail()->update(['rate' => 910.00]);

    expect((float) $firstLine->fresh()->resolved_rate)->toBe(850.0)
        ->and((float) $firstLine->fresh()->line_amount)->toBe(6800.0);

    $prepared = app(PrepareBillingBatchAction::class)->execute($batch->fresh(), $finance);

    expect($prepared->status)->toBe(BillingBatchStatus::Prepared)
        ->and($jobCardA->fresh()->approval_status)->toBe(JobCardApprovalStatus::BillingReady)
        ->and($jobCardB->fresh()->approval_status)->toBe(JobCardApprovalStatus::BillingReady);
});

test('billing batches enforce verified-only evidence single-client scope and duplicate-billing protection', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-eligibility@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $clientA = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);
    $clientB = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);
    $otherTenant = $this->tenant();
    $otherCompany = $this->company($otherTenant, ['name' => 'Other tenant company']);
    $otherTenantClient = Client::factory()->create([
        'tenant_id' => $otherTenant->getKey(),
        'company_id' => $otherCompany->getKey(),
    ]);

    app(CreateRateAgreementAction::class)->execute([
        'client_id' => $clientA->getKey(),
        'name' => 'Client A rate',
        'effective_from' => '2026-08-01',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [[
            'equipment_reference' => 'Reach Stacker',
            'billing_unit' => 'hourly',
            'currency' => 'GHS',
            'rate' => 850.00,
        ]],
    ], $finance);

    app(CreateRateAgreementAction::class)->execute([
        'client_id' => $clientB->getKey(),
        'name' => 'Client B rate',
        'effective_from' => '2026-08-01',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [[
            'equipment_reference' => 'Reach Stacker',
            'billing_unit' => 'hourly',
            'currency' => 'GHS',
            'rate' => 1200.00,
        ]],
    ], $finance);

    $verifiedCard = financeVerifiedJobCard($clientA);
    $pendingCard = financeVerifiedJobCard($clientA, ['approval_status' => JobCardApprovalStatus::PendingVerification]);
    $otherClientCard = financeVerifiedJobCard($clientB);
    $otherTenantCard = financeVerifiedJobCard($otherTenantClient);
    $verifiedEntry = $verifiedCard->workEntries()->firstOrFail();
    $pendingEntry = $pendingCard->workEntries()->firstOrFail();
    $otherClientEntry = $otherClientCard->workEntries()->firstOrFail();
    $otherTenantEntry = $otherTenantCard->workEntries()->firstOrFail();

    $batchA = app(CreateBillingBatchAction::class)->execute([
        'client_id' => $clientA->getKey(),
        'notes' => 'Client A batch',
    ], $finance);

    expect(fn () => app(AddJobCardsToBillingBatchAction::class)->execute($batchA, [$pendingEntry->getKey()], $finance))
        ->toThrow(BusinessException::class, 'Only Accounts-reviewed Work Entries can be added to a Billing Batch.');

    expect(fn () => app(AddJobCardsToBillingBatchAction::class)->execute($batchA, [$verifiedEntry->getKey(), $otherClientEntry->getKey()], $finance))
        ->toThrow(BusinessException::class, 'A Billing Batch can only include Work Entries for its own client and company.');

    expect(fn () => app(AddJobCardsToBillingBatchAction::class)->execute($batchA, [$otherTenantEntry->getKey()], $finance))
        ->toThrow(BusinessException::class, 'A Billing Batch can only include Work Entries for its own client and company.');

    app(AddJobCardsToBillingBatchAction::class)->execute($batchA, [$verifiedEntry->getKey()], $finance);

    $batchB = app(CreateBillingBatchAction::class)->execute([
        'client_id' => $clientA->getKey(),
        'notes' => 'Second client A batch',
    ], $finance);

    expect(fn () => app(AddJobCardsToBillingBatchAction::class)->execute($batchB, [$verifiedEntry->getKey()], $finance))
        ->toThrow(BusinessException::class, 'A Work Entry can only be included in one active Billing Batch.');
});

test('finance sees eligible work entries individually and partial selection keeps remaining entries eligible', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-partial@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'legal_name' => 'GPHA',
    ]);

    app(CreateRateAgreementAction::class)->execute([
        'client_id' => $client->getKey(),
        'name' => 'GPHA Reach Stacker',
        'reference' => 'RA-GPHA-207',
        'effective_from' => '2026-08-01',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [[
            'equipment_reference' => 'Reach Stacker',
            'billing_unit' => 'hourly',
            'currency' => 'GHS',
            'rate' => 207.00,
        ]],
    ], $finance);

    $jobCard = financeVerifiedJobCard($client, ['card_date' => '2026-08-11']);
    $jobCard->workEntries()->delete();
    $entryA = JobCardWorkEntry::factory()->create([
        'job_card_id' => $jobCard->getKey(),
        'vessel' => 'MV Atlas',
        'work_area' => 'Berth 2',
        'from_time' => '08:00:00',
        'to_time' => '15:00:00',
        'normal_hours' => 7.00,
        'overtime_hours' => 0.00,
        'total_hours' => 7.00,
    ]);
    $entryB = JobCardWorkEntry::factory()->create([
        'job_card_id' => $jobCard->getKey(),
        'vessel' => 'MV Atlas',
        'work_area' => 'Berth 2',
        'from_time' => '16:00:00',
        'to_time' => '21:00:00',
        'normal_hours' => 5.00,
        'overtime_hours' => 0.00,
        'total_hours' => 5.00,
    ]);

    $batch = app(CreateBillingBatchAction::class)->execute([
        'client_id' => $client->getKey(),
        'notes' => 'GPHA partial batch',
    ], $finance);

    $eligibleBefore = app(BillingBatchEligibilityService::class)->eligibleWorkEntries($batch);

    expect($eligibleBefore->pluck('id')->all())
        ->toEqualCanonicalizing([$entryA->getKey(), $entryB->getKey()]);

    $batch = app(AddJobCardsToBillingBatchAction::class)->execute($batch, [$entryA->getKey()], $finance);

    $line = $batch->lines()->firstOrFail();

    expect((float) $line->hours)->toBe(7.0)
        ->and((float) $line->resolved_rate)->toBe(207.0)
        ->and((float) $line->line_amount)->toBe(1449.0)
        ->and($line->vessel)->toBe('MV Atlas')
        ->and($line->work_area)->toBe('Berth 2')
        ->and((float) $batch->fresh()->subtotalHours())->toBe(7.0)
        ->and($batch->fresh()->subtotalAmountsByCurrency())->toBe(['GHS' => 1449.0]);

    $eligibleAfter = app(BillingBatchEligibilityService::class)->eligibleWorkEntries($batch->fresh());

    expect($eligibleAfter->pluck('id')->all())->toEqual([$entryB->getKey()]);

    $prepared = app(PrepareBillingBatchAction::class)->execute($batch->fresh(), $finance);

    expect($prepared->status)->toBe(BillingBatchStatus::Prepared)
        ->and($jobCard->fresh()->approval_status)->toBe(JobCardApprovalStatus::Verified);

    $secondBatch = app(CreateBillingBatchAction::class)->execute([
        'client_id' => $client->getKey(),
        'notes' => 'GPHA remainder batch',
    ], $finance);

    expect(app(BillingBatchEligibilityService::class)->eligibleWorkEntries($secondBatch)->pluck('id')->all())
        ->toEqual([$entryB->getKey()]);
});

test('removing a draft billing batch line restores eligibility and prepared batches lock changes', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-remove@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);

    app(CreateRateAgreementAction::class)->execute([
        'client_id' => $client->getKey(),
        'name' => 'Draft removal tariff',
        'effective_from' => '2026-08-01',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [[
            'equipment_reference' => 'Reach Stacker',
            'billing_unit' => 'hourly',
            'currency' => 'GHS',
            'rate' => 850.00,
        ]],
    ], $finance);

    $jobCard = financeVerifiedJobCard($client);
    $workEntry = $jobCard->workEntries()->firstOrFail();

    $batch = app(CreateBillingBatchAction::class)->execute([
        'client_id' => $client->getKey(),
        'notes' => 'Mutable draft batch',
    ], $finance);

    $batch = app(AddJobCardsToBillingBatchAction::class)->execute($batch, [$workEntry->getKey()], $finance);
    $line = $batch->lines()->firstOrFail();

    app(RemoveBillingBatchLineAction::class)->execute($line, $finance);

    expect(app(BillingBatchEligibilityService::class)->eligibleWorkEntries($batch->fresh())->pluck('id')->all())
        ->toEqual([$workEntry->getKey()]);

    $batch = app(AddJobCardsToBillingBatchAction::class)->execute($batch->fresh(), [$workEntry->getKey()], $finance);
    $prepared = app(PrepareBillingBatchAction::class)->execute($batch->fresh(), $finance);

    expect(fn () => app(RemoveBillingBatchLineAction::class)->execute($prepared->lines()->firstOrFail(), $finance))
        ->toThrow(BusinessException::class, 'Prepared Billing Batches are immutable through normal workflows.');
});

test('billing batches reject mixed currency work entries and keep rate snapshots stable after rate changes', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-currency@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);

    $agreement = app(CreateRateAgreementAction::class)->execute([
        'client_id' => $client->getKey(),
        'name' => 'Mixed currency base',
        'effective_from' => '2026-08-01',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [[
            'equipment_reference' => 'Reach Stacker',
            'billing_unit' => 'hourly',
            'currency' => 'GHS',
            'rate' => 850.00,
        ]],
    ], $finance);

    $jobCardA = financeVerifiedJobCard($client, ['card_date' => '2026-08-10']);
    $jobCardB = financeVerifiedJobCard($client, ['card_date' => '2026-08-12']);
    $entryA = $jobCardA->workEntries()->firstOrFail();
    $entryB = $jobCardB->workEntries()->firstOrFail();

    $secondAgreement = RateAgreement::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'client_id' => $client->getKey(),
        'name' => 'USD future agreement',
        'effective_from' => '2026-08-01',
        'status' => RateAgreementStatus::Active,
    ]);
    $secondAgreement->lines()->create([
        'equipment_reference' => 'Forklift',
        'billing_unit' => 'hourly',
        'currency' => 'USD',
        'rate' => 120.00,
    ]);
    $jobCardB->update([
        'equipment_reference' => 'Forklift',
        'machine_number' => 'FL-02',
    ]);

    $batch = app(CreateBillingBatchAction::class)->execute([
        'client_id' => $client->getKey(),
        'notes' => 'Currency test batch',
    ], $finance);

    $batch = app(AddJobCardsToBillingBatchAction::class)->execute($batch, [$entryA->getKey()], $finance);
    $snapshottedLine = $batch->lines()->firstOrFail();

    $agreement->lines()->firstOrFail()->update(['rate' => 910.00]);

    expect((float) $snapshottedLine->fresh()->resolved_rate)->toBe(850.0)
        ->and((float) $snapshottedLine->fresh()->line_amount)->toBe(6800.0);

    expect(fn () => app(AddJobCardsToBillingBatchAction::class)->execute($batch->fresh(), [$entryB->getKey()], $finance))
        ->toThrow(BusinessException::class, 'Mixed-currency Billing Batches are not supported yet. Create a separate batch for each currency.');
});

test('missing rate rejection leaves a billing batch unchanged without creating a line', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-missing-rate@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);
    $workEntry = financeVerifiedJobCard($client, ['equipment_reference' => 'Unrated Reach Stacker'])
        ->workEntries()
        ->firstOrFail();
    $batch = app(CreateBillingBatchAction::class)->execute(['client_id' => $client->getKey()], $finance);

    expect(fn () => app(AddJobCardsToBillingBatchAction::class)->execute($batch, [$workEntry->getKey()], $finance))
        ->toThrow(BusinessException::class, 'No applicable billing rate exists for this Job Card evidence.');

    $batch->refresh();

    expect($batch->status)->toBe(BillingBatchStatus::Draft)
        ->and($batch->totalWorkEntries())->toBe(0)
        ->and($batch->subtotalHours())->toBe(0.0)
        ->and($batch->subtotalAmount())->toBe(0.0)
        ->and($batch->period_start)->toBeNull()
        ->and($batch->period_end)->toBeNull();
});

test('ambiguous rate rejection leaves a billing batch unchanged without creating a line', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-ambiguous-rate@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);

    foreach ([850.00, 900.00] as $rate) {
        $agreement = RateAgreement::factory()->create([
            'tenant_id' => $tenant->getKey(),
            'company_id' => $company->getKey(),
            'client_id' => $client->getKey(),
            'effective_from' => '2026-08-01',
            'status' => RateAgreementStatus::Active,
        ]);
        $agreement->lines()->create([
            'equipment_reference' => 'Reach Stacker',
            'billing_unit' => 'hourly',
            'currency' => 'GHS',
            'rate' => $rate,
        ]);
    }

    $workEntry = financeVerifiedJobCard($client)->workEntries()->firstOrFail();
    $batch = app(CreateBillingBatchAction::class)->execute(['client_id' => $client->getKey()], $finance);

    expect(fn () => app(AddJobCardsToBillingBatchAction::class)->execute($batch, [$workEntry->getKey()], $finance))
        ->toThrow(BusinessException::class, 'Multiple billing rates match this Job Card evidence. Resolve the ambiguity before billing.');

    $batch->refresh();

    expect($batch->status)->toBe(BillingBatchStatus::Draft)
        ->and($batch->totalWorkEntries())->toBe(0)
        ->and($batch->subtotalHours())->toBe(0.0)
        ->and($batch->subtotalAmount())->toBe(0.0)
        ->and($batch->period_start)->toBeNull()
        ->and($batch->period_end)->toBeNull();
});

test('billing batch add reviewed work entries action notifies instead of surfacing missing rates as a Livewire exception', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-rate-notification@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    $this->actingAs($finance);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);
    $workEntry = financeVerifiedJobCard($client, ['equipment_reference' => 'Unrated Reach Stacker'])
        ->workEntries()
        ->firstOrFail();
    $batch = app(CreateBillingBatchAction::class)->execute(['client_id' => $client->getKey()], $finance);

    Livewire::test(ViewBillingBatch::class, ['record' => $batch->getKey()])
        ->callAction('addReviewedEvidence', ['work_entry_ids' => [$workEntry->getKey()]])
        ->assertNotified(
            Notification::make()
                ->danger()
                ->title('Work Entry could not be added')
                ->body('No applicable billing rate exists for this Job Card evidence.'),
        );

    $batch->refresh();

    expect($batch->status)->toBe(BillingBatchStatus::Draft)
        ->and($batch->totalWorkEntries())->toBe(0)
        ->and($batch->subtotalHours())->toBe(0.0)
        ->and($batch->subtotalAmount())->toBe(0.0);
});

test('billing batch add reviewed work entries action does not swallow unexpected exceptions', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-unexpected-rate-error@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    $this->actingAs($finance);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);
    $batch = app(CreateBillingBatchAction::class)->execute(['client_id' => $client->getKey()], $finance);
    $action = Mockery::mock(AddJobCardsToBillingBatchAction::class);
    $action->shouldReceive('execute')->once()->andThrow(new RuntimeException('Unexpected persistence failure.'));
    app()->instance(AddJobCardsToBillingBatchAction::class, $action);

    expect(fn () => Livewire::test(ViewBillingBatch::class, ['record' => $batch->getKey()])
        ->call('addReviewedEvidence', ['work_entry_ids' => [1]]))
        ->toThrow(RuntimeException::class, 'Unexpected persistence failure.');
});

test('finance resources are accessible to finance users and blocked for operations users', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-ui@example.test'], [RoleName::FinanceManager->value]);
    $operations = $this->tenantUser($tenant, ['email' => 'ops-ui@example.test'], [RoleName::OperationsManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    $operations->companies()->sync([$company->getKey()]);

    $this->actingAs($finance);
    session(['active_company_id' => $company->getKey()]);

    $this->get(RateAgreementResource::getUrl('index'))->assertOk();
    $this->get(BillingBatchResource::getUrl('index'))->assertOk();

    $this->actingAs($operations);
    session(['active_company_id' => $company->getKey()]);

    $this->get(RateAgreementResource::getUrl('index'))->assertForbidden();
    $this->get(BillingBatchResource::getUrl('index'))->assertForbidden();
});
