<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs;

use App\Operations\Actions\Jobs\Transitions\TransitionsJobState;
use App\Operations\Enums\JobStatus;

class ResumeJobAction extends TransitionsJobState
{
    protected function ability(): string
    {
        return 'resume';
    }

    protected function event(): string
    {
        return 'job.resumed';
    }

    protected function description(): string
    {
        return 'Job resumed';
    }

    protected function targetStatus(): JobStatus
    {
        return JobStatus::InProgress;
    }
}
