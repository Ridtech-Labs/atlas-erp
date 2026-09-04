<?php

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Resources\JobCards\JobCardResource;
use App\Core\Administration\Filament\Resources\JobCards\Pages\CreateJobCard;
use App\Core\Administration\Filament\Resources\JobCards\Pages\EditJobCard;
use App\Core\Shared\Exceptions\BusinessException;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use App\Finance\Actions\BillingBatches\AddJobCardsToBillingBatchAction;
use App\Finance\Actions\BillingBatches\CreateBillingBatchAction;
use App\Finance\Actions\BillingBatches\PrepareBillingBatchAction;
use App\Finance\Actions\RateAgreements\CreateRateAgreementAction;
use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Enums\RateAgreementStatus;
use App\Finance\Services\BillingBatchEligibilityService;
use App\Fleet\Actions\CancelJobAssetAssignmentAction;
use App\Fleet\Actions\CreateFleetAssetAction;
use App\Fleet\Actions\CreateFleetAssetTypeAction;
use App\Fleet\Actions\CreateJobAssetAssignmentAction;
use App\Fleet\Actions\ReleaseJobAssetAssignmentAction;
use App\Fleet\Enums\FleetAssetCategory;
use App\Fleet\Services\JobCardFleetPrefillService;
use App\Models\User;
use App\Operations\Actions\JobCards\ApproveJobCardAction;
use App\Operations\Actions\JobCards\CreateJobCardAction;
use App\Operations\Actions\JobCards\MarkJobCardBillingReadyAction;
use App\Operations\Actions\JobCards\ReturnJobCardAction;
use App\Operations\Actions\JobCards\SubmitJobCardAction;
use App\Operations\Actions\JobCards\UpdateJobCardAction;
use App\Operations\Actions\JobCardWorkEntries\CreateJobCardWorkEntryAction;
use App\Operations\Actions\JobCardWorkEntries\DeleteJobCardWorkEntryAction;
use App\Operations\Actions\JobCardWorkEntries\UpdateJobCardWorkEntryAction;
use App\Operations\Actions\Jobs\CreateJobAction;
use App\Operations\Actions\Jobs\StartJobAction;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Enums\JobShift;
use App\Operations\Enums\JobStatus;
use App\Operations\Enums\JobType;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;

function attachJobCardEvidence(JobCard $jobCard, string $fileName = 'signed-card.txt'): void
{
    $jobCard->addMediaFromString('signed')->usingFileName($fileName)->toMediaCollection('job-card-documents');
}

function recordJobCardWorkEntry(JobCard $jobCard, User $actor, array $overrides = []): void
{
    app(CreateJobCardWorkEntryAction::class)->execute($jobCard, array_merge([
        'vessel' => 'MV Atlantic Trader',
        'work_area' => 'Jubilee Terminal',
        'from_time' => '08:00',
        'to_time' => '16:00',
        'normal_hours' => 8,
        'overtime_hours' => 0,
        'officer_name' => 'Duty officer',
    ], $overrides), $actor);
}

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
        'machine_number' => 'FLT-18-01',
        'total_hours' => 8,
    ], $actor, ['job-card-uploads/job-card-signed.txt']);

    expect($jobCard->tenant_id)->toBe($job->tenant_id)
        ->and($jobCard->company_id)->toBe($job->company_id)
        ->and($jobCard->job_id)->toBe($job->getKey())
        ->and($jobCard->getMedia('job-card-documents'))->toHaveCount(1);
});

test('job card total hours are calculated and persisted from authoritative work entries', function () {
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

    $jobCard = app(CreateJobCardAction::class)->execute($job, [
        'card_date' => '2026-08-16',
        'shift' => JobShift::Day->value,
        'equipment_reference' => 'Forklift FL-18',
        'operated_by' => 'Kwame Mensah',
        'machine_number' => 'FLT-18-01',
    ], $actor);

    recordJobCardWorkEntry($jobCard, $actor);

    $jobCard->refresh();

    expect($jobCard->from_time)->toBeNull()
        ->and($jobCard->to_time)->toBeNull()
        ->and((float) $jobCard->total_hours)->toBe(8.0)
        ->and($jobCard->displayStartTime())->toBe('08:00:00')
        ->and($jobCard->displayEndTime())->toBe('16:00:00');
});

test('job card total hours support another same day work entry duration', function () {
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

    $jobCard = app(CreateJobCardAction::class)->execute($job, [
        'card_date' => '2026-08-16',
        'shift' => JobShift::Day->value,
        'equipment_reference' => 'Forklift FL-18',
        'operated_by' => 'Kwame Mensah',
        'machine_number' => 'FLT-18-01',
    ], $actor);

    recordJobCardWorkEntry($jobCard, $actor, [
        'from_time' => '09:15',
        'to_time' => '13:45',
        'normal_hours' => 4.5,
    ]);

    expect((float) $jobCard->fresh()->total_hours)->toBe(4.5);
});

test('job card total hours support overnight work entry duration', function () {
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

    $jobCard = app(CreateJobCardAction::class)->execute($job, [
        'card_date' => '2026-08-16',
        'shift' => JobShift::Night->value,
        'equipment_reference' => 'Forklift FL-18',
        'operated_by' => 'Kwame Mensah',
        'machine_number' => 'FLT-18-01',
    ], $actor);

    recordJobCardWorkEntry($jobCard, $actor, [
        'from_time' => '22:00',
        'to_time' => '06:00',
        'normal_hours' => 6,
        'overtime_hours' => 2,
    ]);

    expect((float) $jobCard->fresh()->total_hours)->toBe(8.0);
});

