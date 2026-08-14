<?php

use App\Administration\Enums\RoleName;
use App\Core\Shared\Exceptions\BusinessException;
use App\CRM\Enums\ClientSiteStatus;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use App\Models\User;
use App\Operations\Actions\JobCards\ApproveJobCardAction;
use App\Operations\Actions\JobCards\SubmitJobCardAction;
use App\Operations\Actions\Jobs\ApproveJobAction;
use App\Operations\Actions\Jobs\CancelJobAction;
use App\Operations\Actions\Jobs\CompleteJobAction;
use App\Operations\Actions\Jobs\CreateJobAction;
use App\Operations\Actions\Jobs\ScheduleJobAction;
use App\Operations\Actions\Jobs\StartJobAction;
use App\Operations\Actions\Jobs\SubmitJobForApprovalAction;
use App\Operations\Actions\Jobs\UpdateJobAction;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use Illuminate\Support\Facades\Gate;

test('job creation is tenant scoped and validates site ownership', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $otherTenant = $this->tenant();
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $client = Client::factory()->create(['tenant_id' => $tenant->getKey()]);
    $site = ClientSite::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'client_id' => $client->getKey(),
        'status' => ClientSiteStatus::Active,
    ]);
    $foreignSite = ClientSite::factory()->create([
        'tenant_id' => $otherTenant->getKey(),
    ]);

    $job = app(CreateJobAction::class)->execute([
        'client_id' => $client->getKey(),
        'client_site_id' => $site->getKey(),
        'title' => 'Tower servicing',
        'priority' => 'normal',
        'planned_start_date' => now()->toDateString(),
        'planned_end_date' => now()->addDay()->toDateString(),
    ], $actor);

    expect($job->tenant_id)->toBe($tenant->getKey())
        ->and($job->company_id)->toBe($client->company_id)
        ->and($job->job_number)->toStartWith('JOB-')
        ->and($job->currency)->toBe($client->company?->currency)
        ->and($job->planned_start_date?->toDateString())->toBe(now()->toDateString())
        ->and($job->planned_end_date?->toDateString())->toBe(now()->addDay()->toDateString())
        ->and($job->created_by)->toBe($actor->getKey())
        ->and($job->updated_by)->toBe($actor->getKey());

    expect(fn () => app(CreateJobAction::class)->execute([
        'client_id' => $client->getKey(),
        'client_site_id' => $foreignSite->getKey(),
        'title' => 'Invalid site job',
        'priority' => 'normal',
    ], $actor))->toThrow(BusinessException::class);
});

test('job workflow transitions set approval completion and cancellation metadata', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $operator = User::factory()->create(['tenant_id' => $tenant->getKey()]);
    $client = Client::factory()->create(['tenant_id' => $tenant->getKey()]);
    $site = ClientSite::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'client_id' => $client->getKey(),
        'status' => ClientSiteStatus::Active,
    ]);
    $operator->companies()->sync([$client->company_id]);

    $job = app(CreateJobAction::class)->execute([
        'client_id' => $client->getKey(),
        'client_site_id' => $site->getKey(),
        'title' => 'Generator installation',
        'priority' => 'high',
        'planned_start_date' => now()->toDateString(),
        'planned_end_date' => now()->addDay()->toDateString(),
        'assigned_operator_id' => $operator->getKey(),
    ], $actor);

    $job = app(SubmitJobForApprovalAction::class)->execute($job, $actor);
    expect($job->status)->toBe(JobStatus::PendingApproval);

    $job = app(ApproveJobAction::class)->execute($job, $actor);
    expect($job->status)->toBe(JobStatus::Approved)
        ->and($job->approved_by)->toBe($actor->getKey())
        ->and($job->approved_at)->not->toBeNull();

    $job = app(ScheduleJobAction::class)->execute($job, $actor);
    $job = app(StartJobAction::class)->execute($job, $actor);
    $jobCard = $job->jobCards()->firstOrFail();
    app(SubmitJobCardAction::class)->execute($jobCard, $actor);
    app(ApproveJobCardAction::class)->execute($jobCard->fresh(), $actor);
    $job = app(CompleteJobAction::class)->execute($job, $actor, [
        'actual_end_date' => now()->addHours(2),
    ]);

    expect($job->status)->toBe(JobStatus::Completed)
        ->and($job->completed_by)->toBe($actor->getKey())
        ->and($job->completed_at)->not->toBeNull();
});

test('job cancellation requires a reason and tenant isolation is enforced', function () {
    $this->seedAccessControl();

    $tenantA = $this->tenant();
    $tenantB = $this->tenant();
    $actorA = $this->tenantUser($tenantA, [], [RoleName::CompanyAdministrator->value]);
    $actorB = $this->tenantUser($tenantB, [], [RoleName::CompanyAdministrator->value]);
    $client = Client::factory()->create(['tenant_id' => $tenantA->getKey()]);
    $job = Job::factory()->create([
        'tenant_id' => $tenantA->getKey(),
        'client_id' => $client->getKey(),
        'status' => JobStatus::Approved,
    ]);

    expect(Gate::forUser($actorB)->allows('view', $job))->toBeFalse();
    expect(fn () => app(CancelJobAction::class)->execute($job, $actorA, []))->toThrow(BusinessException::class);

    $cancelled = app(CancelJobAction::class)->execute($job, $actorA, [
        'cancellation_reason' => 'Client requested deferral',
    ]);

    expect($cancelled->status)->toBe(JobStatus::Cancelled)
        ->and($cancelled->cancellation_reason)->toBe('Client requested deferral');
});

