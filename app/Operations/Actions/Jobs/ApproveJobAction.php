<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs;

use App\Models\User;
use App\Operations\Actions\Jobs\Transitions\TransitionsJobState;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;

class ApproveJobAction extends TransitionsJobState
{
    protected function ability(): string
    {
        return 'approve';
    }

    protected function event(): string
    {
        return 'job.approved';
    }

    protected function description(): string
    {
        return 'Job approved';
    }

    protected function targetStatus(): JobStatus
    {
        return JobStatus::Approved;
    }

    protected function mutate(Job $job, User $actor, array $context): void
    {
        $job->forceFill([
            'approved_at' => now(),
            'approved_by' => $actor->getKey(),
        ]);
    }
}