test('job card total hours recalculate when work entries are edited', function () {
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
        'card_date' => '2026-08-16',
        'approval_status' => JobCardApprovalStatus::Recorded,
        'operated_by' => 'Kwame Mensah',
    ]);

    $entry = app(CreateJobCardWorkEntryAction::class)->execute($jobCard, [
        'from_time' => '08:00:00',
        'to_time' => '16:00:00',
        'normal_hours' => 8,
        'overtime_hours' => 0,
    ], $actor);

    $updated = app(UpdateJobCardWorkEntryAction::class)->execute($entry, [
        'from_time' => '10:00',
        'to_time' => '15:30',
        'normal_hours' => 5.5,
        'overtime_hours' => 0,
    ], $actor);

    expect($updated->from_time)->toBe('10:00:00')
        ->and($updated->to_time)->toBe('15:30:00')
        ->and((float) $updated->total_hours)->toBe(5.5)
        ->and((float) $jobCard->fresh()->total_hours)->toBe(5.5);
});

test('filament edit job card page directs operators to work entries instead of parent hour capture fields', function () {
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

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'card_date' => '2026-08-16',
        'approval_status' => JobCardApprovalStatus::Recorded,
        'total_hours' => null,
        'operated_by' => 'Kwame Mensah',
    ]);

    Livewire::test(EditJobCard::class, ['record' => $jobCard->getKey()])
        ->assertSee('Work Entries')
        ->assertSee('Atlas totals the recorded hours automatically from those entries.')
        ->assertDontSee('Header hours');
});

test('data entry clerk cannot see verify or return controls on pending verification job cards', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $operationsManager = $this->tenantUser($tenant, ['email' => 'clerk-review-hidden@example.test'], [RoleName::DataEntryClerk->value]);
    $submitter = $this->tenantUser($tenant, ['email' => 'pending-card-submitter@example.test'], [RoleName::CompanyAdministrator->value]);
    $operationsManager->companies()->sync([$company->getKey()]);
    $submitter->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);
    $this->actingAs($operationsManager);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
    ]);

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::PendingVerification,
        'submitted_by' => $submitter->getKey(),
        'submitted_at' => now(),
    ]);

    $this->get(JobCardResource::getUrl('edit', ['record' => $jobCard]))
        ->assertRedirect(JobCardResource::getUrl('view', ['record' => $jobCard]));

    $this->get(JobCardResource::getUrl('view', ['record' => $jobCard]))
        ->assertOk()
        ->assertDontSee('Review for Billing')
        ->assertDontSee('Return to Operations')
        ->assertDontSee('Save changes');
});

test('finance manager sees accounts review and return controls on pending review job cards', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $verifier = $this->tenantUser($tenant, ['email' => 'finance-reviewer@example.test'], [RoleName::FinanceManager->value]);
    $verifier->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);
    $this->actingAs($verifier);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
    ]);

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::PendingVerification,
        'submitted_by' => null,
        'submitted_at' => now(),
    ]);

    $this->get(JobCardResource::getUrl('edit', ['record' => $jobCard]))
        ->assertRedirect(JobCardResource::getUrl('view', ['record' => $jobCard]));

    $this->get(JobCardResource::getUrl('view', ['record' => $jobCard]))
        ->assertOk()
        ->assertSee('Review for Billing')
        ->assertSee('Return to Operations');
});

test('verified job card view no longer exposes a direct mark billing ready action', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-no-direct-billing@example.test'], [RoleName::FinanceManager->value]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);
    $this->actingAs($finance);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
    ]);

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::Verified,
        'card_date' => now()->toDateString(),
    ]);

    $this->get(JobCardResource::getUrl('view', ['record' => $jobCard]))
        ->assertOk()
        ->assertDontSee('Mark Billing Ready')
        ->assertSee('Billing Batch');
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
        ->and((float) $second->total_hours)->toBe(2.0)
        ->and((float) $jobCard->fresh()->total_hours)->toBe(10.0);
});

test('job card cannot submit without authoritative work entries', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $submitter = $this->tenantUser($tenant, ['email' => 'submit-no-entries@example.test'], [RoleName::OperationsManager->value]);
    $submitter->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::HeavyMachinery,
    ]);

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::Recorded,
        'card_date' => '2026-08-16',
        'shift' => JobShift::Day,
        'equipment_reference' => 'Forklift FL-18',
        'machine_number' => 'FLT-18-08',
        'operated_by' => 'Kwame Mensah',
        'client_endorsed' => true,
        'client_stamped' => true,
    ]);
    attachJobCardEvidence($jobCard, 'submit-no-entries.txt');

    expect(fn () => app(SubmitJobCardAction::class)->execute($jobCard, $submitter))
        ->toThrow(BusinessException::class, 'Add at least one Work Entry before submitting this Job Card.');
});

test('job card cannot submit without required attachment evidence', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $submitter = $this->tenantUser($tenant, ['email' => 'submit-no-attachment@example.test'], [RoleName::OperationsManager->value]);
    $submitter->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::HeavyMachinery,
    ]);

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::Recorded,
        'card_date' => '2026-08-16',
        'shift' => JobShift::Day,
        'equipment_reference' => 'Forklift FL-18',
        'machine_number' => 'FLT-18-08',
        'operated_by' => 'Kwame Mensah',
        'client_endorsed' => true,
        'client_stamped' => true,
    ]);
    recordJobCardWorkEntry($jobCard, $submitter);

    expect(fn () => app(SubmitJobCardAction::class)->execute($jobCard->fresh(), $submitter))
        ->toThrow(BusinessException::class, 'Attach the physical client Job Card evidence before this Job Card can move forward.');
});

