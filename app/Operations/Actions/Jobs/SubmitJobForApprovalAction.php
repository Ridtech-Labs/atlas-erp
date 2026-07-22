<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs;

use App\Operations\Actions\Jobs\Transitions\TransitionsJobState;
use App\Operations\Enums\JobStatus;

class SubmitJobForApprovalAction extends TransitionsJobState
{
    protected function ability(): string
    {
        return 'submit';
    }

    protected function event(): string
    {
        return 'job.submitted';
    }

    protected function description(): string
    {
        return 'Job submitted for approval';
    }

    protected function targetStatus(): JobStatus
    {
        return JobStatus::PendingApproval;
    }
}