test('inactive client sites can not be attached to a job', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $client = Client::factory()->create(['tenant_id' => $tenant->getKey()]);
    $inactiveSite = ClientSite::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'client_id' => $client->getKey(),
        'status' => ClientSiteStatus::Inactive,
    ]);

    expect(fn () => app(CreateJobAction::class)->execute([
        'client_id' => $client->getKey(),
        'client_site_id' => $inactiveSite->getKey(),
        'title' => 'Inactive site job',
        'priority' => 'normal',
    ], $actor))->toThrow(BusinessException::class, 'Only active client sites can be attached to a job.');
});

test('job audit fields can not be mass assigned by untrusted input', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $otherUser = User::factory()->create(['tenant_id' => $tenant->getKey()]);
    $actor->companies()->sync([$company->getKey()]);
    $otherUser->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);
    $site = ClientSite::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'client_id' => $client->getKey(),
        'status' => ClientSiteStatus::Active,
    ]);

    $job = app(CreateJobAction::class)->execute([
        'client_id' => $client->getKey(),
        'client_site_id' => $site->getKey(),
        'title' => 'Audit hardening',
        'priority' => 'normal',
        'created_by' => $otherUser->getKey(),
        'updated_by' => $otherUser->getKey(),
    ], $actor);

    expect($job->created_by)->toBe($actor->getKey())
        ->and($job->updated_by)->toBe($actor->getKey());

    $updated = app(UpdateJobAction::class)->execute($job, [
        'title' => 'Audit hardening updated',
        'created_by' => $otherUser->getKey(),
        'updated_by' => $otherUser->getKey(),
    ], $actor);

    expect($updated->created_by)->toBe($actor->getKey())
        ->and($updated->updated_by)->toBe($actor->getKey());
});

test('trusted job fields are derived from company context instead of request input', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['currency' => 'USD']);
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics', 'currency' => 'NGN']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);

    $job = app(CreateJobAction::class)->execute([
        'client_id' => $client->getKey(),
        'title' => 'Trusted contract job',
        'priority' => 'normal',
        'job_number' => 'MANUAL-OVERRIDE',
        'currency' => 'EUR',
    ], $actor);

    expect($job->job_number)->not->toBe('MANUAL-OVERRIDE')
        ->and($job->job_number)->toStartWith('JOB-')
        ->and($job->currency)->toBe('NGN');
});

test('updated_by changes on edit while created_by remains unchanged', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $creator = $this->tenantUser($tenant, ['email' => 'creator@example.test'], [RoleName::CompanyAdministrator->value]);
    $editor = $this->tenantUser($tenant, ['email' => 'editor@example.test'], [RoleName::CompanyAdministrator->value]);
    $creator->companies()->sync([$company->getKey()]);
    $editor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);
    $site = ClientSite::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'client_id' => $client->getKey(),
        'status' => ClientSiteStatus::Active,
    ]);

    $job = app(CreateJobAction::class)->execute([
        'client_id' => $client->getKey(),
        'client_site_id' => $site->getKey(),
        'title' => 'Original title',
        'priority' => 'normal',
    ], $creator);

    $updated = app(UpdateJobAction::class)->execute($job, [
        'client_id' => $client->getKey(),
        'client_site_id' => $site->getKey(),
        'title' => 'Updated title',
        'priority' => 'high',
    ], $editor);

    expect($updated->created_by)->toBe($creator->getKey())
        ->and($updated->updated_by)->toBe($editor->getKey())
        ->and($updated->title)->toBe('Updated title');
});

test('legacy jobs with null audit fields remain readable', function () {
    $job = Job::factory()->create();

    $job->forceFill([
        'created_by' => null,
        'updated_by' => null,
    ])->saveQuietly();

    $legacyJob = $job->fresh();

    expect($legacyJob->created_by)->toBeNull()
        ->and($legacyJob->updated_by)->toBeNull()
        ->and($legacyJob->title)->not->toBe('');
});

test('cross company job creation is rejected inside the same tenant', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $actor = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    $actor->companies()->sync([$companyA->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyB->getKey(),
    ]);

    expect(fn () => app(CreateJobAction::class)->execute([
        'client_id' => $client->getKey(),
        'title' => 'Foreign company job',
        'priority' => 'normal',
    ], $actor))->toThrow(BusinessException::class);
});

test('platform session can not create operational jobs without tenant context', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::SuperAdministrator->value]);
    $actor->companies()->sync([$company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);

    expect(fn () => app(CreateJobAction::class)->execute([
        'client_id' => $client->getKey(),
        'title' => 'Platform job attempt',
        'priority' => 'normal',
    ], $actor))->toThrow(BusinessException::class, 'Select an active company before creating a job.');
});

