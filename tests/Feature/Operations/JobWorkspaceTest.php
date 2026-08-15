<?php

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Resources\Jobs\JobResource;
use App\Core\Administration\Filament\Widgets\JobWorkspaceWidget;
use App\Core\Shared\Exceptions\BusinessException;
use App\CRM\Models\Client;
use App\Models\User;
use App\Operations\Actions\JobCards\ApproveJobCardAction;
use App\Operations\Actions\JobCards\MarkJobCardBillingReadyAction;
use App\Operations\Actions\JobCards\ReturnJobCardAction;
use App\Operations\Actions\JobCards\SubmitJobCardAction;
use App\Operations\Actions\Jobs\CompleteJobAction;
use App\Operations\Actions\Jobs\HoldJobAction;
use App\Operations\Actions\Jobs\StartJobAction;
use App\Operations\Actions\Jobs\UpdateJobAction;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Enums\JobShift;
use App\Operations\Enums\JobStatus;
use App\Operations\Enums\JobType;
use App\Operations\Enums\WaybillStatus;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Models\Waybill;
use Illuminate\Support\Str;
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
        ->assertSee($job->job_number);
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

test('scheduled heavy machinery job can be started without auto-creating a client job card', function () {
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
        'job_type' => JobType::HeavyMachinery,
    ]);

    $started = app(StartJobAction::class)->execute($job, $actor);

    expect($started->status)->toBe(JobStatus::InProgress)
        ->and($started->jobCards()->count())->toBe(0);

    $startedAgain = app(StartJobAction::class)->execute($started->fresh(), $actor);

    expect($startedAgain->jobCards()->count())->toBe(0);
});

test('scheduled workspace shows start job and explains that the operational document is recorded after start', function () {
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
        'job_type' => JobType::HeavyMachinery,
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('Start Job')
        ->assertSee('recorded from live operations')
        ->assertDontSee('create Job Card #1 automatically');
});

test('draft workspace shows planning readiness and schedule action only when ready', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $draftJob = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::Draft,
        'equipment_requirement' => null,
        'assigned_operator_id' => null,
        'assigned_operator_name' => null,
        'planned_start_date' => null,
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $draftJob])
        ->assertSee('Continue Planning')
        ->assertSee('Edit Planning')
        ->assertSee('Planning incomplete')
        ->assertSee('Equipment')
        ->assertSee('Planned operator')
        ->assertSee('Planned start date');

    $operator = User::factory()->create(['tenant_id' => $tenant->getKey()]);
    $operator->companies()->sync([$company->getKey()]);

    $readyJob = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::Draft,
        'equipment_requirement' => 'Forklift FL-18',
        'assigned_operator_id' => $operator->getKey(),
        'planned_start_date' => '2026-08-05',
        'job_type' => JobType::HeavyMachinery,
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $readyJob])
        ->assertSee('Ready the Job for deployment')
        ->assertSee('Mark Ready for Deployment')
        ->assertSee('Workflow tracker');
});

test('heavy machinery workflow stages include plain language descriptions', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::Draft,
        'job_type' => JobType::HeavyMachinery,
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('What do these stages mean?')
        ->assertSee('Draft')
        ->assertSee('Planning is still being prepared. You can continue editing this Job.')
        ->assertSee('Ready for Deployment')
        ->assertSee('Planning is complete and the machine is ready to be sent to the client-allocated work location.')
        ->assertSee('In Progress')
        ->assertSee('The machine has been deployed and work is underway.')
        ->assertSee('Job Card Recorded')
        ->assertSee('The client-issued Job Card has been received and recorded.')
        ->assertSee('Verified')
        ->assertSee('The Job Card has been checked for endorsement and total hours.')
        ->assertSee('Billing Ready')
        ->assertSee('Hours and rates have been compiled and this work is ready for invoicing.')
        ->assertSee('Completed')
        ->assertSee('Operational processing for this Job is finished.');
});

test('current heavy machinery stage shows its description and business label instead of raw status value', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::Scheduled,
        'job_type' => JobType::HeavyMachinery,
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('READY FOR DEPLOYMENT')
        ->assertSee('Planning is complete and the machine is ready to be sent to the client-allocated work location.')
        ->assertSee('Start Job')
        ->assertDontSee('SCHEDULED');
});

test('mark ready for deployment action is executable from the workspace and retains the same job identity', function () {
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
        'status' => JobStatus::Draft,
        'job_type' => JobType::HeavyMachinery,
        'title' => 'Bulk discharge support',
        'equipment_requirement' => 'Forklift FL-18',
        'assigned_operator_id' => $operator->getKey(),
        'planned_start_date' => '2026-08-05',
        'shift' => JobShift::Day,
    ]);

    $originalId = $job->getKey();
    $originalUuid = $job->uuid;
    $originalJobNumber = $job->job_number;

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('Mark Ready for Deployment')
        ->call('runWorkflowAction', 'schedule')
        ->assertRedirect(JobResource::getUrl('view', ['record' => $job->fresh()]));

    $job->refresh();

    expect($job->status)->toBe(JobStatus::Scheduled)
        ->and($job->getKey())->toBe($originalId)
        ->and($job->uuid)->toBe($originalUuid)
        ->and($job->job_number)->toBe($originalJobNumber)
        ->and($job->jobCards()->count())->toBe(0);
});

