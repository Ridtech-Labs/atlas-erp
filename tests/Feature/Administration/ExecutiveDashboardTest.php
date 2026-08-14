<?php

use App\Administration\Enums\RoleName;
use App\Core\Administration\Services\ExecutiveDashboardService;
use App\CRM\Enums\ClientStatus;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use App\Models\User;
use App\Operations\Enums\JobPriority;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use Carbon\CarbonImmutable;
use Spatie\Activitylog\Models\Activity;

beforeEach(function () {
    CarbonImmutable::setTestNow('2026-07-22 09:00:00');
});

afterEach(function () {
    CarbonImmutable::setTestNow();
});

test('authenticated company administrator can access the executive dashboard', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Global Limited']);
    $user = $this->actingAsCompanyAdministrator($tenant, ['first_name' => 'Ridwan']);

    $this->get('/admin')
        ->assertOk()
        ->assertSee('Good morning, Ridwan')
        ->assertSee('Kadmay Global Limited')
        ->assertSee('Approval Queue')
        ->assertSee('Quick Actions')
        ->assertSee('Recent Activity');
});

test('platform super administrator sees platform dashboard by default', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Global Limited']);
    $user = $this->tenantUser($tenant, ['first_name' => 'Ridwan'], [RoleName::SuperAdministrator->value]);

    Activity::query()->create([
        'log_name' => 'tenancy',
        'description' => 'Tenant activated',
        'causer_type' => User::class,
        'causer_id' => $user->getKey(),
        'event' => 'tenant.activated',
        'properties' => ['tenant_id' => $tenant->getKey()],
    ]);

    $this->get('/admin')
        ->assertRedirect('/admin/login');

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Platform Administration')
        ->assertSee('Platform administration session')
        ->assertSee('Tenants')
        ->assertSee('Platform Users')
        ->assertDontSee('Kadmay Global Limited ·')
        ->assertDontSee('Upcoming Work')
        ->assertDontSee('Approval Queue')
        ->assertDontSee('New Client');

    $data = app(ExecutiveDashboardService::class)->forUser($user);

    expect($data['context_label'])->toBe('Platform Administration')
        ->and($data['tenant'])->toBeNull()
        ->and($data['can']['view_clients'])->toBeFalse()
        ->and($data['can']['view_jobs'])->toBeFalse();
});

test('guest users are redirected to the admin login page', function () {
    $this->get('/admin')->assertRedirect('/admin/login');
});

