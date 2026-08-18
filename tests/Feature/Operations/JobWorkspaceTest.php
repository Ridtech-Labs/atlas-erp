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
use App\Operations\Actions\JobCardWorkEntries\CreateJobCardWorkEntryAction;
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

function attachWorkspaceJobCardEvidence(JobCard $jobCard, string $fileName = 'signed-card.txt'): void
{
    $jobCard->addMediaFromString('signed')->usingFileName($fileName)->toMediaCollection('job-card-documents');
}

function recordWorkspaceJobCardEntry(JobCard $jobCard, User $actor, array $overrides = []): void
{
    app(CreateJobCardWorkEntryAction::class)->execute($jobCard, array_merge([
        'vessel' => 'MV Atlantic Trader',
        'work_area' => 'Jubilee Terminal',
        'from_time' => '08:00',
        'to_time' => '16:00',
        'normal_hours' => 8,
        'overtime_hours' => 0,
    ], $overrides), $actor);
}

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
        ->assertSee('Complete Job Planning')
        ->assertSee('Edit Job')
        ->assertSee('Review or update the Job information before handing it over to Operations.')
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
        ->assertSee('Accounts Reviewed')
        ->assertSee('Finance has reviewed the client-endorsed Job Card and confirmed it can move into billing preparation.')
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
        ->assertSee('Awaiting Operations')
        ->assertSee('An Operations Manager can now mark this Job Ready for Deployment.')
        ->assertDontSee('Mark Ready for Deployment');
});

test('data entry clerk sees edit job for editable draft and the workspace links to the same job record', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $clerk = $this->tenantUser($tenant, ['email' => 'clerk-workspace@example.test'], [RoleName::DataEntryClerk->value]);
    $clerk->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);
    $this->actingAs($clerk);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::Draft,
        'job_type' => JobType::HeavyMachinery,
        'equipment_requirement' => null,
        'assigned_operator_id' => null,
        'planned_start_date' => null,
    ]);

    $editUrl = JobResource::getUrl('edit', ['record' => $job]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('Complete Job Planning')
        ->assertSee('Edit Job')
        ->assertSee('Review or update the Job information before handing it over to Operations.')
        ->assertSee($editUrl);
});

test('data entry clerk sees awaiting operations when draft planning is complete and cannot cancel jobs from the workspace', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $clerk = $this->tenantUser($tenant, ['email' => 'clerk-awaiting-ops@example.test'], [RoleName::DataEntryClerk->value]);
    $operator = User::factory()->create(['tenant_id' => $tenant->getKey()]);
    $clerk->companies()->sync([$company->getKey()]);
    $operator->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);
    $this->actingAs($clerk);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::Draft,
        'job_type' => JobType::HeavyMachinery,
        'equipment_requirement' => 'Reach stacker',
        'assigned_operator_id' => $operator->getKey(),
        'planned_start_date' => '2026-08-16',
        'shift' => JobShift::Day,
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('Awaiting Operations')
        ->assertSee('An Operations Manager can now mark this Job Ready for Deployment.')
        ->assertDontSee('Mark Ready for Deployment')
        ->assertDontSee('Cancel Job');
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
    $approver = $this->tenantUser($tenant, ['email' => 'workspace-approver@example.test'], [RoleName::CompanyAdministrator->value]);
    $submitter = $this->tenantUser($tenant, ['email' => 'workspace-submitter@example.test'], [RoleName::OperationsManager->value]);
    $approver->companies()->sync([$company->getKey()]);
    $submitter->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $draftJob = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::Draft,
    ]);

    expect(fn () => app(StartJobAction::class)->execute($draftJob, $approver))->toThrow(BusinessException::class);

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

    expect(fn () => app(CompleteJobAction::class)->execute($job, $approver, ['actual_end_date' => now()]))
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

test('job workspace aggregates recorded hours from client job cards', function () {
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
        'approval_status' => JobCardApprovalStatus::Recorded,
        'card_number' => 'JC-00001',
        'from_time' => '08:00:00',
        'to_time' => '16:00:00',
        'total_hours' => 8,
        'operated_by' => 'Kwame Mensah',
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('Recorded hours')
        ->assertSee('8.00')
        ->assertSee('JC-00001')
        ->assertSee('8.00 total hours');
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
    $approver = $this->tenantUser($tenant, ['email' => 'workspace-approver@example.test'], [RoleName::CompanyAdministrator->value]);
    $submitter = $this->tenantUser($tenant, ['email' => 'workspace-submitter@example.test'], [RoleName::OperationsManager->value]);
    $approver->companies()->sync([$company->getKey()]);
    $submitter->companies()->sync([$company->getKey()]);
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
        'shift' => JobShift::Day,
        'equipment_reference' => 'Forklift FL-18',
        'operated_by' => 'Kwame Mensah',
        'client_endorsed' => true,
        'client_stamped' => true,
        'machine_number' => 'FLT-18-01',
        'card_date' => now()->toDateString(),
        'hourly_rate' => 150,
    ]);
    recordWorkspaceJobCardEntry($jobCard, $submitter);
    attachWorkspaceJobCardEvidence($jobCard, 'signed-card.txt');

    $submitted = app(SubmitJobCardAction::class)->execute($jobCard, $submitter);
    expect($submitted->approval_status)->toBe(JobCardApprovalStatus::PendingVerification);

    $returned = app(ReturnJobCardAction::class)->execute($submitted, $approver, 'Hours need correction.');
    expect($returned->approval_status)->toBe(JobCardApprovalStatus::Returned)
        ->and($returned->return_reason)->toBe('Hours need correction.');

    $resubmitted = app(SubmitJobCardAction::class)->execute($returned, $submitter);
    $approved = app(ApproveJobCardAction::class)->execute($resubmitted, $approver, 'Looks good.');

    expect($approved->approval_status)->toBe(JobCardApprovalStatus::Verified)
        ->and($approved->verified_by)->toBe($approver->getKey())
        ->and($approved->verified_at)->not->toBeNull();

    $billingReady = app(MarkJobCardBillingReadyAction::class)->execute($approved, $approver);

    expect($billingReady->approval_status)->toBe(JobCardApprovalStatus::BillingReady)
        ->and($billingReady->billing_ready_by)->toBe($approver->getKey())
        ->and($billingReady->billing_ready_at)->not->toBeNull();
});

test('finance manager sees accounts review pending card as the primary action when another user submitted it', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, ['email' => 'finance-verifier@example.test'], [RoleName::FinanceManager->value]);
    $submitter = $this->tenantUser($tenant, ['email' => 'clerk-workspace-submit@example.test'], [RoleName::DataEntryClerk->value]);
    $actor->companies()->sync([$company->getKey()]);
    $submitter->companies()->sync([$company->getKey()]);
    $this->actingAs($actor);
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
        'submitted_by' => $submitter->getKey(),
        'submitted_at' => now(),
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertSee('Review for Billing')
        ->assertSee('Accounts review');
});

test('operations manager without accounts review permission sees awaiting accounts review for a legacy pending job card', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, ['email' => 'ops-workspace-legacy@example.test'], [RoleName::OperationsManager->value]);
    $actor->companies()->sync([$company->getKey()]);
    $this->actingAs($actor);
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
        'submitted_by' => null,
        'submitted_at' => null,
    ]);

    Livewire::test(JobWorkspaceWidget::class, ['record' => $job])
        ->assertDontSee('Review for Billing')
        ->assertSee('Awaiting Accounts Review')
        ->assertSee('waiting for Finance or an authorized administrator to review it for billing');
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
