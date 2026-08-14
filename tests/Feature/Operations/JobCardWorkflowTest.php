<?php

use App\Administration\Enums\RoleName;
use App\Core\Shared\Exceptions\BusinessException;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use App\Models\User;
use App\Operations\Actions\JobCards\ApproveJobCardAction;
use App\Operations\Actions\JobCards\CreateJobCardAction;
use App\Operations\Actions\JobCards\SubmitJobCardAction;
use App\Operations\Actions\JobCards\UpdateJobCardAction;
use App\Operations\Actions\JobCardWorkEntries\CreateJobCardWorkEntryAction;
use App\Operations\Actions\Jobs\CreateJobAction;
use App\Operations\Actions\Jobs\StartJobAction;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Enums\JobShift;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

test('job can be created with kadmay planning fields', function () {
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
    $site = ClientSite::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'client_id' => $client->getKey(),
    ]);
    $operator = User::factory()->create(['tenant_id' => $tenant->getKey()]);
    $operator->companies()->sync([$company->getKey()]);

    $job = app(CreateJobAction::class)->execute([
        'client_id' => $client->getKey(),
        'client_site_id' => $site->getKey(),
        'title' => 'Operational engagement for GPHA',
        'job_reference' => 'KAD-2026-018',
        'planned_start_date' => '2026-07-23',
        'planned_start_time' => '19:00',
        'planned_end_date' => '2026-07-24',
        'planned_end_time' => '07:00',
        'vessel' => 'MV Jubilee',
        'work_area' => 'Jubilee Terminal',
        'equipment_requirement' => 'Forklift FL-18',
        'assigned_operator_id' => $operator->getKey(),
        'shift' => JobShift::Night->value,
        'priority' => 'high',
        'status' => 'draft',
        'description' => 'Night shift plant support',
    ], $actor);

    expect($job->company_id)->toBe($company->getKey())
        ->and($job->client_id)->toBe($client->getKey())
        ->and($job->client_site_id)->toBe($site->getKey())
        ->and($job->job_reference)->toBe('KAD-2026-018')
        ->and($job->vessel)->toBe('MV Jubilee')
        ->and($job->work_area)->toBe('Jubilee Terminal')
        ->and($job->equipment_requirement)->toBe('Forklift FL-18')
        ->and($job->assigned_operator_id)->toBe($operator->getKey())
        ->and($job->shift)->toBe(JobShift::Night);
});

test('job card belongs to the same company and tenant as the job and attachments are stored on the card', function () {
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

    Storage::disk('local')->put('job-card-uploads/job-card-signed.txt', 'signed');

    $jobCard = app(CreateJobCardAction::class)->execute($job, [
        'card_date' => '2026-07-23',
        'shift' => JobShift::Night->value,
        'equipment_reference' => 'Forklift FL-18',
        'operated_by' => 'Ridwan Kadri',
    ], $actor, ['job-card-uploads/job-card-signed.txt']);

    expect($jobCard->tenant_id)->toBe($job->tenant_id)
        ->and($jobCard->company_id)->toBe($job->company_id)
        ->and($jobCard->job_id)->toBe($job->getKey())
        ->and($jobCard->getMedia('job-card-documents'))->toHaveCount(1);
});

test('multiple work entries can belong to one job card and overnight duration calculates correctly', function () {
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
        'card_date' => '2026-07-23',
    ]);

    $first = app(CreateJobCardWorkEntryAction::class)->execute($jobCard, [
        'vessel' => 'MV Atlantic Trader',
        'work_area' => 'Jubilee Terminal',
        'from_time' => '22:00',
        'to_time' => '06:00',
        'normal_hours' => 6,
        'overtime_hours' => 2,
    ], $actor);

    $second = app(CreateJobCardWorkEntryAction::class)->execute($jobCard, [
        'vessel' => 'MV Atlantic Trader',
        'work_area' => 'Yard',
        'from_time' => '06:30',
        'to_time' => '08:30',
        'normal_hours' => 2,
        'overtime_hours' => 0,
    ], $actor);

    expect($jobCard->workEntries()->count())->toBe(2)
        ->and((float) $first->total_hours)->toBe(8.0)
        ->and((float) $second->total_hours)->toBe(2.0);
});

test('unauthorized cross company job cards are rejected', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$companyA->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyB->getKey(),
    ]);

    expect(fn () => app(CreateJobCardAction::class)->execute($job, [
        'card_date' => '2026-07-23',
        'shift' => JobShift::Night->value,
    ], $actor))->toThrow(BusinessException::class);
});

