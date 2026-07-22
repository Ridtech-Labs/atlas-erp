<?php

use App\Administration\Enums\RoleName;
use App\Core\Shared\Exceptions\BusinessException;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use App\Operations\Actions\Jobs\ApproveJobAction;
use App\Operations\Actions\Jobs\CancelJobAction;
use App\Operations\Actions\Jobs\CompleteJobAction;
use App\Operations\Actions\Jobs\CreateJobAction;
use App\Operations\Actions\Jobs\ScheduleJobAction;
use App\Operations\Actions\Jobs\StartJobAction;
use App\Operations\Actions\Jobs\SubmitJobForApprovalAction;
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
        'is_active' => true,
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
        ->and($job->job_number)->toStartWith('JOB-')
        ->and($job->planned_start_date?->toDateString())->toBe(now()->toDateString())
        ->and($job->planned_end_date?->toDateString())->toBe(now()->addDay()->toDateString());

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
    $client = Client::factory()->create(['tenant_id' => $tenant->getKey()]);
    $site = ClientSite::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'client_id' => $client->getKey(),
        'is_active' => true,
    ]);

    $job = app(CreateJobAction::class)->execute([
        'client_id' => $client->getKey(),
        'client_site_id' => $site->getKey(),
        'title' => 'Generator installation',
        'priority' => 'high',
        'planned_start_date' => now()->toDateString(),
        'planned_end_date' => now()->addDay()->toDateString(),
    ], $actor);

    $job = app(SubmitJobForApprovalAction::class)->execute($job, $actor);
    expect($job->status)->toBe(JobStatus::PendingApproval);

    $job = app(ApproveJobAction::class)->execute($job, $actor);
    expect($job->status)->toBe(JobStatus::Approved)
        ->and($job->approved_by)->toBe($actor->getKey())
        ->and($job->approved_at)->not->toBeNull();

    $job = app(ScheduleJobAction::class)->execute($job, $actor);
    $job = app(StartJobAction::class)->execute($job, $actor);
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