test('ready for deployment workspace exposes start job and in progress exposes record client job card without auto creating one', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::Scheduled,
        'job_type' => JobType::HeavyMachinery,
        'title' => 'Crane support',
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('READY FOR DEPLOYMENT')
        ->assertSee('Planning is complete and the machine is ready to be sent to the client-allocated work location.')
        ->assertSee('Start Job')
        ->assertDontSee('Schedule Job')
        ->call('runWorkflowAction', 'start')
        ->assertRedirect(JobResource::getUrl('view', ['record' => $job->fresh()]));

    $job->refresh();

    expect($job->status)->toBe(JobStatus::InProgress)
        ->and($job->jobCards()->count())->toBe(0);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job->fresh()])
        ->assertSee('IN PROGRESS')
        ->assertSee('The machine has been deployed and work is underway.')
        ->assertSee('Record Client Job Card');
});

test('unauthorized users do not receive executable ready for deployment ctas', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $user = User::factory()->create(['tenant_id' => $tenant->getKey()]);
    $operator = User::factory()->create(['tenant_id' => $tenant->getKey()]);
    $user->companies()->sync([$company->getKey()]);
    $operator->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);
    $this->actingAs($user);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::Draft,
        'job_type' => JobType::HeavyMachinery,
        'title' => 'Container unloading',
        'equipment_requirement' => 'Reach stacker',
        'assigned_operator_id' => $operator->getKey(),
        'planned_start_date' => '2026-08-05',
        'shift' => JobShift::Day,
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('Ready the Job for deployment')
        ->assertSee('An authorized user can now mark this Job ready for deployment.')
        ->assertDontSee('Mark Ready for Deployment');
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

test('draft job cannot be started and jobs with non-billing-ready client job cards cannot complete', function () {
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
        'approval_status' => JobCardApprovalStatus::Recorded,
    ]);

    expect(fn () => app(CompleteJobAction::class)->execute($job, $actor, ['actual_end_date' => now()]))
        ->toThrow(BusinessException::class);
});

test('in progress workspace shows continue client job card and billing-ready completion blockers', function () {
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
        'approval_status' => JobCardApprovalStatus::Recorded,
        'card_number' => 'JC-00001',
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('Continue Client Job Card')
        ->assertSee('No billing-ready Client Job Cards exist yet.');
});

test('heavy machinery workspace shows billing tab and calculated billing basis', function () {
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
        'job_type' => JobType::HeavyMachinery,
    ]);

    JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::BillingReady,
        'card_number' => 'JC-00041',
        'client_card_reference' => 'GPHA-JC-41',
        'machine_number' => 'FLT-16T-04',
        'from_time' => '08:00',
        'to_time' => '16:00',
        'total_hours' => 8,
        'rate_currency' => 'USD',
        'hourly_rate' => 160,
        'exchange_rate' => 11.56,
        'converted_hourly_rate' => 1849.60,
        'billable_amount' => 14796.80,
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('Billing')
        ->assertSee('Total billable')
        ->assertSee('GPHA-JC-41')
        ->assertSee('14,796.80');
});

test('client job card verification workflow supports submit return verify and billing ready', function () {
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
        'approval_status' => JobCardApprovalStatus::Recorded,
        'client_endorsed' => true,
        'client_stamped' => true,
        'machine_number' => 'FLT-18-01',
        'card_date' => now()->toDateString(),
        'total_hours' => 8,
        'hourly_rate' => 150,
    ]);
    $jobCard->addMediaFromString('signed')->usingFileName('signed-card.txt')->toMediaCollection('job-card-documents');

    $submitted = app(SubmitJobCardAction::class)->execute($jobCard, $actor);
    expect($submitted->approval_status)->toBe(JobCardApprovalStatus::PendingVerification);

    $returned = app(ReturnJobCardAction::class)->execute($submitted, $actor, 'Hours need correction.');
    expect($returned->approval_status)->toBe(JobCardApprovalStatus::Returned)
        ->and($returned->return_reason)->toBe('Hours need correction.');

    $resubmitted = app(SubmitJobCardAction::class)->execute($returned, $actor);
    $approved = app(ApproveJobCardAction::class)->execute($resubmitted, $actor, 'Looks good.');

    expect($approved->approval_status)->toBe(JobCardApprovalStatus::Verified)
        ->and($approved->verified_by)->toBe($actor->getKey())
        ->and($approved->verified_at)->not->toBeNull();

    $billingReady = app(MarkJobCardBillingReadyAction::class)->execute($approved, $actor);

    expect($billingReady->approval_status)->toBe(JobCardApprovalStatus::BillingReady)
        ->and($billingReady->billing_ready_by)->toBe($actor->getKey())
        ->and($billingReady->billing_ready_at)->not->toBeNull();
});

test('approver sees review pending card as the primary action', function () {
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
        'approval_status' => JobCardApprovalStatus::PendingVerification,
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('Review / Verify Job Card')
        ->assertSee('awaiting verification');
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
        ->assertSee('View Final Summary')
        ->assertDontSee('Start Job')
        ->assertDontSee('Resume Job');
});

test('trucking workspace branches to waybills instead of client job cards', function () {
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
        'job_type' => JobType::Trucking,
        'title' => 'Haulage support',
    ]);

    Waybill::withoutEvents(fn () => Waybill::query()->create([
        'uuid' => (string) Str::uuid(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'job_id' => $job->getKey(),
        'client_id' => $job->client_id,
        'waybill_date' => now()->toDateString(),
        'driver_name' => 'Kwame Boateng',
        'truck_number' => 'AS-2214-26',
        'number_of_trips' => 3,
        'pickup_point' => 'Tema Port',
        'destination' => 'Kadmay Yard',
        'status' => WaybillStatus::PendingVerification,
    ]));

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('Waybills')
        ->assertSee('Review Pending Waybill')
        ->assertDontSee('Create New Job Card')
        ->assertDontSee('What do these stages mean?')
        ->assertDontSee('The client-issued Job Card has been received and recorded.');
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