test('accounts review rejects inconsistent evidence missing attachment and missing endorsement or stamp', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $submitter = $this->tenantUser($tenant, ['email' => 'ops-inconsistent-card@example.test'], [RoleName::OperationsManager->value]);
    $finance = $this->tenantUser($tenant, ['email' => 'finance-inconsistent-card@example.test'], [RoleName::FinanceManager->value]);
    $submitter->companies()->sync([$company->getKey()]);
    $finance->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::HeavyMachinery,
    ]);

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::Recorded,
        'card_date' => '2026-08-16',
        'shift' => JobShift::Day,
        'equipment_reference' => 'Forklift FL-18',
        'machine_number' => 'FLT-18-08',
        'operated_by' => 'Kwame Mensah',
        'client_endorsed' => false,
        'client_stamped' => false,
    ]);
    recordJobCardWorkEntry($jobCard, $submitter);
    attachJobCardEvidence($jobCard, 'inconsistent-evidence.txt');

    $submitted = app(SubmitJobCardAction::class)->execute($jobCard->fresh(), $submitter);
    $submitted->forceFill(['total_hours' => 7])->saveQuietly();

    expect(fn () => app(ApproveJobCardAction::class)->execute($submitted->fresh(), $finance))
        ->toThrow(BusinessException::class, 'Work Entry totals must reconcile exactly with the Job Card total before this Job Card can move forward.');

    $jobCard = $jobCard->fresh();
    $jobCard->forceFill([
        'approval_status' => JobCardApprovalStatus::PendingVerification->value,
        'total_hours' => $jobCard->workEntries()->sum('total_hours'),
    ])->saveQuietly();
    $jobCard->clearMediaCollection('job-card-documents');

    expect(fn () => app(ApproveJobCardAction::class)->execute($jobCard->fresh(), $finance))
        ->toThrow(BusinessException::class, 'Attach the physical client Job Card evidence before this Job Card can move forward.');

    attachJobCardEvidence($jobCard, 'inconsistent-evidence-restored.txt');
    $jobCard = $jobCard->fresh();
    $jobCard->forceFill([
        'total_hours' => $jobCard->workEntries()->sum('total_hours'),
        'client_endorsed' => false,
        'client_stamped' => true,
    ])->saveQuietly();

    expect(fn () => app(ApproveJobCardAction::class)->execute($jobCard->fresh(), $finance))
        ->toThrow(BusinessException::class, 'Client endorsement and stamp or signature must be confirmed before Accounts review.');
});

test('work entries become read only after submission and unlock again after return for correction', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $submitter = $this->tenantUser($tenant, ['email' => 'ops-work-entry@example.test'], [RoleName::OperationsManager->value]);
    $verifier = $this->tenantUser($tenant, ['email' => 'admin-work-entry@example.test'], [RoleName::CompanyAdministrator->value]);
    $submitter->companies()->sync([$company->getKey()]);
    $verifier->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::HeavyMachinery,
    ]);

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::Recorded,
        'card_date' => '2026-08-16',
        'shift' => JobShift::Day,
        'equipment_reference' => 'Forklift FL-18',
        'machine_number' => 'FLT-18-01',
        'operated_by' => 'Kwame Mensah',
        'total_hours' => 8,
        'client_endorsed' => true,
        'client_stamped' => true,
    ]);

    $entry = app(CreateJobCardWorkEntryAction::class)->execute($jobCard, [
        'vessel' => 'MV Atlantic Trader',
        'work_area' => 'Jubilee Terminal',
        'from_time' => '08:00',
        'to_time' => '16:00',
        'normal_hours' => 8,
        'overtime_hours' => 0,
    ], $submitter);

    $updatedBeforeSubmission = app(UpdateJobCardWorkEntryAction::class)->execute($entry, [
        'vessel' => 'MV Atlantic Trader',
        'work_area' => 'Quay West',
        'from_time' => '08:00',
        'to_time' => '16:00',
        'normal_hours' => 8,
        'overtime_hours' => 0,
    ], $submitter);

    expect($updatedBeforeSubmission->work_area)->toBe('Quay West');

    Storage::disk('local')->put('job-card-uploads/work-entry-locking.txt', 'signed');
    $jobCard->addMedia(Storage::disk('local')->path('job-card-uploads/work-entry-locking.txt'))
        ->preservingOriginal()
        ->toMediaCollection('job-card-documents');

    $submitted = app(SubmitJobCardAction::class)->execute($jobCard->fresh(), $submitter);

    expect($submitted->approval_status)->toBe(JobCardApprovalStatus::PendingVerification)
        ->and($submitted->submitted_by)->toBe($submitter->getKey())
        ->and($submitted->submitted_at)->not->toBeNull();

    expect(fn () => app(CreateJobCardWorkEntryAction::class)->execute($submitted->fresh(), [
        'vessel' => 'MV Atlantic Trader',
        'work_area' => 'Yard',
        'from_time' => '17:00',
        'to_time' => '18:00',
        'normal_hours' => 1,
        'overtime_hours' => 0,
    ], $submitter))->toThrow(BusinessException::class, 'Job card work entries are read-only once the Job Card has been submitted to Accounts.');

    expect(fn () => app(UpdateJobCardWorkEntryAction::class)->execute($entry->fresh(), [
        'vessel' => 'MV Atlantic Trader',
        'work_area' => 'Restricted Yard',
        'from_time' => '08:00',
        'to_time' => '16:00',
        'normal_hours' => 8,
        'overtime_hours' => 0,
    ], $submitter))->toThrow(BusinessException::class, 'Job card work entries are read-only once the Job Card has been submitted to Accounts.');

    expect(fn () => app(DeleteJobCardWorkEntryAction::class)->execute($entry->fresh(), $submitter))
        ->toThrow(BusinessException::class, 'Job card work entries are read-only once the Job Card has been submitted to Accounts.');

    $returned = app(ReturnJobCardAction::class)->execute($submitted->fresh(), $verifier, 'Correct the supporting detail rows.');

    expect($returned->approval_status)->toBe(JobCardApprovalStatus::Returned)
        ->and($returned->returned_by)->toBe($verifier->getKey())
        ->and($returned->submitted_by)->toBe($submitter->getKey());

    $updatedAfterReturn = app(UpdateJobCardWorkEntryAction::class)->execute($entry->fresh(), [
        'vessel' => 'MV Atlantic Trader',
        'work_area' => 'Corrected Yard',
        'from_time' => '08:00',
        'to_time' => '16:00',
        'normal_hours' => 8,
        'overtime_hours' => 0,
    ], $submitter);

    expect($updatedAfterReturn->work_area)->toBe('Corrected Yard');
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
    ], $actor))->toThrow(BusinessException::class, 'Client Job Cards can only be recorded while the job is in progress.');
});

