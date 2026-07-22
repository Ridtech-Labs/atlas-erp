<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs\Transitions;

use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use App\Operations\Services\JobWorkflowService;
use Illuminate\Support\Facades\DB;

abstract class TransitionsJobState
{
    public function __construct(
        protected readonly JobWorkflowService $workflow,
        protected readonly AdministrationActivityLogger $logger,
    ) {}

    /**
     * @param  array<string, mixed>  $context
     */
    public function execute(Job $job, User $actor, array $context = []): Job
    {
        if (! $actor->can($this->ability(), $job)) {
            throw new BusinessException('You are not allowed to perform this workflow action.', 403);
        }

        $this->workflow->assertCanTransition($job, $this->targetStatus());

        return DB::transaction(function () use ($job, $actor, $context): Job {
            $job->refresh();
            $this->workflow->assertCanTransition($job, $this->targetStatus());

            $this->mutate($job, $actor, $context);
            $job->forceFill([
                'status' => $this->targetStatus()->value,
                'updated_by' => $actor->getKey(),
            ]);
            $job->save();

            $this->logger->log($this->event(), $this->description(), $actor, $job, [
                'tenant_id' => $job->tenant_id,
                'job_number' => $job->job_number,
            ]);

            return $job->refresh();
        });
    }

    abstract protected function ability(): string;

    abstract protected function event(): string;

    abstract protected function description(): string;

    abstract protected function targetStatus(): JobStatus;

    /**
     * @param  array<string, mixed>  $context
     */
    protected function mutate(Job $job, User $actor, array $context): void {}
}
