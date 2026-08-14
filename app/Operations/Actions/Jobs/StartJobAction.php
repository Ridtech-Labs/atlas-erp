<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs;

use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use App\Operations\Actions\JobCards\CreateJobCardAction;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use App\Operations\Services\JobWorkflowService;
use App\Operations\Traits\AppliesJobAudit;
use Illuminate\Support\Facades\DB;

class StartJobAction
{
    use AppliesJobAudit;

    public function __construct(
        private readonly JobWorkflowService $workflow,
        private readonly AdministrationActivityLogger $logger,
        private readonly CreateJobCardAction $createJobCard,
    ) {}

    /**
     * @param  array<string, mixed>  $context
     */
    public function execute(Job $job, User $actor, array $context = []): Job
    {
        if (! $actor->can('start', $job)) {
            throw new BusinessException('You are not allowed to perform this workflow action.', 403);
        }

        return DB::transaction(function () use ($job, $actor): Job {
            $job = Job::query()->whereKey($job->getKey())->lockForUpdate()->firstOrFail();
            $existingFirstCard = $job->jobCards()->orderBy('card_date')->orderBy('id')->first();

            if ((string) $job->getRawOriginal('status') === JobStatus::InProgress->value && $existingFirstCard !== null) {
                return $job->refresh();
            }

            $this->workflow->assertCanTransition($job, JobStatus::InProgress);

            if ($job->plannedOperatorName() === null) {
                throw new BusinessException('Assign an operator before starting this Job.', 422);
            }

            if ($job->getAttribute('actual_start_date') === null) {
                $job->actual_start_date = now()->toDateString();
            }

            $job->status = JobStatus::InProgress->value;
            $this->stampUpdateAudit($job, $actor);
            $job->save();

            if ($existingFirstCard === null) {
                $shift = $job->getRawOriginal('shift');

                $this->createJobCard->execute($job, [
                    'source' => 'job_start',
                    'shift' => is_string($shift) && $shift !== '' ? $shift : null,
                    'equipment_reference' => $job->equipment_requirement,
                    'operator_id' => $job->assigned_operator_id,
                    'operated_by' => $job->assigned_operator_name,
                ], $actor);
            }

            $this->logger->log('job.started', 'Job started', $actor, $job, [
                'tenant_id' => $job->tenant_id,
                'company_id' => $job->company_id,
                'job_number' => $job->job_number,
            ]);

            return $job->refresh();
        });
    }
}
