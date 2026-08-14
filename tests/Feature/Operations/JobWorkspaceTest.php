<?php

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Resources\Jobs\JobResource;
use App\Core\Administration\Filament\Widgets\JobWorkspaceWidget;
use App\Core\Shared\Exceptions\BusinessException;
use App\CRM\Models\Client;
use App\Models\User;
use App\Operations\Actions\JobCards\ApproveJobCardAction;
use App\Operations\Actions\JobCards\ReturnJobCardAction;
use App\Operations\Actions\JobCards\SubmitJobCardAction;
use App\Operations\Actions\Jobs\CompleteJobAction;
use App\Operations\Actions\Jobs\HoldJobAction;
use App\Operations\Actions\Jobs\StartJobAction;
use App\Operations\Actions\Jobs\UpdateJobAction;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Enums\JobShift;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use Livewire\Livewire;

test('authorized user can view the job workspace', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $user = $this->actingAsCompanyAdministrator($tenant);
    $user->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
    ]);

    $this->get(JobResource::getUrl('view', ['record' => $job]))
        ->assertOk()
        ->assertSee($job->job_number)
        ->assertSee('Operational summary, commercial context, and workflow progress.');
});

test('job list and workspace display the same status after workflow transitions', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'title' => 'Bulk discharge support',
    ]);

    app(HoldJobAction::class)->execute($job, $actor);
    $job = $job->fresh();

    $this->get(JobResource::getUrl('index'))
        ->assertOk()
        ->assertSee('Bulk discharge support')
        ->assertSee('On Hold');

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('ON HOLD')
        ->assertSee('Resume Job');
});

test('cross company user cannot view the job workspace', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $user = $this->actingAsCompanyAdministrator($tenant);
    $user->companies()->sync([$companyA->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyB->getKey(),
    ]);

    $this->get(JobResource::getUrl('view', ['record' => $job]))->assertNotFound();
});

test('scheduled job can be started once and creates the first job card', function () {
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

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'client_id' => $client->getKey(),
        'planned_start_date' => '2026-07-25',
        'shift' => JobShift::Night,
        'equipment_requirement' => 'Forklift FL-18',
        'assigned_operator_id' => $operator->getKey(),
        'status' => JobStatus::Scheduled,
    ]);

    $started = app(StartJobAction::class)->execute($job, $actor);

    expect($started->status)->toBe(JobStatus::InProgress)
        ->and($started->jobCards()->count())->toBe(1);

    $card = $started->jobCards()->firstOrFail();

    expect($card->tenant_id)->toBe($job->tenant_id)
        ->and($card->company_id)->toBe($job->company_id)
        ->and($card->client_id)->toBe($job->client_id)
        ->and($card->card_date?->toDateString())->toBe('2026-07-25')
        ->and($card->shift)->toBe(JobShift::Night->value)
        ->and($card->equipment_reference)->toBe('Forklift FL-18')
        ->and($card->operator_id)->toBe($operator->getKey())
        ->and($card->card_number)->toStartWith('JC-')
        ->and($card->approval_status)->toBe(JobCardApprovalStatus::Draft);

    $startedAgain = app(StartJobAction::class)->execute($started->fresh(), $actor);

    expect($startedAgain->jobCards()->count())->toBe(1);
});

test('scheduled workspace shows start job as the primary action and explains automatic first card creation', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $operator = User::factory()->create(['tenant_id' => $tenant->getKey()]);
    $actor->companies()->sync([$company->getKey()]);
    $operator->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::Scheduled,
        'title' => 'Quay wall inspection',
        'assigned_operator_id' => $operator->getKey(),
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('Start Job')
        ->assertSee('create Job Card #1 automatically');
});

test('workspace crew metric shows external operator name instead of the creator', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->actingAsCompanyAdministrator($tenant, ['first_name' => 'Ridwan', 'last_name' => 'Kadri']);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'assigned_operator_id' => null,
        'assigned_operator_name' => 'Kofi Asante',
        'created_by' => $actor->getKey(),
        'updated_by' => $actor->getKey(),
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('Kofi Asante')
        ->assertDontSee('Ridwan Kadri');
});

test('draft job cannot be started and jobs with unapproved cards cannot complete', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $draftJob = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::Draft,
    ]);

    expect(fn () => app(StartJobAction::class)->execute($draftJob, $actor))->toThrow(BusinessException::class);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'actual_start_date' => '2026-07-24',
    ]);

    JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::Draft,
    ]);

    expect(fn () => app(CompleteJobAction::class)->execute($job, $actor, ['actual_end_date' => now()]))
        ->toThrow(BusinessException::class);
});

test('in progress workspace shows continue job card and completion blockers while draft work exists', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
    ]);

    JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::Draft,
        'card_number' => 'JC-00001',
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('Continue Job Card')
        ->assertSee('No approved Job Cards exist yet.');
});

test('job card approval workflow supports submit return and approve', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
    ]);

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::Draft,
    ]);

    $submitted = app(SubmitJobCardAction::class)->execute($jobCard, $actor);
    expect($submitted->approval_status)->toBe(JobCardApprovalStatus::Submitted);

    $returned = app(ReturnJobCardAction::class)->execute($submitted, $actor, 'Hours need correction.');
    expect($returned->approval_status)->toBe(JobCardApprovalStatus::Returned)
        ->and($returned->return_reason)->toBe('Hours need correction.');

    $resubmitted = app(SubmitJobCardAction::class)->execute($returned, $actor);
    $approved = app(ApproveJobCardAction::class)->execute($resubmitted, $actor, 'Looks good.');

    expect($approved->approval_status)->toBe(JobCardApprovalStatus::Approved)
        ->and($approved->approved_by)->toBe($actor->getKey())
        ->and($approved->approved_at)->not->toBeNull();
});

test('approver sees review submitted card as the primary action', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
    ]);

    JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::Submitted,
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('Review Submitted Card')
        ->assertSee('awaiting approval');
});

test('returned card becomes the primary correction action for operational users', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
    ]);

    JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::Returned,
        'return_reason' => 'Update overtime hours.',
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('Correct Returned Card')
        ->assertSee('Update overtime hours.');
});

test('planning becomes restricted after job start for non approvers', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $operationsManager = $this->tenantUser($tenant, ['email' => 'ops@example.test'], [RoleName::OperationsManager->value]);
    $approver = $this->tenantUser($tenant, ['email' => 'approver@example.test'], [RoleName::CompanyAdministrator->value]);
    $operationsManager->companies()->sync([$company->getKey()]);
    $approver->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'actual_start_date' => '2026-07-24',
    ]);

    expect(fn () => app(UpdateJobAction::class)->execute($job, ['title' => 'Updated title'], $operationsManager))
        ->toThrow(BusinessException::class);

    $updated = app(UpdateJobAction::class)->execute($job, ['title' => 'Updated title'], $approver);

    expect($updated->title)->toBe('Updated title');
});

test('completed workspace removes operational mutation actions and shows completion summary', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::Completed,
        'completed_at' => now(),
        'completed_by' => $actor->getKey(),
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('View Completion Summary')
        ->assertDontSee('Start Job')
        ->assertDontSee('Resume Job');
});

test('cancelled workspace shows no operational action', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::Cancelled,
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('No operational action available')
        ->assertDontSee('Start Job')
        ->assertDontSee('Resume Job');
});