test('data entry clerk can record and submit a client job card but cannot verify return or mark it billing ready', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $clerk = $this->tenantUser($tenant, ['email' => 'clerk-job-card@example.test'], [RoleName::DataEntryClerk->value]);
    $clerk->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::HeavyMachinery,
    ]);

    Storage::disk('local')->put('job-card-uploads/data-entry-clerk.txt', 'signed');

    $jobCard = app(CreateJobCardAction::class)->execute($job, [
        'card_date' => '2026-08-15',
        'shift' => JobShift::Day->value,
        'equipment_reference' => 'Forklift FL-18',
        'operated_by' => 'Kwame Mensah',
        'machine_number' => 'FLT-18-01',
        'total_hours' => 8,
        'client_endorsed' => true,
        'client_stamped' => true,
        'verification_notes' => 'Attempted verification during recording',
        'hourly_rate' => 150,
        'rate_currency' => 'GHS',
        'billable_amount' => 1200,
    ], $clerk, ['job-card-uploads/data-entry-clerk.txt']);

    expect($jobCard->company_id)->toBe($company->getKey())
        ->and($jobCard->getMedia('job-card-documents'))->toHaveCount(1)
        ->and($jobCard->approval_status)->toBe(JobCardApprovalStatus::Recorded)
        ->and($jobCard->verification_notes)->toBeNull()
        ->and($jobCard->hourly_rate)->toBeNull()
        ->and($jobCard->rate_currency)->toBeNull()
        ->and($jobCard->billable_amount)->toBeNull();

    recordJobCardWorkEntry($jobCard, $clerk);

    $submittedByClerk = app(SubmitJobCardAction::class)->execute($jobCard->fresh(), $clerk);

    expect($submittedByClerk->approval_status)->toBe(JobCardApprovalStatus::PendingVerification)
        ->and($submittedByClerk->submitted_by)->toBe($clerk->getKey())
        ->and($submittedByClerk->submitted_at)->not->toBeNull();

    expect(fn () => app(ApproveJobCardAction::class)->execute($submittedByClerk->fresh(), $clerk))
        ->toThrow(BusinessException::class, 'You are not allowed to review this client Job Card for billing.');

    expect(fn () => app(ReturnJobCardAction::class)->execute($submittedByClerk->fresh(), $clerk, 'No verifier authority.'))
        ->toThrow(BusinessException::class, 'You are not allowed to return this client Job Card to Operations.');

    $verified = $submittedByClerk->fresh();
    $verified->forceFill([
        'approval_status' => JobCardApprovalStatus::Verified->value,
        'hourly_rate' => 150,
        'rate_currency' => 'GHS',
    ])->save();

    expect(fn () => app(MarkJobCardBillingReadyAction::class)->execute($verified->fresh(), $clerk))
        ->toThrow(BusinessException::class, 'You are not allowed to mark this client Job Card as billing ready.');
});

test('operations manager without accounts review permission cannot review or return a legacy pending job card', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $operationsManager = $this->tenantUser($tenant, ['email' => 'ops-verifier@example.test'], [RoleName::OperationsManager->value]);
    $operationsManager->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);
    $this->actingAs($operationsManager);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::HeavyMachinery,
    ]);

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::PendingVerification,
        'card_date' => '2026-08-15',
        'machine_number' => 'FLT-18-07',
        'total_hours' => 8,
        'client_endorsed' => true,
        'client_stamped' => true,
        'submitted_by' => null,
        'submitted_at' => null,
    ]);
    $jobCard->addMediaFromString('signed')->usingFileName('ops-verifier-card.txt')->toMediaCollection('job-card-documents');

    Livewire::test(EditJobCard::class, ['record' => $jobCard->getKey()])
        ->assertDontSee('Review for Billing')
        ->assertDontSee('Return to Operations');

    expect($operationsManager->hasPermissionTo('job_cards.verify'))->toBeFalse()
        ->and($operationsManager->hasPermissionTo('job_cards.bill'))->toBeFalse()
        ->and($operationsManager->hasPermissionTo('jobs.approve'))->toBeFalse()
        ->and($operationsManager->can('users.view'))->toBeFalse()
        ->and($operationsManager->can('roles.manage'))->toBeFalse()
        ->and($operationsManager->can('settings.manage'))->toBeFalse();

    expect(fn () => app(ApproveJobCardAction::class)->execute($jobCard->fresh(), $operationsManager, 'Operational evidence confirmed.'))
        ->toThrow(BusinessException::class, 'You are not allowed to review this client Job Card for billing.');

    expect(fn () => app(ReturnJobCardAction::class)->execute($jobCard->fresh(), $operationsManager, 'Correct the operator detail.'))
        ->toThrow(BusinessException::class, 'You are not allowed to return this client Job Card to Operations.');
});