test('new job cards cannot be created while a job is on hold', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::OnHold,
    ]);

    expect(fn () => app(CreateJobCardAction::class)->execute($job, [
        'card_date' => '2026-07-23',
        'shift' => JobShift::Night->value,
    ], $actor))->toThrow(BusinessException::class, 'Additional job cards can only be created while the job is in progress.');
});

test('first job card inherits a planned external operator and not the authenticated actor', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::Scheduled,
        'assigned_operator_id' => null,
        'assigned_operator_name' => 'Kofi Asante',
    ]);

    $started = app(StartJobAction::class)->execute($job, $actor);
    $card = $started->jobCards()->firstOrFail();

    expect($card->operator_id)->toBeNull()
        ->and($card->operated_by)->toBe('Kofi Asante')
        ->and($card->created_by)->toBe($actor->getKey())
        ->and($card->operatorDisplayName())->toBe('Kofi Asante');
});

test('start job fails clearly when no operator is assigned', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::Scheduled,
        'assigned_operator_id' => null,
        'assigned_operator_name' => null,
    ]);

    expect(fn () => app(StartJobAction::class)->execute($job, $actor))
        ->toThrow(BusinessException::class, 'Assign an operator before starting this Job.');
});

test('draft job card operator can differ from the planned operator and the change is logged', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $plannedOperator = User::factory()->create(['tenant_id' => $tenant->getKey()]);
    $actualOperator = User::factory()->create(['tenant_id' => $tenant->getKey()]);
    $actor->companies()->sync([$company->getKey()]);
    $plannedOperator->companies()->sync([$company->getKey()]);
    $actualOperator->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'assigned_operator_id' => $plannedOperator->getKey(),
        'assigned_operator_name' => null,
    ]);

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'operator_id' => $plannedOperator->getKey(),
        'operated_by' => null,
        'approval_status' => JobCardApprovalStatus::Draft,
    ]);

    $updated = app(UpdateJobCardAction::class)->execute($jobCard, [
        'operator_id' => $actualOperator->getKey(),
        'operated_by' => null,
    ], $actor);

    $activity = Activity::query()
        ->where('event', 'job_card.operator_changed')
        ->where('subject_id', $jobCard->getKey())
        ->latest()
        ->first();

    expect($updated->operator_id)->toBe($actualOperator->getKey())
        ->and($updated->operatorDisplayName())->toBe($actualOperator->full_name)
        ->and($activity?->description)->toBe(sprintf('Operator changed from %s to %s', $plannedOperator->full_name, $actualOperator->full_name));
});

test('job card operator fields cannot conflict', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $operator = User::factory()->create(['tenant_id' => $tenant->getKey()]);
    $actor->companies()->sync([$company->getKey()]);
    $operator->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
    ]);

    expect(fn () => app(CreateJobCardAction::class)->execute($job, [
        'card_date' => '2026-07-23',
        'shift' => JobShift::Night->value,
        'operator_id' => $operator->getKey(),
        'operated_by' => 'Kofi Asante',
    ], $actor))->toThrow(BusinessException::class, 'Select a company operator or enter an external operator name, not both.');
});

test('officer approval is recorded and cross company access to the card is denied', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $approver = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $approver->companies()->sync([$companyA->getKey()]);
    $outsider = $this->tenantUser($tenant, ['email' => 'outsider@example.test'], [RoleName::OperationsManager->value]);
    $outsider->companies()->sync([$companyB->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyA->getKey(),
    ]);

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyA->getKey(),
    ]);

    $submitted = app(SubmitJobCardAction::class)->execute($jobCard, $approver);
    $approved = app(ApproveJobCardAction::class)->execute($submitted, $approver, 'Card reviewed and approved.');

    expect($approved->approval_status)->toBe(JobCardApprovalStatus::Approved)
        ->and($approved->approved_by)->toBe($approver->getKey())
        ->and($approved->approved_at)->not->toBeNull()
        ->and(Gate::forUser($outsider)->allows('view', $approved))->toBeFalse();
});

test('existing jobs remain valid without job cards', function () {
    $job = Job::factory()->create([
        'job_reference' => null,
        'vessel' => null,
        'work_area' => null,
        'equipment_requirement' => null,
        'assigned_operator_id' => null,
        'shift' => null,
    ]);

    expect($job->exists)->toBeTrue()
        ->and($job->jobCards()->count())->toBe(0);
});
