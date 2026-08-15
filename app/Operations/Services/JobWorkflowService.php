<?php

declare(strict_types=1);

namespace App\Operations\Services;

use App\Core\Shared\Exceptions\BusinessException;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;

class JobWorkflowService
{
    /**
     * @return array<string, list<string>>
     */
    public function transitions(Job $job): array
    {
        if ($this->approvalRequired($job)) {
            return [
                JobStatus::Draft->value => [JobStatus::PendingApproval->value, JobStatus::Cancelled->value],
                JobStatus::PendingApproval->value => [JobStatus::Draft->value, JobStatus::Approved->value, JobStatus::Cancelled->value],
                JobStatus::Approved->value => [JobStatus::Scheduled->value, JobStatus::Cancelled->value],
                JobStatus::Scheduled->value => [JobStatus::InProgress->value, JobStatus::Cancelled->value],
                JobStatus::InProgress->value => [JobStatus::OnHold->value, JobStatus::Completed->value, JobStatus::Cancelled->value],
                JobStatus::OnHold->value => [JobStatus::InProgress->value, JobStatus::Cancelled->value],
                JobStatus::Completed->value => [],
                JobStatus::Cancelled->value => [],
            ];
        }

        return [
            JobStatus::Draft->value => [JobStatus::Scheduled->value, JobStatus::Cancelled->value],
            JobStatus::PendingApproval->value => [JobStatus::Draft->value, JobStatus::Approved->value, JobStatus::Cancelled->value],
            JobStatus::Approved->value => [JobStatus::Scheduled->value, JobStatus::Cancelled->value],
            JobStatus::Scheduled->value => [JobStatus::InProgress->value, JobStatus::Cancelled->value],
            JobStatus::InProgress->value => [JobStatus::OnHold->value, JobStatus::Completed->value, JobStatus::Cancelled->value],
            JobStatus::OnHold->value => [JobStatus::InProgress->value, JobStatus::Cancelled->value],
            JobStatus::Completed->value => [],
            JobStatus::Cancelled->value => [],
        ];
    }

    public function approvalRequired(Job $job): bool
    {
        return $job->isTrucking();
    }

    public function canCancel(Job $job): bool
    {
        return in_array(
            (string) $job->getRawOriginal('status'),
            [
                JobStatus::Draft->value,
                JobStatus::PendingApproval->value,
                JobStatus::Approved->value,
                JobStatus::Scheduled->value,
                JobStatus::InProgress->value,
                JobStatus::OnHold->value,
            ],
            true,
        );
    }

    public function assertCanTransition(Job $job, JobStatus $to): void
    {
        $currentStatus = JobStatus::from((string) $job->getRawOriginal('status'));
        $allowed = $this->transitions($job)[$currentStatus->value] ?? [];

        if (! in_array($to->value, $allowed, true)) {
            throw new BusinessException("Jobs in {$currentStatus->label()} status cannot transition to {$to->label()}.", 422);
        }
    }
}