test('all current job form fields persist successfully', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $operator = User::factory()->create(['tenant_id' => $tenant->getKey()]);
    $actor->companies()->sync([$company->getKey()]);
    $operator->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);
    $site = ClientSite::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'client_id' => $client->getKey(),
        'status' => ClientSiteStatus::Active,
    ]);

    $job = app(CreateJobAction::class)->execute([
        'client_id' => $client->getKey(),
        'client_site_id' => $site->getKey(),
        'title' => 'Operational engagement for GPHA',
        'job_reference' => 'KAD-2026-021',
        'vessel' => 'MV Meridian',
        'work_area' => 'Jubilee Terminal',
        'equipment_requirement' => 'Forklift FL-18',
        'assigned_operator_id' => $operator->getKey(),
        'shift' => 'night',
        'description' => 'Night shift plant support',
        'status' => 'draft',
        'priority' => 'high',
        'requested_start_date' => '2026-07-23',
        'planned_start_date' => '2026-07-23',
        'planned_start_time' => '19:00',
        'planned_end_date' => '2026-07-24',
        'planned_end_time' => '07:00',
        'client_reference' => 'GPHA-OPS-992',
        'internal_reference' => 'INT-OPS-442',
        'estimated_value' => 125000.50,
    ], $actor);

    expect($job->tenant_id)->toBe($tenant->getKey())
        ->and($job->company_id)->toBe($company->getKey())
        ->and($job->client_id)->toBe($client->getKey())
        ->and($job->client_site_id)->toBe($site->getKey())
        ->and($job->currency)->toBe($company->currency)
        ->and($job->job_reference)->toBe('KAD-2026-021')
        ->and($job->vessel)->toBe('MV Meridian')
        ->and($job->work_area)->toBe('Jubilee Terminal')
        ->and($job->equipment_requirement)->toBe('Forklift FL-18')
        ->and($job->assigned_operator_id)->toBe($operator->getKey())
        ->and($job->shift?->value)->toBe('night')
        ->and($job->requested_start_date?->toDateString())->toBe('2026-07-23')
        ->and($job->planned_start_date?->toDateString())->toBe('2026-07-23')
        ->and((string) $job->planned_start_time)->toContain('19:00')
        ->and($job->planned_end_date?->toDateString())->toBe('2026-07-24')
        ->and((string) $job->planned_end_time)->toContain('07:00')
        ->and($job->client_reference)->toBe('GPHA-OPS-992')
        ->and($job->customer_reference)->toBe('GPHA-OPS-992')
        ->and($job->internal_reference)->toBe('INT-OPS-442')
        ->and($job->purchase_order_number)->toBe('INT-OPS-442')
        ->and((float) $job->estimated_value)->toBe(125000.5)
        ->and((float) $job->estimated_amount)->toBe(125000.5)
        ->and($job->scheduled_start_date?->toDateString())->toBe('2026-07-23')
        ->and($job->scheduled_end_date?->toDateString())->toBe('2026-07-24')
        ->and($job->assigned_to)->toBe($operator->getKey())
        ->and($job->notes)->toBe('Night shift plant support');
});

test('draft job can be created without operator and creator is not auto assigned as operator', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);

    $job = app(CreateJobAction::class)->execute([
        'client_id' => $client->getKey(),
        'title' => 'Draft without operator',
        'priority' => 'normal',
        'status' => 'draft',
    ], $actor);

    expect($job->assigned_operator_id)->toBeNull()
        ->and($job->assigned_operator_name)->toBeNull()
        ->and($job->created_by)->toBe($actor->getKey());
});

test('planner can use an external operator name and the creator remains the audit actor', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);

    $job = app(CreateJobAction::class)->execute([
        'client_id' => $client->getKey(),
        'title' => 'External operator planning',
        'priority' => 'normal',
        'assigned_operator_name' => '  Kofi   Asante  ',
    ], $actor);

    expect($job->assigned_operator_id)->toBeNull()
        ->and($job->assigned_operator_name)->toBe('Kofi Asante')
        ->and($job->plannedOperatorName())->toBe('Kofi Asante')
        ->and($job->created_by)->toBe($actor->getKey());
});

test('cross company operators are rejected and linked plus external operators cannot conflict', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $operator = User::factory()->create(['tenant_id' => $tenant->getKey()]);
    $actor->companies()->sync([$companyA->getKey()]);
    $operator->companies()->sync([$companyB->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyA->getKey(),
    ]);

    expect(fn () => app(CreateJobAction::class)->execute([
        'client_id' => $client->getKey(),
        'title' => 'Invalid operator company',
        'priority' => 'normal',
        'assigned_operator_id' => $operator->getKey(),
    ], $actor))->toThrow(BusinessException::class, 'The selected operator is not authorized for the active company.');

    expect(fn () => app(CreateJobAction::class)->execute([
        'client_id' => $client->getKey(),
        'title' => 'Conflicting operator fields',
        'priority' => 'normal',
        'assigned_operator_name' => 'Kofi Asante',
        'assigned_operator_id' => $actor->getKey(),
    ], $actor))->toThrow(BusinessException::class, 'Select a company operator or enter an external operator name, not both.');
});
