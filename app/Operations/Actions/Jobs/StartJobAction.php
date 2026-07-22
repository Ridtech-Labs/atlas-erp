<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs;

use App\Models\User;
use App\Operations\Actions\Jobs\Transitions\TransitionsJobState;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;

class StartJobAction extends TransitionsJobState
{
    protected function ability(): string
    {
        return 'start';
    }

    protected function event(): string
    {
        return 'job.started';
    }

    protected function description(): string
    {
        return 'Job started';
    }

    protected function targetStatus(): JobStatus
    {
        return JobStatus::InProgress;
    }

    protected function mutate(Job $job, User $actor, array $context): void
    {
        if ($job->getAttribute('actual_start_date') === null) {
            $job->forceFill([
                'actual_start_date' => now(),
            ]);
        }
    }
}
