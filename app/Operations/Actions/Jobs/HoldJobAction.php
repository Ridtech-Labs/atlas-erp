<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs;

use App\Operations\Actions\Jobs\Transitions\TransitionsJobState;
use App\Operations\Enums\JobStatus;

class HoldJobAction extends TransitionsJobState
{
    protected function ability(): string
    {
        return 'hold';
    }

    protected function event(): string
    {
        return 'job.placed_on_hold';
    }

    protected function description(): string
    {
        return 'Job placed on hold';
    }

    protected function targetStatus(): JobStatus
    {
        return JobStatus::OnHold;
    }
}
