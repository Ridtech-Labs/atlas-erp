<?php

declare(strict_types=1);

namespace App\Operations\Actions\JobCards;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Shared\Services\TenantSequenceService;
use App\Models\User;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Support\OperatorAssignmentService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateJobCardAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly TenantSequenceService $sequences,
        private readonly OperatorAssignmentService $operators,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @param  list<string>  $attachmentPaths
     */
    public function execute(Job $job, array $data, User $actor, array $attachmentPaths = []): JobCard
    {
        $operators = $this->operators;
        $logger = $this->logger;

        if (! $actor->hasPermissionTo(PermissionName::JobsCreate->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $job->company_id, $job->tenant_id)) {
            throw new BusinessException('You are not allowed to create job cards for this job.', 403);
        }

        if (! $job->isHeavyMachinery()) {
            throw new BusinessException('Client Job Cards can only be recorded for heavy machinery jobs.', 422);
        }

        if ((string) $job->getRawOriginal('status') !== JobStatus::InProgress->value) {
            throw new BusinessException('Client Job Cards can only be recorded while the job is in progress.', 422);
        }

        return DB::transaction(function () use ($job, $data, $actor, $attachmentPaths, $logger, $operators): JobCard {
            if (! is_int($job->company_id)) {
                throw new BusinessException('The active Job is missing its company context.', 422);
            }

            $assignment = $operators->resolveAssignment(
                $data['operator_id'] ?? null,
                $data['operated_by'] ?? null,
                $job->tenant_id,
                $job->company_id,
                false,
            );
            $resolvedOperators = $operators->resolveOperatorEntries($data['operators'] ?? [], $job->tenant_id, $job->company_id);

            if ($resolvedOperators === [] && ($assignment['operator'] !== null || $assignment['external_name'] !== null)) {
                $resolvedOperators = [[
                    'user_id' => $assignment['operator']?->getKey(),
                    'operator_name' => $assignment['external_name'],
                ]];
            }

            if ($resolvedOperators === []) {
                throw new BusinessException('Record at least one operator on the client Job Card.', 422);
            }

            $jobCard = JobCard::query()->create([
                ...Arr::except($data, ['tenant_id', 'company_id', 'job_id', 'client_id', 'client_site_id', 'operator_id', 'operators', 'attachments', 'source']),
                'card_number' => $data['card_number'] ?? $this->generateCardNumber($job),
                'uuid' => (string) Str::uuid(),
                'tenant_id' => $job->tenant_id,
                'company_id' => $job->company_id,
                'job_id' => $job->getKey(),
                'client_id' => $job->client_id,
                'client_site_id' => $job->client_site_id,
                'card_date' => $this->resolveCardDate($job, $data),
                'operator_id' => $assignment['operator']?->getKey(),
                'shift' => $data['shift'] ?? $job->getRawOriginal('shift'),
                'equipment_reference' => $data['equipment_reference'] ?? $job->equipment_requirement,
                'operated_by' => $assignment['external_name'],
                'approval_status' => $data['approval_status'] ?? JobCardApprovalStatus::Recorded->value,
                'total_hours' => $data['total_hours'] ?? $data['header_hours'] ?? null,
                'billable_amount' => null,
                'created_by' => $actor->getKey(),
                'updated_by' => $actor->getKey(),
            ]);

            $operators->syncJobCardOperators($jobCard, $resolvedOperators);
            $this->attachDocuments($jobCard, $attachmentPaths);
            $jobCard->syncBillingFigures();
            $jobCard->saveQuietly();

            $logger->log('job_card.created', sprintf('Client Job Card recorded by %s', $actor->full_name), $actor, $jobCard, [
                'tenant_id' => $jobCard->tenant_id,
                'company_id' => $jobCard->company_id,
                'job_id' => $jobCard->job_id,
            ]);

            if ($jobCard->operatorDisplayName() !== null) {
                $logger->log('job_card.operator_recorded', sprintf('Operators recorded: %s', $jobCard->operatorDisplayName()), $actor, $jobCard, [
                    'tenant_id' => $jobCard->tenant_id,
                    'company_id' => $jobCard->company_id,
                    'job_id' => $jobCard->job_id,
                    'operator_name' => $jobCard->operatorDisplayName(),
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

    /**
     * @param  array<string, mixed>  $data
     */
    private function resolveCardDate(Job $job, array $data): string
    {
        if (filled($data['card_date'] ?? null)) {
            return CarbonImmutable::parse((string) $data['card_date'])->toDateString();
        }

        $today = CarbonImmutable::today();
        $plannedStart = $job->getRawOriginal('planned_start_date');

        if (is_string($plannedStart) && $plannedStart !== '') {
            $plannedStartDate = CarbonImmutable::parse($plannedStart);

            if ($plannedStartDate->greaterThanOrEqualTo($today)) {
                return $plannedStartDate->toDateString();
            }
        }

        return $today->toDateString();
    }

    private function generateCardNumber(Job $job): string
    {
        return sprintf('JC-%05d', $this->sequences->nextValue($job->tenant_id, 'job_card_number'));
    }
}
