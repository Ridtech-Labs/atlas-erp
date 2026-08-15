<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs;

use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use Illuminate\Support\Facades\DB;

class DeleteJobAction
{
    public function __construct(
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(Job $job, User $actor): void
    {
        if (! $actor->can('delete', $job)) {
            throw new BusinessException('You are not allowed to delete this job.', 403);
        }

        DB::transaction(function () use ($job, $actor): void {
            $job = Job::query()
                ->withCount(['jobCards', 'waybills'])
                ->whereKey($job->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ((string) $job->getRawOriginal('status') !== JobStatus::Draft->value) {
                throw new BusinessException('Only draft jobs can be deleted.', 422);
            }

            if ($job->job_cards_count > 0) {
                throw new BusinessException('Jobs with client Job Cards cannot be deleted.', 422);
            }

            if ($job->waybills_count > 0) {
                throw new BusinessException('Jobs with Waybills cannot be deleted.', 422);
            }

            $job->delete();

            $this->logger->log('job.deleted', 'Draft job deleted', $actor, $job, [
                'tenant_id' => $job->tenant_id,
                'company_id' => $job->company_id,
                'job_number' => $job->job_number,
            ]);
        });
    }
}
