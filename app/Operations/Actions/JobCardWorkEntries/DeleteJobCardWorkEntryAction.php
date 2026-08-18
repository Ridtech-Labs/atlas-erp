<?php

declare(strict_types=1);

namespace App\Operations\Actions\JobCardWorkEntries;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use App\Operations\Models\JobCardWorkEntry;
use App\Operations\Support\JobCardAggregateService;
use Illuminate\Support\Facades\DB;

class DeleteJobCardWorkEntryAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly JobCardAggregateService $aggregates,
    ) {}

    public function execute(JobCardWorkEntry $entry, User $actor): void
    {
        $jobCard = $entry->jobCard()->firstOrFail();

        if (! $actor->hasPermissionTo(PermissionName::JobsUpdate->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $jobCard->company_id, $jobCard->tenant_id)) {
            throw new BusinessException('You are not allowed to delete this job card work entry.', 403);
        }

        if ($jobCard->workEntriesAreLocked()) {
            throw new BusinessException('Job card work entries are read-only once the Job Card has been submitted to Accounts.', 422);
        }

        DB::transaction(function () use ($entry, $jobCard, $actor): void {
            $this->logger->log('job_card_work_entry.deleted', 'Job card work entry deleted', $actor, $entry, [
                'tenant_id' => $jobCard->tenant_id,
                'company_id' => $jobCard->company_id,
                'job_card_id' => $jobCard->getKey(),
            ]);

            $entry->delete();

            $this->aggregates->syncTotals($jobCard);
        });
    }
}