test('users without dashboard permission can not access the executive dashboard', function () {
    $tenant = $this->tenant();
    $user = $this->tenantUser($tenant);

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

test('dashboard metrics and attention items are accurate and tenant isolated', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Atlas Demo Company']);
    $otherTenant = $this->tenant(['name' => 'Other Company']);
    $user = $this->actingAsCompanyAdministrator($tenant, ['first_name' => 'Atlas']);

    $clientA = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'legal_name' => 'Atlas Active Client One',
        'status' => ClientStatus::Active,
        'email' => 'one@example.test',
        'phone' => '12345',
        'physical_address' => 'Accra',
    ]);
    $clientB = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'legal_name' => 'Atlas Active Client Two',
        'status' => ClientStatus::Active,
        'email' => 'two@example.test',
        'phone' => '12345',
        'physical_address' => 'Tema',
    ]);
    Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'legal_name' => 'Atlas Inactive Client',
        'status' => ClientStatus::Inactive,
        'email' => 'inactive@example.test',
        'phone' => '12345',
        'physical_address' => 'Takoradi',
    ]);
    $incompleteClient = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'legal_name' => 'Incomplete Client Alpha',
        'trading_name' => null,
        'status' => ClientStatus::Active,
        'email' => null,
        'phone' => null,
        'physical_address' => null,
    ]);
    Client::factory()->create([
        'tenant_id' => $otherTenant->getKey(),
        'legal_name' => 'Other Tenant Client',
        'status' => ClientStatus::Active,
    ]);

    $siteA = ClientSite::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'client_id' => $clientA->getKey(),
        'name' => 'Atlas Site A',
    ]);
    $siteB = ClientSite::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'client_id' => $clientB->getKey(),
        'name' => 'Atlas Site B',
    ]);
    $otherSite = ClientSite::factory()->create([
        'tenant_id' => $otherTenant->getKey(),
        'client_id' => Client::factory()->create(['tenant_id' => $otherTenant->getKey()])->getKey(),
        'name' => 'Other Site',
    ]);

    Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'client_id' => $clientA->getKey(),
        'client_site_id' => $siteA->getKey(),
        'title' => 'Pending Approval Alpha',
        'job_number' => 'JOB-PENDING-001',
        'status' => JobStatus::PendingApproval,
        'planned_start_date' => CarbonImmutable::today()->toDateString(),
        'planned_end_date' => CarbonImmutable::today()->addDay()->toDateString(),
        'priority' => JobPriority::High,
    ]);
    Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'client_id' => $clientA->getKey(),
        'client_site_id' => $siteA->getKey(),
        'title' => 'Scheduled Today Alpha',
        'job_number' => 'JOB-TODAY-001',
        'status' => JobStatus::Scheduled,
        'planned_start_date' => CarbonImmutable::today()->toDateString(),
        'planned_end_date' => CarbonImmutable::today()->addDay()->toDateString(),
        'priority' => JobPriority::Normal,
    ]);
    Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'client_id' => $clientB->getKey(),
        'client_site_id' => $siteB->getKey(),
        'title' => 'Overdue In Progress Alpha',
        'job_number' => 'JOB-OVERDUE-001',
        'status' => JobStatus::InProgress,
        'planned_start_date' => CarbonImmutable::today()->subDays(3)->toDateString(),
        'planned_end_date' => CarbonImmutable::today()->subDay()->toDateString(),
        'priority' => JobPriority::Urgent,
    ]);
    Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'client_id' => $clientB->getKey(),
        'client_site_id' => $siteB->getKey(),
        'title' => 'Completed This Month Alpha',
        'job_number' => 'JOB-COMPLETE-001',
        'status' => JobStatus::Completed,
        'planned_start_date' => CarbonImmutable::today()->subDays(4)->toDateString(),
        'planned_end_date' => CarbonImmutable::today()->subDays(2)->toDateString(),
        'completed_at' => CarbonImmutable::today()->subDay(),
        'priority' => JobPriority::Normal,
    ]);
    Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'client_id' => $clientA->getKey(),
        'client_site_id' => $siteA->getKey(),
        'title' => 'Unscheduled Approval Alpha',
        'job_number' => 'JOB-UNSCHEDULED-001',
        'status' => JobStatus::Approved,
        'planned_start_date' => null,
        'planned_end_date' => null,
        'priority' => JobPriority::High,
    ]);
    Job::factory()->create([
        'tenant_id' => $otherTenant->getKey(),
        'client_id' => Client::factory()->create(['tenant_id' => $otherTenant->getKey()])->getKey(),
        'client_site_id' => $otherSite->getKey(),
        'title' => 'Other Tenant Overdue Job',
        'job_number' => 'JOB-OTHER-001',
        'status' => JobStatus::InProgress,
        'planned_start_date' => CarbonImmutable::today()->subDays(2)->toDateString(),
        'planned_end_date' => CarbonImmutable::today()->subDay()->toDateString(),
        'priority' => JobPriority::Urgent,
    ]);

    Activity::query()->create([
        'log_name' => 'jobs',
        'description' => 'Tenant activity only',
        'subject_type' => Job::class,
        'subject_id' => Job::query()->where('tenant_id', $tenant->getKey())->firstOrFail()->getKey(),
        'causer_type' => User::class,
        'causer_id' => $user->getKey(),
        'event' => 'job.updated',
        'properties' => ['tenant_id' => $tenant->getKey()],
    ]);
    Activity::query()->create([
        'log_name' => 'jobs',
        'description' => 'Other tenant activity',
        'subject_type' => Job::class,
        'subject_id' => Job::query()->where('tenant_id', $otherTenant->getKey())->firstOrFail()->getKey(),
        'causer_type' => User::class,
        'causer_id' => User::factory()->create(['tenant_id' => $otherTenant->getKey()])->getKey(),
        'event' => 'job.updated',
        'properties' => ['tenant_id' => $otherTenant->getKey()],
    ]);

    $data = app(ExecutiveDashboardService::class)->forUser($user);
    $kpis = collect($data['kpis'])->keyBy('label');

    expect($kpis['Total Clients']['value'])->toBe('4')
        ->and($kpis['Jobs Today']['value'])->toBe('2')
        ->and($kpis['Pending Approvals']['value'])->toBe('1')
        ->and($kpis['Active Jobs']['value'])->toBe('2')
        ->and($kpis['Completed MTD']['value'])->toBe('1')
        ->and($kpis['Revenue MTD']['value'])->toBe('—')
        ->and($data['recent_activity']['restricted'])->toBeFalse();

    $this->get('/admin')
        ->assertOk()
        ->assertSee('Pending Approval Alpha')
        ->assertSee('Overdue In Progress Alpha')
        ->assertSee('Unscheduled Approval Alpha')
        ->assertSee('Incomplete Client Alpha')
        ->assertDontSee('Other Tenant Overdue Job')
        ->assertDontSee('Other Tenant Client')
        ->assertDontSee('Other tenant activity');
});

test('dashboard shows intentional empty states when a company has no operational data', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $this->actingAsCompanyAdministrator($tenant);

    $this->get('/admin')
        ->assertOk()
        ->assertSee('No work is scheduled yet')
        ->assertSee('Approval queue is clear')
        ->assertSee('No system alerts');
});

test('dashboard actions are permission aware for restricted roles', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $this->actingAsRole(RoleName::StandardUser->value, $tenant);

    $this->get('/admin')
        ->assertOk()
        ->assertSee('No dashboard actions available')
        ->assertSee('Job visibility is restricted')
        ->assertSee('Approval visibility is restricted')
        ->assertDontSee('New Client')
        ->assertDontSee('New Job');
});
