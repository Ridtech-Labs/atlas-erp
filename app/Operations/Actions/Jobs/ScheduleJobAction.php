<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs;

use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use App\Operations\Actions\Jobs\Transitions\TransitionsJobState;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;

class ScheduleJobAction extends TransitionsJobState
{
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
        if ($job->planned_start_date === null) {
            throw new BusinessException('A planned start date is required before scheduling a job.', 422);
        }
    }
}
