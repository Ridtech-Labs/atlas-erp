<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs;

use App\Operations\Actions\Jobs\Transitions\TransitionsJobState;
use App\Operations\Enums\JobStatus;

class ReturnJobToDraftAction extends TransitionsJobState
{
    protected function ability(): string
    {
        return 'update';
    }

    protected function event(): string
    {
        return 'job.returned_to_draft';
    }

    protected function description(): string
    {
        return 'Job returned to draft';
    }

    protected function targetStatus(): JobStatus
    {
        return JobStatus::Draft;
    }
}
