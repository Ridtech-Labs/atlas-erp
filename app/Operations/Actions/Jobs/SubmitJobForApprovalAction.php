<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs;

use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use App\Operations\Actions\Jobs\Transitions\TransitionsJobState;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;

class SubmitJobForApprovalAction extends TransitionsJobState
{
    public function execute(Job $job, User $actor, array $context = []): Job
    {
        if (! $this->workflow->approvalRequired($job)) {
            throw new BusinessException('This job type does not require the legacy approval chain before scheduling.', 422);
        }

        return parent::execute($job, $actor, $context);
    }

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