test('record client job card form does not expose verification or billing inputs during initial capture', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $clerk = $this->tenantUser($tenant, ['email' => 'clerk-form@example.test'], [RoleName::DataEntryClerk->value]);
    $clerk->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);
    $this->actingAs($clerk);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::HeavyMachinery,
    ]);

    $this->get(JobCardResource::getUrl('create', ['job' => $job->getKey()]))
        ->assertOk()
        ->assertSee('Recording guidance')
        ->assertSee('Accounts review stage')
        ->assertDontSee('Accounts review notes')
        ->assertDontSee('Billing Preparation')
        ->assertDontSee('Hourly rate')
        ->assertDontSee('Rate currency')
        ->assertDontSee('Calculated billable amount');
});

test('the real Job Card create page prefills the sole active Fleet assignment without creating a dynamic evidence link', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $clerk = $this->tenantUser($tenant, ['email' => 'clerk-fleet-prefill@example.test'], [RoleName::DataEntryClerk->value]);
    $operations = $this->tenantUser($tenant, ['email' => 'operations-fleet-prefill@example.test'], [RoleName::OperationsManager->value]);
    $clerk->companies()->sync([$company->getKey()]);
    $operations->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);
    $this->actingAs($clerk);
    $type = app(CreateFleetAssetTypeAction::class)->execute([
        'name' => 'Reach Stacker', 'category' => FleetAssetCategory::HeavyMachinery->value, 'is_active' => true,
    ], $clerk);
    $asset = app(CreateFleetAssetAction::class)->execute([
        'fleet_asset_type_id' => $type->getKey(), 'asset_number' => 'RS-003', 'operational_status' => 'available',
    ], $clerk);
    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(), 'company_id' => $company->getKey(), 'status' => JobStatus::InProgress, 'job_type' => JobType::HeavyMachinery,
    ]);
    $prefill = app(JobCardFleetPrefillService::class);
    expect($prefill->forJob($job))->toBe(['equipment_reference' => null, 'machine_number' => null]);

    $this->actingAs($operations);
    app(CreateJobAssetAssignmentAction::class)->execute($job, ['fleet_asset_id' => $asset->getKey()], $operations);

    $this->actingAs($clerk);
    $this->get(JobCardResource::getUrl('create', ['job' => $job->getKey()]))
        ->assertOk();

    Livewire::withQueryParams(['job' => $job->getKey()])
        ->test(CreateJobCard::class)
        ->assertSet('data.job_id', $job->getKey())
        ->assertSet('data.equipment_reference', 'Reach Stacker')
        ->assertSet('data.machine_number', 'RS-003');

    expect($prefill->forJob($job))->toBe(['equipment_reference' => 'Reach Stacker', 'machine_number' => 'RS-003']);
    app(ReleaseJobAssetAssignmentAction::class)->execute($job->assetAssignments()->firstOrFail(), $operations);
    expect($prefill->forJob($job))->toBe(['equipment_reference' => null, 'machine_number' => null]);

    $cancelled = app(CreateJobAssetAssignmentAction::class)->execute($job, ['fleet_asset_id' => $asset->getKey()], $operations);
    app(CancelJobAssetAssignmentAction::class)->execute($cancelled, $operations, 'QA cancellation');
    expect($prefill->forJob($job))->toBe(['equipment_reference' => null, 'machine_number' => null]);
});

test('accounts review notes belong to accounts review and billing fields only apply after review', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $approver = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $submitter = $this->tenantUser($tenant, ['email' => 'ops-submit-job-card@example.test'], [RoleName::OperationsManager->value]);
    $clerk = $this->tenantUser($tenant, ['email' => 'clerk-update-card@example.test'], [RoleName::DataEntryClerk->value]);
    $approver->companies()->sync([$company->getKey()]);
    $submitter->companies()->sync([$company->getKey()]);
    $clerk->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::HeavyMachinery,
    ]);

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::Recorded,
        'operated_by' => 'Kwame Mensah',
        'machine_number' => 'FLT-18-03',
        'card_date' => '2026-08-15',
        'shift' => JobShift::Day,
        'equipment_reference' => 'Forklift FL-18',
        'client_endorsed' => true,
        'client_stamped' => true,
    ]);

    recordJobCardWorkEntry($jobCard, $submitter);

    $updatedByClerk = app(UpdateJobCardAction::class)->execute($jobCard, [
        'verification_notes' => 'Should not persist from data entry',
        'hourly_rate' => 175,
        'rate_currency' => 'GHS',
        'rate_notes' => 'Should not persist before verification',
        'officer_remarks' => 'Operational evidence updated',
    ], $clerk);

    expect($updatedByClerk->verification_notes)->toBeNull()
        ->and($updatedByClerk->hourly_rate)->toBeNull()
        ->and($updatedByClerk->rate_currency)->toBeNull()
        ->and($updatedByClerk->rate_notes)->toBeNull()
        ->and($updatedByClerk->officer_remarks)->toBe('Operational evidence updated');

    attachJobCardEvidence($jobCard, 'signed-card.txt');

    $submitted = app(SubmitJobCardAction::class)->execute($updatedByClerk, $submitter);
    $verified = app(ApproveJobCardAction::class)->execute($submitted, $approver, 'Hours and endorsement confirmed.');

    expect($verified->approval_status)->toBe(JobCardApprovalStatus::Verified)
        ->and($verified->verification_notes)->toBe('Hours and endorsement confirmed.')
        ->and($verified->submitted_by)->toBe($submitter->getKey())
        ->and($verified->submitted_at)->not->toBeNull();

    expect(fn () => app(UpdateJobCardAction::class)->execute($verified, [
        'hourly_rate' => 150,
        'rate_currency' => 'GHS',
        'exchange_rate' => 1,
        'billable_amount' => 1,
        'rate_notes' => 'Verified billing basis',
    ], $approver))->toThrow(BusinessException::class, 'Submitted and Accounts-reviewed client Job Card evidence is read-only until it is returned to Operations.');
});

