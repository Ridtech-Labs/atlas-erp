<?php

declare(strict_types=1);

namespace App\Operations\Actions\JobCards;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use App\Operations\Models\JobCard;
use App\Operations\Support\JobCardAggregateService;
use App\Operations\Support\OperatorAssignmentService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateJobCardAction
{
    /**
     * @var list<string>
     */
    private const WORKFLOW_ONLY_FIELDS = [
        'approval_status',
        'approved_by',
        'approved_at',
        'submitted_by',
        'submitted_at',
        'returned_by',
        'returned_at',
        'return_reason',
        'verification_notes',
        'verified_by',
        'verified_at',
        'billing_ready_at',
        'billing_ready_by',
        'billable_amount',
    ];

    /**
     * @var list<string>
     */
    private const BILLING_FIELDS = [
        'rate_currency',
        'hourly_rate',
        'exchange_rate',
        'converted_hourly_rate',
        'rate_notes',
    ];

    /**
     * @var list<string>
     */
    private const AUTHORITATIVE_WORK_ENTRY_FIELDS = [
        'from_time',
        'to_time',
        'header_hours',
        'total_hours',
    ];

    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly JobCardAggregateService $aggregates,
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

        if ($jobCard->evidenceIsLockedForEditing()) {
            throw new BusinessException('Submitted and Accounts-reviewed client Job Card evidence is read-only until it is returned to Operations.', 422);
        }

        return DB::transaction(function () use ($jobCard, $data, $actor, $attachmentPaths): JobCard {
            $sanitized = Arr::except($data, self::WORKFLOW_ONLY_FIELDS);
            $sanitized = Arr::except($sanitized, self::BILLING_FIELDS);

            $previousOperatorName = $jobCard->operatorDisplayName();
            $assignment = $this->operators->resolveAssignment(
                $sanitized['operator_id'] ?? $jobCard->operator_id,
                array_key_exists('operated_by', $sanitized) ? $sanitized['operated_by'] : $jobCard->operated_by,
                $jobCard->tenant_id,
                $jobCard->company_id,
                false,
            );
            $resolvedOperators = $this->operators->resolveOperatorEntries($sanitized['operators'] ?? [], $jobCard->tenant_id, $jobCard->company_id);

            if ($resolvedOperators === [] && ($assignment['operator'] !== null || $assignment['external_name'] !== null)) {
                $resolvedOperators = [[
                    'user_id' => $assignment['operator']?->getKey(),
                    'operator_name' => $assignment['external_name'],
                ]];
            }

            if ($resolvedOperators === []) {
                throw new BusinessException('Record at least one operator on the client Job Card.', 422);
            }

            $jobCard->fill(Arr::except($sanitized, ['tenant_id', 'company_id', 'job_id', 'client_id', 'client_site_id', 'operators', 'attachments', ...self::AUTHORITATIVE_WORK_ENTRY_FIELDS]));
            $jobCard->operator_id = $assignment['operator']?->getKey();
            $jobCard->operated_by = $assignment['external_name'];
            $jobCard->updated_by = $actor->getKey();
            $jobCard->save();
            $jobCard->unsetRelation('operator');
            $this->operators->syncJobCardOperators($jobCard, $resolvedOperators);

            $this->attachDocuments($jobCard, $attachmentPaths);
            $this->aggregates->syncTotals($jobCard);

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
