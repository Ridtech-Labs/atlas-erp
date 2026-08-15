<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs;

use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use App\Operations\Actions\Jobs\Transitions\TransitionsJobState;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use App\Operations\Services\JobWorkflowService;
use App\Operations\Support\JobPlanningReadinessService;

class ScheduleJobAction extends TransitionsJobState
{
    public function __construct(
        JobWorkflowService $workflow,
        AdministrationActivityLogger $logger,
        private readonly JobPlanningReadinessService $planningReadiness,
    ) {
        parent::__construct($workflow, $logger);
    }

    protected function ability(): string
    {
        return 'schedule';
    }

    protected function event(): string
    {
        return 'job.scheduled';
    }

    protected function description(): string
    {
        return 'Job scheduled';
    }

    protected function targetStatus(): JobStatus
    {
        return JobStatus::Scheduled;
    }

    protected function mutate(Job $job, User $actor, array $context): void
    {
        $missing = $this->planningReadiness->missingLabels($job);

        if ($missing !== []) {
            throw new BusinessException('Planning is incomplete. Complete: '.implode(', ', $missing).'.', 422);
        }
    }
}