test('pending verification verified and billing ready job cards cannot be updated directly and edit redirects to view', function () {
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

    foreach ([JobCardApprovalStatus::PendingVerification, JobCardApprovalStatus::Verified, JobCardApprovalStatus::BillingReady] as $status) {
        $jobCard = JobCard::factory()->create([
            'job_id' => $job->getKey(),
            'tenant_id' => $tenant->getKey(),
            'company_id' => $company->getKey(),
            'approval_status' => $status,
            'card_date' => '2026-08-16',
            'machine_number' => 'FLT-18-09',
            'operated_by' => 'Kwame Mensah',
            'total_hours' => 8,
        ]);

        $this->get(JobCardResource::getUrl('edit', ['record' => $jobCard]))
            ->assertRedirect(JobCardResource::getUrl('view', ['record' => $jobCard]));

        expect(fn () => app(UpdateJobCardAction::class)->execute($jobCard->fresh(), [
            'machine_number' => 'FLT-18-10',
            'operated_by' => 'Kwame Mensah',
        ], $actor))->toThrow(BusinessException::class, 'Submitted and Accounts-reviewed client Job Card evidence is read-only until it is returned to Operations.');
    }
});

test('returned job card can be edited and resubmitted after correction', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $submitter = $this->tenantUser($tenant, ['email' => 'returned-submit@example.test'], [RoleName::OperationsManager->value]);
    $verifier = $this->tenantUser($tenant, ['email' => 'returned-verifier@example.test'], [RoleName::CompanyAdministrator->value]);
    $submitter->companies()->sync([$company->getKey()]);
    $verifier->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::HeavyMachinery,
    ]);

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::Recorded,
        'card_date' => '2026-08-16',
        'shift' => JobShift::Day,
        'equipment_reference' => 'Forklift FL-18',
        'machine_number' => 'FLT-18-03',
        'operated_by' => 'Kwame Mensah',
        'client_endorsed' => true,
        'client_stamped' => true,
    ]);
    recordJobCardWorkEntry($jobCard, $submitter);
    attachJobCardEvidence($jobCard, 'returned-card.txt');

    $submitted = app(SubmitJobCardAction::class)->execute($jobCard, $submitter);
    $returned = app(ReturnJobCardAction::class)->execute($submitted, $verifier, 'Correct the machine number.');

    $updated = app(UpdateJobCardAction::class)->execute($returned, [
        'machine_number' => 'FLT-18-99',
        'operated_by' => 'Kwame Mensah',
    ], $submitter);

    $resubmitted = app(SubmitJobCardAction::class)->execute($updated, $submitter);

    expect($updated->machine_number)->toBe('FLT-18-99')
        ->and($resubmitted->approval_status)->toBe(JobCardApprovalStatus::PendingVerification)
        ->and($resubmitted->submitted_by)->toBe($submitter->getKey())
        ->and($resubmitted->submitted_at)->not->toBeNull();
});

test('data entry clerk cannot manage users or roles', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $clerk = $this->tenantUser($tenant, ['email' => 'clerk-no-admin@example.test'], [RoleName::DataEntryClerk->value]);
    $clerk->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    expect($clerk->can('users.view'))->toBeFalse()
        ->and($clerk->can('roles.view'))->toBeFalse()
        ->and($clerk->can('roles.manage'))->toBeFalse()
        ->and($clerk->can('settings.manage'))->toBeFalse();
});

test('starting a heavy machinery job does not auto-create a client job card', function () {
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
        'job_type' => JobType::HeavyMachinery,
    ]);

    $started = app(StartJobAction::class)->execute($job, $actor);

    expect($started->status)->toBe(JobStatus::InProgress)
        ->and($started->jobCards()->count())->toBe(0);
});

