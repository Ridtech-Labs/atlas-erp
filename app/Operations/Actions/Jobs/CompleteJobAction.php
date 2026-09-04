<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs;

use App\Core\Shared\Exceptions\BusinessException;
use App\Fleet\Actions\ReleaseJobAssetAssignmentAction;
use App\Fleet\Enums\JobAssetAssignmentStatus;
use App\Models\User;
use App\Operations\Actions\Jobs\Transitions\TransitionsJobState;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;

class CompleteJobAction extends TransitionsJobState
{
    protected function ability(): string
    {
        return 'complete';
    }

    protected function event(): string
    {
        return 'job.completed';
    }

    protected function description(): string
    {
        return 'Job completed';
    }

    protected function targetStatus(): JobStatus
    {
        return JobStatus::Completed;
    }

    protected function mutate(Job $job, User $actor, array $context): void
    {
        $actualEnd = $context['actual_end_date'] ?? now();
        $job->loadMissing('jobCards');

        if ($job->actual_start_date === null) {
            throw new BusinessException('A job must have an actual start date before it can be completed.', 422);
        }

        if (! $job->readyForCompletion()) {
            throw new BusinessException(
                $job->isTrucking()
                    ? 'All recorded Waybills must be verified or billing ready before the job can be completed.'
                    : 'All recorded client Job Cards must be billing ready before the job can be completed.',
                422,
            );
        }

        if ($actualEnd < $job->actual_start_date) {
            throw new BusinessException('Actual end date cannot precede actual start date.', 422);
        }

        $job->forceFill([
            'actual_end_date' => $actualEnd,
            'completed_at' => $actualEnd,
            'completed_by' => $actor->getKey(),
        ]);

        $job->assetAssignments()
            ->where('status', JobAssetAssignmentStatus::Assigned->value)
            ->get()
            ->each(fn ($assignment) => app(ReleaseJobAssetAssignmentAction::class)->execute($assignment, $actor, 'Job completed before dispatch tracking is available.'));
    }
}
