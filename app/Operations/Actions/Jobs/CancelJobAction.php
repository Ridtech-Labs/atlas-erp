<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs;

use App\Core\Shared\Exceptions\BusinessException;
use App\Fleet\Actions\CancelJobAssetAssignmentAction;
use App\Fleet\Enums\JobAssetAssignmentStatus;
use App\Models\User;
use App\Operations\Actions\Jobs\Transitions\TransitionsJobState;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;

class CancelJobAction extends TransitionsJobState
{
    protected function ability(): string
    {
        return 'cancel';
    }

    protected function event(): string
    {
        return 'job.cancelled';
    }

    protected function description(): string
    {
        return 'Job cancelled';
    }

    protected function targetStatus(): JobStatus
    {
        return JobStatus::Cancelled;
    }

    protected function mutate(Job $job, User $actor, array $context): void
    {
        $reason = trim((string) ($context['cancellation_reason'] ?? ''));

        if ($reason === '') {
            throw new BusinessException('A cancellation reason is required.', 422);
        }
        if ($job->assetAssignments()->where('status', JobAssetAssignmentStatus::Dispatched->value)->exists()) {
            throw new BusinessException('Return all dispatched Fleet assets before cancelling this Job.', 422);
        }

        $job->forceFill([
            'cancelled_at' => now(),
            'cancelled_by' => $actor->getKey(),
            'cancellation_reason' => $reason,
        ]);

        $job->assetAssignments()
            ->where('status', JobAssetAssignmentStatus::Assigned->value)
            ->get()
            ->each(fn ($assignment) => app(CancelJobAssetAssignmentAction::class)->execute($assignment, $actor, 'Job cancelled: '.$reason));
    }
}
