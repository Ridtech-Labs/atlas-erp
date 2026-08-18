<?php

declare(strict_types=1);

namespace App\Operations\Actions\JobCardWorkEntries;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use App\Operations\Models\JobCard;
use App\Operations\Models\JobCardWorkEntry;
use App\Operations\Support\JobCardAggregateService;
use App\Operations\Support\JobCardWorkEntryCalculator;
use Illuminate\Support\Facades\DB;

class CreateJobCardWorkEntryAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly JobCardAggregateService $aggregates,
        private readonly JobCardWorkEntryCalculator $calculator,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(JobCard $jobCard, array $data, User $actor): JobCardWorkEntry
    {
        if (! $actor->hasPermissionTo(PermissionName::JobsUpdate->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $jobCard->company_id, $jobCard->tenant_id)) {
            throw new BusinessException('You are not allowed to add work entries to this job card.', 403);
        }

        if ($jobCard->workEntriesAreLocked()) {
            throw new BusinessException('Job card work entries are read-only once the Job Card has been submitted to Accounts.', 422);
        }

        return DB::transaction(function () use ($jobCard, $data, $actor): JobCardWorkEntry {
            $hours = $this->calculator->calculate($jobCard, $data);

            $entry = $jobCard->workEntries()->create([
                'vessel' => $data['vessel'] ?? null,
                'work_area' => $data['work_area'] ?? null,
                'officer_name' => $data['officer_name'] ?? null,
                'notes' => $data['notes'] ?? null,
                ...$hours,
            ]);

            $this->aggregates->syncTotals($jobCard);

            $this->logger->log('job_card_work_entry.created', 'Job card work entry created', $actor, $entry, [
                'tenant_id' => $jobCard->tenant_id,
                'company_id' => $jobCard->company_id,
                'job_card_id' => $jobCard->getKey(),
            ]);

            return $entry->refresh();
        });
    }
}