test('start job no longer requires an operator assignment', function () {
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

    $started = app(StartJobAction::class)->execute($job, $actor);

    expect($started->status)->toBe(JobStatus::InProgress)
        ->and($started->actual_start_date)->not->toBeNull();
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
        'approval_status' => JobCardApprovalStatus::Recorded,
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

test('job card verification is recorded without hourly rate and verified work entries move to billing ready through billing batches', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $approver = $this->tenantUser($tenant, [], [RoleName::FinanceManager->value]);
    $submitter = $this->tenantUser($tenant, ['email' => 'ops-submit-cross-company@example.test'], [RoleName::OperationsManager->value]);
    $approver->companies()->sync([$companyA->getKey()]);
    $submitter->companies()->sync([$companyA->getKey()]);
    $outsider = $this->tenantUser($tenant, ['email' => 'outsider@example.test'], [RoleName::OperationsManager->value]);
    $outsider->companies()->sync([$companyB->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    $client = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyA->getKey(),
        'legal_name' => 'GPHA',
    ]);

    app(CreateRateAgreementAction::class)->execute([
        'client_id' => $client->getKey(),
        'name' => 'GPHA accepted tariff',
        'reference' => 'RA-GPHA-ACCEPTANCE',
        'effective_from' => '2026-08-01',
        'effective_to' => '2026-12-31',
        'status' => RateAgreementStatus::Active->value,
        'lines' => [[
            'equipment_reference' => 'Forklift FL-18',
            'billing_unit' => 'hourly',
            'currency' => 'GHS',
            'rate' => 150.00,
        ]],
    ], $approver);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyA->getKey(),
        'client_id' => $client->getKey(),
    ]);

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyA->getKey(),
        'client_id' => $client->getKey(),
        'card_date' => now()->toDateString(),
        'shift' => JobShift::Day,
        'equipment_reference' => 'Forklift FL-18',
        'machine_number' => 'FLT-18-02',
        'operated_by' => 'Kwame Mensah',
        'client_endorsed' => true,
        'client_stamped' => true,
    ]);
    recordJobCardWorkEntry($jobCard, $submitter);
    attachJobCardEvidence($jobCard, 'job-card-verification.txt');

    $submitted = app(SubmitJobCardAction::class)->execute($jobCard, $submitter);
    $approved = app(ApproveJobCardAction::class)->execute($submitted, $approver, 'Card reviewed and approved.');

    expect($approved->approval_status)->toBe(JobCardApprovalStatus::Verified)
        ->and($approved->submitted_by)->toBe($submitter->getKey())
        ->and($approved->verified_by)->toBe($approver->getKey())
        ->and($approved->verified_at)->not->toBeNull()
        ->and($approved->hourly_rate)->toBeNull()
        ->and($approved->rate_currency)->toBeNull()
        ->and($approved->billable_amount)->toBeNull()
        ->and(Gate::forUser($outsider)->allows('view', $approved))->toBeFalse();

    $batch = app(CreateBillingBatchAction::class)->execute([
        'client_id' => $client->getKey(),
        'notes' => 'Cross-company billing validation',
    ], $approver);

    expect(app(BillingBatchEligibilityService::class)->eligibleWorkEntries($batch)->pluck('job_card_id')->all())
        ->toContain($approved->getKey());

    $batch = app(AddJobCardsToBillingBatchAction::class)->execute(
        $batch,
        [$approved->workEntries()->firstOrFail()->getKey()],
        $approver,
    );
    $prepared = app(PrepareBillingBatchAction::class)->execute($batch, $approver);

    expect($prepared->status)->toBe(BillingBatchStatus::Prepared)
        ->and($approved->fresh()->approval_status)->toBe(JobCardApprovalStatus::BillingReady);
});

test('data entry clerk cannot verify or return directly and submitter cannot verify own submission', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $operationsManager = $this->tenantUser($tenant, ['email' => 'clerk-no-verify@example.test'], [RoleName::DataEntryClerk->value]);
    $companyAdministrator = $this->tenantUser($tenant, ['email' => 'company-admin-self-verify@example.test'], [RoleName::CompanyAdministrator->value]);
    $operationsManager->companies()->sync([$company->getKey()]);
    $companyAdministrator->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::HeavyMachinery,
    ]);

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::PendingVerification,
        'card_date' => '2026-08-15',
        'machine_number' => 'FLT-18-03',
        'total_hours' => 8,
        'client_endorsed' => true,
        'client_stamped' => true,
        'submitted_by' => $companyAdministrator->getKey(),
        'submitted_at' => now(),
    ]);
    $jobCard->addMediaFromString('signed')->usingFileName('signed-card.txt')->toMediaCollection('job-card-documents');

    expect(fn () => app(ApproveJobCardAction::class)->execute($jobCard->fresh(), $operationsManager))
        ->toThrow(BusinessException::class, 'You are not allowed to review this client Job Card for billing.');

    expect(fn () => app(ReturnJobCardAction::class)->execute($jobCard->fresh(), $operationsManager, 'No permission'))
        ->toThrow(BusinessException::class, 'You are not allowed to return this client Job Card to Operations.');

    $selfSubmitted = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::Recorded,
        'card_date' => '2026-08-15',
        'shift' => JobShift::Day,
        'equipment_reference' => 'Forklift FL-18',
        'machine_number' => 'FLT-18-04',
        'operated_by' => 'Kwame Mensah',
        'client_endorsed' => true,
        'client_stamped' => true,
    ]);
    recordJobCardWorkEntry($selfSubmitted, $companyAdministrator);
    attachJobCardEvidence($selfSubmitted, 'self-signed-card.txt');

    $submitted = app(SubmitJobCardAction::class)->execute($selfSubmitted, $companyAdministrator);

    expect($submitted->submitted_by)->toBe($companyAdministrator->getKey())
        ->and($submitted->submitted_at)->not->toBeNull();

    expect(fn () => app(ApproveJobCardAction::class)->execute($submitted->fresh(), $companyAdministrator))
        ->toThrow(BusinessException::class, 'You cannot review a client Job Card for billing when you submitted it to Accounts.');

    expect(fn () => app(ReturnJobCardAction::class)->execute($submitted->fresh(), $companyAdministrator, 'Self return'))
        ->toThrow(BusinessException::class, 'You cannot return a client Job Card to Operations when you submitted it to Accounts.');
});

