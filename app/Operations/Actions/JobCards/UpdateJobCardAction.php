<?php

declare(strict_types=1);

namespace App\Operations\Actions\JobCards;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Models\JobCard;
use App\Operations\Support\OperatorAssignmentService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateJobCardAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly OperatorAssignmentService $operators,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @param  list<string>  $attachmentPaths
     */
    public function execute(JobCard $jobCard, array $data, User $actor, array $attachmentPaths = []): JobCard
    {
        if (! $actor->hasPermissionTo(PermissionName::JobsUpdate->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $jobCard->company_id, $jobCard->tenant_id)) {
            throw new BusinessException('You are not allowed to update this job card.', 403);
        }

        if ((string) $jobCard->getRawOriginal('approval_status') === JobCardApprovalStatus::Approved->value
            && ! $actor->hasPermissionTo(PermissionName::JobsApprove->value)) {
            throw new BusinessException('Approved job cards are read-only for operational users.', 422);
        }

        return DB::transaction(function () use ($jobCard, $data, $actor, $attachmentPaths): JobCard {
            $previousOperatorName = $jobCard->operatorDisplayName();
            $assignment = $this->operators->resolveAssignment(
                $data['operator_id'] ?? $jobCard->operator_id,
                array_key_exists('operated_by', $data) ? $data['operated_by'] : $jobCard->operated_by,
                $jobCard->tenant_id,
                $jobCard->company_id,
                false,
            );
            $resolvedOperators = $this->operators->resolveOperatorEntries($data['operators'] ?? [], $jobCard->tenant_id, $jobCard->company_id);

            if ($resolvedOperators === [] && ($assignment['operator'] !== null || $assignment['external_name'] !== null)) {
                $resolvedOperators = [[
                    'user_id' => $assignment['operator']?->getKey(),
                    'operator_name' => $assignment['external_name'],
                ]];
            }

            if ($resolvedOperators === []) {
                throw new BusinessException('Record at least one operator on the client Job Card.', 422);
            }

            $jobCard->fill(Arr::except($data, ['tenant_id', 'company_id', 'job_id', 'client_id', 'client_site_id', 'operators', 'attachments']));
            $jobCard->operator_id = $assignment['operator']?->getKey();
            $jobCard->operated_by = $assignment['external_name'];
            $jobCard->total_hours = $data['total_hours'] ?? $data['header_hours'] ?? $jobCard->total_hours;
            $jobCard->updated_by = $actor->getKey();
            $jobCard->save();
            $jobCard->unsetRelation('operator');
            $this->operators->syncJobCardOperators($jobCard, $resolvedOperators);

            $this->attachDocuments($jobCard, $attachmentPaths);
            $jobCard->syncBillingFigures();
            $jobCard->saveQuietly();

            $this->logger->log('job_card.updated', sprintf('Client Job Card updated by %s', $actor->full_name), $actor, $jobCard, [
                'tenant_id' => $jobCard->tenant_id,
                'company_id' => $jobCard->company_id,
                'job_id' => $jobCard->job_id,
            ]);

            $currentOperatorName = $jobCard->operatorDisplayName();

            if ($previousOperatorName !== $currentOperatorName) {
                $description = match (true) {
                    $previousOperatorName === null && $currentOperatorName !== null => sprintf('Operator assigned: %s', $currentOperatorName),
                    $previousOperatorName !== null && $currentOperatorName === null => sprintf('Operator cleared from %s', $previousOperatorName),
                    default => sprintf('Operator changed from %s to %s', $previousOperatorName, $currentOperatorName),
                };

                $this->logger->log('job_card.operator_changed', $description, $actor, $jobCard, [
                    'tenant_id' => $jobCard->tenant_id,
                    'company_id' => $jobCard->company_id,
                    'job_id' => $jobCard->job_id,
                    'previous_operator_name' => $previousOperatorName,
                    'current_operator_name' => $currentOperatorName,
                ]);
            }

            return $jobCard->refresh();
        });
    }

    /**
     * @param  list<string>  $attachmentPaths
     */
    private function attachDocuments(JobCard $jobCard, array $attachmentPaths): void
    {
        foreach ($attachmentPaths as $path) {
            if ($path === '') {
                continue;
            }

            $jobCard
                ->addMedia(Storage::disk('local')->path($path))
                ->preservingOriginal()
                ->toMediaCollection('job-card-documents');
        }
    }
}
