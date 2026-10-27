<?php

declare(strict_types=1);

use App\Administration\Enums\RoleName;
use App\Core\Administration\Services\ExecutiveDashboardService;
use App\Core\Tenancy\Support\ActiveCompanyResolver;
use App\CRM\Actions\Clients\CreateClientAction;
use App\CRM\Enums\ClientSiteStatus;
use App\CRM\Models\Client;
use App\CRM\Models\ClientContact;
use App\CRM\Models\ClientSite;
use App\Operations\Actions\Jobs\CreateJobAction;
use App\Operations\Models\Job;
use Illuminate\Support\Facades\Gate;

test('tenant has many companies and company belongs to tenant', function () {
    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $logistics = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $engineering = $this->company($tenant, ['name' => 'Kadmay Engineering']);

    expect($tenant->companies()->pluck('id')->all())
        ->toContain($logistics->getKey(), $engineering->getKey())
        ->and($logistics->tenant_id)->toBe($tenant->getKey())
        ->and($engineering->tenant_id)->toBe($tenant->getKey());
});

test('user can be assigned to one or more companies in the same tenant', function () {
    $tenant = $this->tenant();
    $alpha = $this->company($tenant, ['name' => 'Alpha']);
    $beta = $this->company($tenant, ['name' => 'Beta']);
    $user = $this->tenantUser($tenant);

    $user->companies()->sync([$alpha->getKey(), $beta->getKey()]);

    expect($user->companies()->pluck('companies.id')->all())
        ->toContain($alpha->getKey(), $beta->getKey());
});

test('active company resolves for a single authorized company and ignores an unauthorized company from another tenant', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $otherTenant = $this->tenant(['name' => 'Other Holdings']);
    $otherCompany = $this->company($otherTenant, ['name' => 'Other Logistics']);
    $user = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);

    $user->companies()->sync([$company->getKey()]);

    $resolver = app(ActiveCompanyResolver::class);

    expect($resolver->resolveFor($user)?->getKey())->toBe($company->getKey());

    session(['active_company_id' => $otherCompany->getKey()]);

    expect($resolver->resolveFor($user)?->getKey())->toBe($company->getKey());
});

test('authorized user can switch active company within the current tenant', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $user = $this->actingAsCompanyAdministrator($tenant);
    $user->companies()->sync([$companyA->getKey(), $companyB->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    $this->post(route('atlas.company-context.switch'), [
        'company_id' => $companyB->getKey(),
    ])->assertRedirect();

    $this->assertEquals($companyB->getKey(), session('active_company_id'));
});

test('company administrators only receive authorized companies from explicit memberships', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $user = $this->actingAsCompanyAdministrator($tenant);
    $user->companies()->sync([$companyA->getKey()]);

    $authorizedCompanies = app(ActiveCompanyResolver::class)
        ->authorizedCompaniesFor($user)
        ->pluck('name')
        ->all();

    expect($authorizedCompanies)
        ->toBe([$companyA->name])
        ->not->toContain($companyB->name);
});

test('user can not switch to an unauthorized company', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $user = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    $user->companies()->sync([$companyA->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    $this->actingAs($user)
        ->post(route('atlas.company-context.switch'), [
            'company_id' => $companyB->getKey(),
        ])
        ->assertForbidden();
});

test('platform session has no implicit company context', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $user = $this->tenantUser($tenant, ['first_name' => 'Ridwan'], [RoleName::SuperAdministrator->value]);

    $user->companies()->sync([$company->getKey()]);

    expect(app(ActiveCompanyResolver::class)->resolveFor($user))->toBeNull();
});

test('a super administrator can select only companies in an entered support tenant workspace', function () {
    $this->seedAccessControl();

    $homeTenant = $this->tenant(['name' => 'Atlas Platform']);
    $homeCompany = $this->company($homeTenant, ['name' => 'Atlas Platform Company']);
    $supportTenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $supportCompanyA = $this->company($supportTenant, ['name' => 'Kadmay Logistics']);
    $supportCompanyB = $this->company($supportTenant, ['name' => 'Kadmay Marine']);
    $otherTenant = $this->tenant(['name' => 'Other Holdings']);
    $otherCompany = $this->company($otherTenant, ['name' => 'Other Logistics']);
    $user = $this->tenantUser($homeTenant, [], [RoleName::SuperAdministrator->value]);
    $user->companies()->sync([$homeCompany->getKey()]);

    $this->actingAs($user)
        ->post(route('atlas.support-workspace.enter', $supportTenant))
        ->assertRedirect('/admin')
        ->assertSessionHas('support_tenant_id', $supportTenant->getKey());

    $resolver = app(ActiveCompanyResolver::class);

    expect($resolver->authorizedCompaniesFor($user)->pluck('id')->all())
        ->toBe([$supportCompanyA->getKey(), $supportCompanyB->getKey()])
        ->and($resolver->userCanAccessCompany($user, $supportCompanyA))->toBeTrue()
        ->and($resolver->userCanAccessCompany($user, $otherCompany))->toBeFalse();

    $this->post(route('atlas.company-context.switch'), ['company_id' => $supportCompanyB->getKey()])
        ->assertRedirect();

    expect($resolver->resolveFor($user)?->getKey())->toBe($supportCompanyB->getKey());

    $this->post(route('atlas.company-context.switch'), ['company_id' => $otherCompany->getKey()])
        ->assertNotFound();
});