test('finance manager can review and return another users submission and resubmission updates the latest submission audit', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $firstSubmitter = $this->tenantUser($tenant, ['email' => 'ops-first-submit@example.test'], [RoleName::OperationsManager->value]);
    $secondSubmitter = $this->tenantUser($tenant, ['email' => 'ops-second-submit@example.test'], [RoleName::OperationsManager->value]);
    $verifier = $this->tenantUser($tenant, ['email' => 'finance-return@example.test'], [RoleName::FinanceManager->value]);
    $firstSubmitter->companies()->sync([$company->getKey()]);
    $secondSubmitter->companies()->sync([$company->getKey()]);
    $verifier->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::HeavyMachinery,
    ]);

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'approval_status' => JobCardApprovalStatus::Recorded,
        'card_date' => '2026-08-15',
        'shift' => JobShift::Day,
        'equipment_reference' => 'Forklift FL-18',
        'machine_number' => 'FLT-18-05',
        'operated_by' => 'Kwame Mensah',
        'client_endorsed' => true,
        'client_stamped' => true,
    ]);
    recordJobCardWorkEntry($jobCard, $firstSubmitter);
    attachJobCardEvidence($jobCard, 'resubmitted-card.txt');

    $submitted = app(SubmitJobCardAction::class)->execute($jobCard, $firstSubmitter);
    $firstSubmissionTime = $submitted->submitted_at;

    expect($submitted->submitted_by)->toBe($firstSubmitter->getKey())
        ->and($firstSubmissionTime)->not->toBeNull();

    $returned = app(ReturnJobCardAction::class)->execute($submitted, $verifier, 'Correct the supporting evidence.');

    expect($returned->approval_status)->toBe(JobCardApprovalStatus::Returned)
        ->and($returned->return_reason)->toBe('Correct the supporting evidence.')
        ->and($returned->returned_by)->toBe($verifier->getKey())
        ->and($returned->returned_at)->not->toBeNull()
        ->and($returned->submitted_by)->toBe($firstSubmitter->getKey());

    $staleSubmissionTime = $returned->submitted_at?->subMinute();

    $returned->forceFill([
        'submitted_at' => $staleSubmissionTime,
    ])->saveQuietly();

    $resubmitted = app(SubmitJobCardAction::class)->execute($returned->fresh(), $secondSubmitter);

    expect($resubmitted->submitted_by)->toBe($secondSubmitter->getKey())
        ->and($resubmitted->submitted_at)->not->toBeNull()
        ->and($resubmitted->submitted_at?->greaterThan($staleSubmissionTime))->toBeTrue();

    $verified = app(ApproveJobCardAction::class)->execute($resubmitted, $verifier, 'Verified after correction.');

    expect($verified->approval_status)->toBe(JobCardApprovalStatus::Verified)
        ->and($verified->verified_by)->toBe($verifier->getKey())
        ->and($verified->verification_notes)->toBe('Verified after correction.');
});

test('finance manager receives only accounts review permissions needed for phase 1', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $financeManager = $this->tenantUser($tenant, ['email' => 'finance-permissions@example.test'], [RoleName::FinanceManager->value]);

    expect($financeManager->hasPermissionTo('jobs.view_any'))->toBeTrue()
        ->and($financeManager->hasPermissionTo('jobs.view'))->toBeTrue()
        ->and($financeManager->hasPermissionTo('job_cards.verify'))->toBeTrue()
        ->and($financeManager->hasPermissionTo('job_cards.bill'))->toBeTrue()
        ->and($financeManager->hasPermissionTo('jobs.create'))->toBeFalse()
        ->and($financeManager->hasPermissionTo('jobs.schedule'))->toBeFalse()
        ->and($financeManager->hasPermissionTo('jobs.start'))->toBeFalse()
        ->and($financeManager->hasPermissionTo('jobs.complete'))->toBeFalse()
        ->and($financeManager->hasPermissionTo('jobs.cancel'))->toBeFalse()
        ->and($financeManager->hasPermissionTo('jobs.submit'))->toBeFalse()
        ->and($financeManager->hasPermissionTo('users.create'))->toBeFalse()
        ->and($financeManager->hasPermissionTo('roles.assign'))->toBeFalse()
        ->and($financeManager->hasPermissionTo('settings.manage'))->toBeFalse();
});

test('cross company and cross tenant verifiers are denied and billing ready still requires verified state', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $otherTenant = $this->tenant();
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $foreignCompany = $this->company($otherTenant, ['name' => 'External Marine']);
    $submitter = $this->tenantUser($tenant, ['email' => 'ops-cross-scope@example.test'], [RoleName::OperationsManager->value]);
    $crossCompanyVerifier = $this->tenantUser($tenant, ['email' => 'cross-company-verifier@example.test'], [RoleName::CompanyAdministrator->value]);
    $crossTenantVerifier = $this->tenantUser($otherTenant, ['email' => 'cross-tenant-verifier@example.test'], [RoleName::CompanyAdministrator->value]);
    $submitter->companies()->sync([$companyA->getKey()]);
    $crossCompanyVerifier->companies()->sync([$companyB->getKey()]);
    $crossTenantVerifier->companies()->sync([$foreignCompany->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    $job = Job::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyA->getKey(),
        'status' => JobStatus::InProgress,
        'job_type' => JobType::HeavyMachinery,
    ]);

    $jobCard = JobCard::factory()->create([
        'job_id' => $job->getKey(),
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyA->getKey(),
        'approval_status' => JobCardApprovalStatus::Recorded,
        'card_date' => '2026-08-15',
        'shift' => JobShift::Day,
        'equipment_reference' => 'Forklift FL-18',
        'machine_number' => 'FLT-18-06',
        'operated_by' => 'Kwame Mensah',
        'client_endorsed' => true,
        'client_stamped' => true,
    ]);
    recordJobCardWorkEntry($jobCard, $submitter);
    attachJobCardEvidence($jobCard, 'cross-scope-card.txt');

    $submitted = app(SubmitJobCardAction::class)->execute($jobCard, $submitter);

    expect(fn () => app(ApproveJobCardAction::class)->execute($submitted->fresh(), $crossCompanyVerifier))
        ->toThrow(BusinessException::class, 'You are not allowed to review this client Job Card for billing.');

    expect(fn () => app(ApproveJobCardAction::class)->execute($submitted->fresh(), $crossTenantVerifier))
        ->toThrow(BusinessException::class, 'You are not allowed to review this client Job Card for billing.');

    expect(fn () => app(MarkJobCardBillingReadyAction::class)->execute($submitted->fresh(), $crossCompanyVerifier))
        ->toThrow(BusinessException::class, 'You are not allowed to mark this client Job Card as billing ready.');
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
