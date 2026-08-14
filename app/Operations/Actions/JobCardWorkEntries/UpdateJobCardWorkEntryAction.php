<?php

declare(strict_types=1);

namespace App\Operations\Actions\JobCardWorkEntries;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Models\JobCardWorkEntry;
use App\Operations\Support\JobCardWorkEntryCalculator;

class UpdateJobCardWorkEntryAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly JobCardWorkEntryCalculator $calculator,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(JobCardWorkEntry $entry, array $data, User $actor): JobCardWorkEntry
    {
        $jobCard = $entry->jobCard()->firstOrFail();

        if (! $actor->hasPermissionTo(PermissionName::JobsUpdate->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $jobCard->company_id, $jobCard->tenant_id)) {
            throw new BusinessException('You are not allowed to update this job card work entry.', 403);
        }

        if ((string) $jobCard->getRawOriginal('approval_status') === JobCardApprovalStatus::Approved->value
            && ! $actor->hasPermissionTo(PermissionName::JobsApprove->value)) {
            throw new BusinessException('Approved job cards are read-only for operational users.', 422);
        }

        $hours = $this->calculator->calculate($jobCard, $data);

        $entry->fill([
            'vessel' => $data['vessel'] ?? null,
            'work_area' => $data['work_area'] ?? null,
            'officer_name' => $data['officer_name'] ?? null,
            'notes' => $data['notes'] ?? null,
            ...$hours,
        ]);
        $entry->save();

        $this->logger->log('job_card_work_entry.updated', 'Job card work entry updated', $actor, $entry, [
            'tenant_id' => $jobCard->tenant_id,
            'company_id' => $jobCard->company_id,
            'job_card_id' => $jobCard->getKey(),
        ]);

        return $entry->refresh();
    }
}