test('client creation assigns the active company and matching tenant id', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);

    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = app(CreateClientAction::class)->execute([
        'legal_name' => 'Tema Oil Refinery',
        'client_type' => 'corporate',
        'status' => 'active',
        'country' => 'Ghana',
    ], $actor);

    expect($client->tenant_id)->toBe($tenant->getKey())
        ->and($client->company_id)->toBe($company->getKey());
});

test('job creation assigns the active company and matching tenant id', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);
    $site = ClientSite::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'client_id' => $client->getKey(),
    ]);

    $job = app(CreateJobAction::class)->execute([
        'client_id' => $client->getKey(),
        'client_site_id' => $site->getKey(),
        'title' => 'Crane Hire',
        'priority' => 'normal',
        'planned_start_date' => now()->toDateString(),
        'planned_end_date' => now()->addDay()->toDateString(),
    ], $actor);

    expect($job->tenant_id)->toBe($tenant->getKey())
        ->and($job->company_id)->toBe($company->getKey());
});

test('cross company client and job access is denied inside the same tenant', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $user = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    $user->companies()->sync([$companyA->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    $clientB = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyB->getKey(),
    ]);
    $jobB = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyB->getKey(),
        'client_id' => $clientB->getKey(),
    ]);

    expect(Gate::forUser($user)->allows('view', $clientB))->toBeFalse()
        ->and(Gate::forUser($user)->allows('view', $jobB))->toBeFalse();
});

test('cross company client contacts and sites are denied inside the same tenant', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $user = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    $user->companies()->sync([$companyA->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    $clientB = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyB->getKey(),
    ]);
    $contactB = ClientContact::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyB->getKey(),
        'client_id' => $clientB->getKey(),
    ]);
    $siteB = ClientSite::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyB->getKey(),
        'client_id' => $clientB->getKey(),
    ]);

    expect(Gate::forUser($user)->allows('view', $contactB))->toBeFalse()
        ->and(Gate::forUser($user)->allows('view', $siteB))->toBeFalse();
});

test('dashboard metrics scope to the active company', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $user = $this->tenantUser($tenant, ['first_name' => 'Ridwan'], [RoleName::CompanyAdministrator->value]);

    $user->companies()->sync([$companyA->getKey(), $companyB->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyA->getKey(),
        'status' => 'active',
    ]);
    Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyB->getKey(),
        'status' => 'active',
    ]);

    $clientA = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyA->getKey(),
        'status' => 'active',
    ]);
    $siteA = ClientSite::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'client_id' => $clientA->getKey(),
        'status' => ClientSiteStatus::Active,
    ]);

    Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyA->getKey(),
        'client_id' => $clientA->getKey(),
        'client_site_id' => $siteA->getKey(),
        'status' => 'scheduled',
        'planned_start_date' => now()->toDateString(),
        'planned_end_date' => now()->addDay()->toDateString(),
    ]);

    $data = app(ExecutiveDashboardService::class)->forUser($user);
    $kpis = collect($data['kpis'])->keyBy('label');

    expect($data['context_label'])->toBe('Kadmay Logistics')
        ->and($kpis['Total Clients']['value'])->toBe('2')
        ->and($kpis['Jobs Today']['value'])->toBe('1');
});

test('client site inherits tenant and company ownership from its client', function () {
    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);

    $site = ClientSite::factory()->create([
        'client_id' => $client->getKey(),
    ]);

    expect($site->tenant_id)->toBe($client->tenant_id)
        ->and($site->company_id)->toBe($client->company_id)
        ->and($site->status)->toBe(ClientSiteStatus::Active);
});
