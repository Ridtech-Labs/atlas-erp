<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Core\Administration\Filament\Resources\BillingBatches\BillingBatchResource;
use App\Core\Administration\Filament\Resources\JobCards\JobCardResource;
use App\Core\Administration\Filament\Resources\Jobs\JobResource;
use App\Core\Administration\Filament\Resources\Waybills\WaybillResource;
use App\Core\Shared\Exceptions\BusinessException;
use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Models\BillingBatchLine;
use App\Models\User;
use App\Operations\Actions\Jobs\ApproveJobAction;
use App\Operations\Actions\Jobs\CompleteJobAction;
use App\Operations\Actions\Jobs\ResumeJobAction;
use App\Operations\Actions\Jobs\ScheduleJobAction;
use App\Operations\Actions\Jobs\StartJobAction;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Enums\JobStatus;
use App\Operations\Enums\WaybillStatus;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Models\Waybill;
use App\Operations\Services\JobWorkflowService;
use App\Operations\Support\JobPlanningReadinessService;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;

class JobWorkspaceWidget extends Widget
{
    protected string $view = 'filament.widgets.job-workspace-widget';

    protected int|string|array $columnSpan = 'full';

    public ?Job $record = null;

    protected function getViewData(): array
    {
        $user = auth()->user();
        $job = $this->record?->loadMissing([
            'client',
            'site',
            'assignedOperator',
            'jobCards.operator',
            'jobCards.operators.user',
            'jobCards.workEntries',
            'waybills',
            'completer',
        ]);

        $jobStatus = $job instanceof Job
            ? JobStatus::from((string) $job->getRawOriginal('status'))
            : null;

        $activities = $job instanceof Job
            ? Activity::query()
                ->where('subject_type', $job->getMorphClass())
                ->where('subject_id', $job->getKey())
                ->latest()
                ->limit(12)
                ->get()
            : collect();

        /** @var Collection<int, JobCard> $jobCards */
        $jobCards = $job instanceof Job ? $job->jobCards->sortByDesc('card_date')->values() : collect();
        /** @var Collection<int, Waybill> $waybills */
        $waybills = $job instanceof Job ? $job->waybills->sortByDesc('waybill_date')->values() : collect();
        $isTrucking = $job?->isTrucking() ?? false;
        $workflow = app(JobWorkflowService::class);
        $planningChecklist = [];
        $planningMissingLabels = [];
        $planningReady = false;

        if ($job instanceof Job) {
            $planning = app(JobPlanningReadinessService::class);
            $planningChecklist = $planning->checklist($job);
            $planningMissingLabels = $planning->missingLabels($job);
            $planningReady = $planning->isReadyToSchedule($job);
        }

        $activeCard = $this->resolveActiveCard($jobCards);
        $activeWaybill = $this->resolveActiveWaybill($waybills);
        $primaryNextAction = $this->resolvePrimaryNextAction($job, $jobStatus, $activeCard, $activeWaybill, $user, $planningReady, $planningMissingLabels);
        $latestTransition = $this->latestTransition($activities);
        $completionBlockers = $this->completionBlockers($job, $jobStatus, $jobCards, $waybills);
        $workflowGuide = $this->workflowGuide($job, $jobStatus, $jobCards, $waybills, $planningReady);
        $statusPanel = $this->statusPanel($job, $jobStatus, $latestTransition, $primaryNextAction, $planningReady, $planningMissingLabels, $workflowGuide);
        $workflowTracker = $workflowGuide['tracker'];

        $workEntries = $jobCards->flatMap(fn (JobCard $card): Collection => $card->workEntries);
        $latestCard = $jobCards->first();
        $latestWaybill = $waybills->first();
        $operationalDocumentLabel = $job?->operationalDocumentLabel() ?? 'Operational Document';
        $operationalDocumentLabelPlural = $operationalDocumentLabel === 'Waybill' ? 'Waybills' : 'Client Job Cards';
        $pendingVerificationCards = $jobCards->filter(fn (JobCard $card): bool => in_array((string) $card->getRawOriginal('approval_status'), [JobCardApprovalStatus::PendingVerification->value, JobCardApprovalStatus::Submitted->value], true));
        $returnedCards = $jobCards->filter(fn (JobCard $card): bool => (string) $card->getRawOriginal('approval_status') === JobCardApprovalStatus::Returned->value);
        $recordedCards = $jobCards->filter(fn (JobCard $card): bool => in_array((string) $card->getRawOriginal('approval_status'), [JobCardApprovalStatus::Draft->value, JobCardApprovalStatus::Recorded->value], true));
        $verifiedCards = $jobCards->filter(fn (JobCard $card): bool => (string) $card->getRawOriginal('approval_status') === JobCardApprovalStatus::Verified->value);
        $billingReadyCards = $jobCards->filter(fn (JobCard $card): bool => (string) $card->getRawOriginal('approval_status') === JobCardApprovalStatus::BillingReady->value);
        $pendingVerificationWaybills = $waybills->filter(fn (Waybill $waybill): bool => (string) $waybill->getRawOriginal('status') === WaybillStatus::PendingVerification->value);
        $returnedWaybills = $waybills->filter(fn (Waybill $waybill): bool => (string) $waybill->getRawOriginal('status') === WaybillStatus::Returned->value);
        $recordedWaybills = $waybills->filter(fn (Waybill $waybill): bool => (string) $waybill->getRawOriginal('status') === WaybillStatus::Recorded->value);
        $verifiedWaybills = $waybills->filter(fn (Waybill $waybill): bool => (string) $waybill->getRawOriginal('status') === WaybillStatus::Verified->value);
        $billingReadyWaybills = $waybills->filter(fn (Waybill $waybill): bool => (string) $waybill->getRawOriginal('status') === WaybillStatus::BillingReady->value);
        /** @var Collection<int, BillingBatchLine> $billingLines */
        $billingLines = $job instanceof Job && ! $isTrucking
            ? BillingBatchLine::query()
                ->with(['billingBatch', 'jobCard'])
                ->where('job_id', $job->getKey())
                ->whereHas('billingBatch', fn ($query) => $query
                    ->where('tenant_id', $job->tenant_id)
                    ->where('company_id', $job->company_id))
                ->orderBy('activity_date')
                ->orderBy('id')
                ->get()
            : collect();
        $billingRows = [];

        foreach ($billingLines as $line) {
            $batch = $line->billingBatch;
            $card = $line->jobCard;

            if ($batch === null || $card === null) {
                continue;
            }

            $currency = strtoupper((string) $line->currency);

            $billingRows[] = [
                'batch_number' => $batch->batch_number,
                'date' => CarbonImmutable::parse((string) $line->activity_date)->format('j M Y'),
                'reference' => $card->client_card_reference ?? $card->card_number ?? $line->job_reference,
                'machine_number' => $line->machine_number,
                'from' => filled($line->from_time) ? substr((string) $line->from_time, 0, 5) : null,
                'to' => filled($line->to_time) ? substr((string) $line->to_time, 0, 5) : null,
                'hours' => $line->hours,
                'currency' => $currency,
                'resolved_rate' => $line->resolved_rate,
                'line_amount' => $line->line_amount,
                'batch_status' => BillingBatchStatus::tryFrom((string) $batch->getRawOriginal('status'))?->label() ?? 'Draft',
                'url' => BillingBatchResource::getUrl('view', ['record' => $batch]),
            ];
        }

        return [
            'job' => $job,
            'jobStatus' => $jobStatus,
            'activityItems' => $activities,
            'isTrucking' => $isTrucking,
            'operationalDocumentLabel' => $operationalDocumentLabel,
            'operationalDocumentLabelPlural' => $operationalDocumentLabelPlural,
            'jobCards' => $jobCards,
            'waybills' => $waybills,
            'latestCard' => $latestCard,
            'latestWaybill' => $latestWaybill,
            'activeCard' => $activeCard,
            'activeWaybill' => $activeWaybill,
            'primaryNextAction' => $primaryNextAction,
            'statusPanel' => $statusPanel,
            'workflowTracker' => $workflowTracker,
            'workflowGuide' => $workflowGuide,
            'planningChecklist' => $planningChecklist,
            'planningMissingLabels' => $planningMissingLabels,
            'planningReady' => $planningReady,
            'planningSummary' => $planningReady
                ? 'Planning is complete and this Job is ready to be marked ready for deployment.'
                : 'Planning is incomplete. Complete the missing items before this Job can be marked ready for deployment.',
            'latestTransition' => $latestTransition,
            'completionBlockers' => $completionBlockers,
            'completionEligible' => $job instanceof Job
                && $jobStatus === JobStatus::InProgress
                && $completionBlockers === []
                && $user instanceof User
                && $user->can('complete', $job),
            'jobCardsIndexUrl' => $job instanceof Job ? JobCardResource::getUrl('index', ['job' => $job->getKey()]) : '#',
            'waybillsIndexUrl' => $job instanceof Job ? WaybillResource::getUrl('index', ['job' => $job->getKey()]) : '#',
            'plannedOperatorName' => $job?->plannedOperatorName(),
            'latestCardOperatorName' => $latestCard?->operatorDisplayName(),
            'totalNormalHours' => round((float) $workEntries->sum('normal_hours'), 2),
            'totalOvertimeHours' => round((float) $workEntries->sum('overtime_hours'), 2),
            'totalRecordedHours' => round((float) $jobCards->sum(fn (JobCard $card): float => (float) ($card->displayTotalHours() ?? 0)), 2),
            'jobCardCount' => $jobCards->count(),
            'waybillCount' => $waybills->count(),
            'pendingVerificationCount' => $isTrucking ? $pendingVerificationWaybills->count() : $pendingVerificationCards->count(),
            'returnedCount' => $isTrucking ? $returnedWaybills->count() : $returnedCards->count(),
            'recordedCount' => $isTrucking ? $recordedWaybills->count() : $recordedCards->count(),
            'verifiedCount' => $isTrucking ? $verifiedWaybills->count() : $verifiedCards->count(),
            'billingReadyCount' => $isTrucking ? $billingReadyWaybills->count() : $billingReadyCards->count(),
            'attachmentCount' => $isTrucking
                ? $waybills->sum(fn (Waybill $waybill): int => $waybill->getMedia('waybill-documents')->count())
                : $jobCards->sum(fn (JobCard $card): int => $card->getMedia('job-card-documents')->count()),
            'canCancel' => $job instanceof Job
                && $user instanceof User
                && $workflow->canCancel($job)
                && $user->can('cancel', $job),
            'deleteEligible' => $job instanceof Job
                && $jobStatus === JobStatus::Draft
                && $jobCards->isEmpty()
                && $waybills->isEmpty()
                && $user instanceof User
                && $user->can('delete', $job),
            'canEditPlanning' => $job instanceof Job
                && $user instanceof User
                && $user->can('update', $job),
            'billingSummary' => [
                'job_card_count' => $jobCards->count(),
                'total_hours' => number_format((float) $jobCards->sum(fn (JobCard $card): float => (float) ($card->displayTotalHours() ?? 0)), 2),
                'verified_cards' => $verifiedCards->count(),
                'billing_ready_cards' => $billingReadyCards->count(),
                'snapshot_count' => $billingLines->count(),
            ],
            'billingRows' => $billingRows,
            'showVerificationTab' => true,
            'showBillingTab' => ! $isTrucking,
            'approvalRequired' => $job instanceof Job ? $workflow->approvalRequired($job) : false,
        ];
    }

    public function runWorkflowAction(string $trigger): void
    {
        $job = $this->record;
        $user = auth()->user();

        if (! $job instanceof Job || ! $user instanceof User) {
            abort(403);
        }

        try {
            $updatedJob = match ($trigger) {
                'approve' => app(ApproveJobAction::class)->execute($job, $user),
                'schedule' => app(ScheduleJobAction::class)->execute($job, $user),
                'start' => app(StartJobAction::class)->execute($job, $user),
                'resume' => app(ResumeJobAction::class)->execute($job, $user),
                'complete' => app(CompleteJobAction::class)->execute($job, $user, ['actual_end_date' => now()]),
                default => throw new BusinessException('This workflow action is not available from the workspace.', 422),
            };
        } catch (BusinessException $exception) {
            Notification::make()
                ->danger()
                ->title('Workflow action unavailable')
                ->body($exception->getMessage())
                ->send();

            return;
        }

        $this->redirect(JobResource::getUrl('view', ['record' => $updatedJob]));
    }

    /**
     * @param  Collection<int, JobCard>  $jobCards
     */
    private function resolveActiveCard(Collection $jobCards): ?JobCard
    {
        foreach ([JobCardApprovalStatus::Returned, JobCardApprovalStatus::PendingVerification, JobCardApprovalStatus::Submitted, JobCardApprovalStatus::Recorded, JobCardApprovalStatus::Draft, JobCardApprovalStatus::Verified, JobCardApprovalStatus::BillingReady, JobCardApprovalStatus::Approved] as $status) {
            $card = $jobCards->first(
                fn (JobCard $jobCard): bool => (string) $jobCard->getRawOriginal('approval_status') === $status->value,
            );

            if ($card instanceof JobCard) {
                return $card;
            }
        }

        return null;
    }

    /**
     * @param  Collection<int, Waybill>  $waybills
     */
    private function resolveActiveWaybill(Collection $waybills): ?Waybill
    {
        foreach ([WaybillStatus::Returned, WaybillStatus::PendingVerification, WaybillStatus::Recorded, WaybillStatus::Verified, WaybillStatus::BillingReady] as $status) {
            $waybill = $waybills->first(
                fn (Waybill $candidate): bool => (string) $candidate->getRawOriginal('status') === $status->value,
            );

            if ($waybill instanceof Waybill) {
                return $waybill;
            }
        }

        return null;
    }

    /**
     * @param  Collection<int, Activity>  $activities
     * @return array{label:string,time:?CarbonInterface}|null
     */
    private function latestTransition(Collection $activities): ?array
    {
        $transition = $activities->first(
            fn (Activity $activity): bool => is_string($activity->event) && str_starts_with($activity->event, 'job.'),
        );

        if (! $transition instanceof Activity) {
            return null;
        }

        return [
            'label' => (string) ($transition->description ?: $transition->event),
            'time' => $transition->created_at,
        ];
    }

    /**
     * @param  Collection<int, JobCard>  $jobCards
     * @param  Collection<int, Waybill>  $waybills
     * @return list<string>
     */
    private function completionBlockers(?Job $job, ?JobStatus $status, Collection $jobCards, Collection $waybills): array
    {
        if (! $job instanceof Job || $status !== JobStatus::InProgress) {
            return [];
        }

        if ($job->isTrucking()) {
            $blockers = [];

            if ($waybills->isEmpty()) {
                $blockers[] = 'No Waybills have been recorded yet.';
            }

            $pending = $waybills->filter(fn (Waybill $waybill): bool => (string) $waybill->getRawOriginal('status') === WaybillStatus::PendingVerification->value);
            $returned = $waybills->filter(fn (Waybill $waybill): bool => (string) $waybill->getRawOriginal('status') === WaybillStatus::Returned->value);
            $billingReady = $waybills->filter(fn (Waybill $waybill): bool => in_array((string) $waybill->getRawOriginal('status'), [WaybillStatus::Verified->value, WaybillStatus::BillingReady->value], true));

            if ($pending->isNotEmpty()) {
                $blockers[] = sprintf('%d Waybill%s still await verification.', $pending->count(), $pending->count() === 1 ? '' : 's');
            }

            if ($returned->isNotEmpty()) {
                $blockers[] = sprintf('%d returned Waybill%s require correction.', $returned->count(), $returned->count() === 1 ? '' : 's');
            }

            if ($waybills->isNotEmpty() && $billingReady->isEmpty()) {
                $blockers[] = 'No verified or billing-ready Waybills exist yet.';
            }

            return $blockers;
        }

        $blockers = [];

        if ($jobCards->isEmpty()) {
            $blockers[] = 'No Client Job Cards have been recorded yet.';
        }

        $pending = $jobCards->filter(fn (JobCard $card): bool => in_array((string) $card->getRawOriginal('approval_status'), [JobCardApprovalStatus::PendingVerification->value, JobCardApprovalStatus::Submitted->value], true));
        $returned = $jobCards->filter(fn (JobCard $card): bool => (string) $card->getRawOriginal('approval_status') === JobCardApprovalStatus::Returned->value);
        $billingReady = $jobCards->filter(fn (JobCard $card): bool => (string) $card->getRawOriginal('approval_status') === JobCardApprovalStatus::BillingReady->value);

        if ($pending->isNotEmpty()) {
            $blockers[] = sprintf('%d Client Job Card%s still await verification.', $pending->count(), $pending->count() === 1 ? '' : 's');
        }

        if ($returned->isNotEmpty()) {
            $blockers[] = sprintf('%d returned Client Job Card%s require correction.', $returned->count(), $returned->count() === 1 ? '' : 's');
        }

        if ($jobCards->isNotEmpty() && $billingReady->isEmpty()) {
            $blockers[] = 'No billing-ready Client Job Cards exist yet.';
        }

        return $blockers;
    }

    /**
     * @param  array{current_stage:?array{key:string,label:string,state:string,description:?string},show_help:bool,tracker:list<array{key:string,label:string,state:string,description:?string}>}  $workflowGuide
     * @param  array{label:string,time:?CarbonInterface}|null  $latestTransition
     * @param  array{label:string,helper:string,url:?string,kind:string,trigger:?string}|null  $primaryNextAction
     * @param  list<string>  $planningMissingLabels
     * @return array{title:string,status:string,message:string,next_action_label:?string,last_transition_label:?string,last_transition_time:?CarbonInterface,color:string}
     */
    private function statusPanel(?Job $job, ?JobStatus $status, ?array $latestTransition, ?array $primaryNextAction, bool $planningReady, array $planningMissingLabels, array $workflowGuide): array
    {
        $currentStage = $workflowGuide['current_stage'] ?? null;
        $message = match ($status) {
            JobStatus::Draft => $planningReady
                ? 'Planning is complete and the machine is ready to be sent to the client-allocated work location.'
                : 'Planning is still being prepared. You can continue editing this Job.',
            JobStatus::PendingApproval => 'This Job is in a legacy approval step and remains readable without changing its identity.',
            JobStatus::Approved => 'Planning is complete and the machine is ready to be sent to the client-allocated work location.',
            JobStatus::Scheduled => 'Planning is complete and the machine is ready to be sent to the client-allocated work location.',
            JobStatus::InProgress => 'The machine has been deployed and operational work is underway.',
            JobStatus::OnHold => 'Work is paused until the Job is resumed.',
            JobStatus::Completed => 'Operational work is complete and the billing basis remains available for finance follow-up.',
            JobStatus::Cancelled => 'This Job has been cancelled and remains part of the audit trail.',
            null => 'Status is not available.',
        };

        if ($status === JobStatus::Draft && $planningMissingLabels !== []) {
            $message = 'Planning incomplete: '.implode(', ', $planningMissingLabels).'.';
        }

        if ($job instanceof Job && ! $job->isTrucking() && is_array($currentStage) && filled($currentStage['description'] ?? null)) {
            $message = (string) $currentStage['description'];

            if ($status === JobStatus::OnHold) {
                $message .= ' Work is currently paused until the Job is resumed.';
            }
        }

        $useBusinessStageLabel = $job instanceof Job
            && ! $job->isTrucking()
            && is_array($currentStage)
            && ! in_array($status, [JobStatus::PendingApproval, JobStatus::OnHold, JobStatus::Cancelled], true);

        return [
            'title' => 'Job Status',
            'status' => $useBusinessStageLabel
                ? (string) $currentStage['label']
                : ($status?->label() ?? 'Unknown'),
            'message' => $message,
            'next_action_label' => $primaryNextAction['label'] ?? null,
            'last_transition_label' => $latestTransition['label'] ?? null,
            'last_transition_time' => $latestTransition['time'] ?? null,
            'color' => $status?->color() ?? 'gray',
        ];
    }

    /**
     * @param  list<string>  $planningMissingLabels
     * @return array{label:string,button_label:?string,helper:string,url:?string,kind:string,trigger:?string}|null
     */
    private function resolvePrimaryNextAction(?Job $job, ?JobStatus $status, ?JobCard $activeCard, ?Waybill $activeWaybill, mixed $user, bool $planningReady, array $planningMissingLabels): ?array
    {
        if (! $job instanceof Job || ! $user instanceof User) {
            return null;
        }

        $canApprove = $user->hasPermissionTo('jobs.approve');
        $canBillJobCard = $user->hasPermissionTo('job_cards.bill');
        $canUpdate = $user->can('update', $job);
        $canSchedule = $user->can('schedule', $job);
        $canStart = $user->can('start', $job);
        $canResume = $user->can('resume', $job);
        $canComplete = $user->can('complete', $job);
        $completionBlockers = $this->completionBlockers($job, $status, $job->jobCards, $job->waybills);

        return match ($status) {
            JobStatus::Completed => [
                'label' => 'View Final Summary',
                'button_label' => null,
                'helper' => 'Review the completed operational history and billing basis for this Job.',
                'url' => null,
                'kind' => 'summary',
                'trigger' => null,
            ],
            JobStatus::Cancelled => null,
            JobStatus::Draft => $planningReady
                ? ($canSchedule
                    ? [
                        'label' => 'Ready the Job for deployment',
                        'button_label' => 'Mark Ready for Deployment',
                        'helper' => 'Planning is complete. Mark this Job ready for deployment so operations can start when the client allocation is confirmed.',
                        'url' => null,
                        'kind' => 'primary',
                        'trigger' => 'schedule',
                    ]
                    : [
                        'label' => 'Awaiting Operations',
                        'button_label' => null,
                        'helper' => 'Planning is complete. An Operations Manager can now mark this Job Ready for Deployment.',
                        'url' => null,
                        'kind' => 'attention',
                        'trigger' => null,
                    ])
                : ($canUpdate
                    ? [
                        'label' => 'Complete Job Planning',
                        'button_label' => 'Edit Job',
                        'helper' => 'Review or update the Job information before handing it over to Operations.',
                        'url' => JobResource::getUrl('edit', ['record' => $job]),
                        'kind' => 'planning',
                        'trigger' => null,
                    ]
                    : [
                        'label' => 'Planning in progress',
                        'button_label' => null,
                        'helper' => $planningMissingLabels === []
                            ? 'This Job still needs planning attention before Operations can take over.'
                            : 'Planning still needs attention: '.implode(', ', $planningMissingLabels).'.',
                        'url' => null,
                        'kind' => 'attention',
                        'trigger' => null,
                    ]),
            JobStatus::PendingApproval => $canApprove
                ? [
                    'label' => 'Approve Job',
                    'button_label' => 'Approve Job',
                    'helper' => 'This legacy approval-state Job can be approved and moved back into the active workflow.',
                    'url' => null,
                    'kind' => 'attention',
                    'trigger' => 'approve',
                ]
                : [
                    'label' => 'Awaiting Job Approval',
                    'button_label' => null,
                    'helper' => 'This Job is waiting for an authorized approver.',
                    'url' => null,
                    'kind' => 'attention',
                    'trigger' => null,
                ],
            JobStatus::Approved => $canSchedule
                ? [
                    'label' => 'Ready the Job for deployment',
                    'button_label' => 'Mark Ready for Deployment',
                    'helper' => 'Mark this approved Job ready for deployment so it can move into live operations when the client allocation is confirmed.',
                    'url' => null,
                    'kind' => 'primary',
                    'trigger' => 'schedule',
                ]
                : [
                    'label' => 'Ready the Job for deployment',
                    'button_label' => null,
                    'helper' => 'This Job is approved and waiting for an authorized user to mark it ready for deployment.',
                    'url' => null,
                    'kind' => 'attention',
                    'trigger' => null,
                ],
            JobStatus::Scheduled => $canStart
                ? [
                    'label' => 'Start Job',
                    'button_label' => 'Start Job',
                    'helper' => 'Starting the Job moves it into live operations so the client-issued operational document can be recorded after the work is done.',
                    'url' => null,
                    'kind' => 'primary',
                    'trigger' => 'start',
                ]
                : [
                    'label' => 'Awaiting Job Start',
                    'button_label' => null,
                    'helper' => 'This Job is ready for deployment and is waiting for an authorized user to start it.',
                    'url' => null,
                    'kind' => 'attention',
                    'trigger' => null,
                ],
            JobStatus::OnHold => $canResume
                ? [
                    'label' => 'Resume Job',
                    'button_label' => 'Resume Job',
                    'helper' => 'Resume this paused Job before recording more operational work.',
                    'url' => null,
                    'kind' => 'primary',
                    'trigger' => 'resume',
                ]
                : [
                    'label' => 'Awaiting Job Resume',
                    'button_label' => null,
                    'helper' => 'This Job is paused and waiting for an authorized user to resume work.',
                    'url' => null,
                    'kind' => 'attention',
                    'trigger' => null,
                ],
            JobStatus::InProgress => $this->resolveInProgressAction($job, $activeCard, $activeWaybill, $canApprove, $completionBlockers),
            default => null,
        };
    }

    /**
     * @param  list<string>  $completionBlockers
     * @return array{label:string,button_label:?string,helper:string,url:?string,kind:string,trigger:?string}
     */
    private function resolveInProgressAction(Job $job, ?JobCard $activeCard, ?Waybill $activeWaybill, bool $canApprove, array $completionBlockers): array
    {
        $canComplete = auth()->user() instanceof User && auth()->user()->can('complete', $job);

        if ($job->isTrucking()) {
            if ($activeWaybill instanceof Waybill) {
                return match ((string) $activeWaybill->getRawOriginal('status')) {
                    WaybillStatus::Returned->value => [
                        'label' => 'Correct Returned Waybill',
                        'button_label' => 'Correct Returned Waybill',
                        'helper' => 'A returned Waybill needs correction before it can support billing.',
                        'url' => WaybillResource::getUrl('edit', ['record' => $activeWaybill]),
                        'kind' => 'warning',
                        'trigger' => null,
                    ],
                    WaybillStatus::PendingVerification->value => [
                        'label' => $canApprove ? 'Review Pending Waybill' : 'Awaiting Waybill Verification',
                        'button_label' => $canApprove ? 'Review Pending Waybill' : null,
                        'helper' => $canApprove
                            ? 'A Waybill is waiting for verification attention.'
                            : 'A Waybill is waiting for an authorized verifier.',
                        'url' => WaybillResource::getUrl($canApprove ? 'edit' : 'view', ['record' => $activeWaybill]),
                        'kind' => 'attention',
                        'trigger' => null,
                    ],
                    WaybillStatus::Recorded->value => [
                        'label' => 'Continue Waybill',
                        'button_label' => 'Continue Waybill',
                        'helper' => 'Continue recording trips and commercial details on this Waybill.',
                        'url' => WaybillResource::getUrl('edit', ['record' => $activeWaybill]),
                        'kind' => 'primary',
                        'trigger' => null,
                    ],
                    WaybillStatus::Verified->value => [
                        'label' => 'Prepare Waybill Billing',
                        'button_label' => 'Prepare Waybill Billing',
                        'helper' => 'This verified Waybill can now be finalized as billing ready.',
                        'url' => WaybillResource::getUrl('edit', ['record' => $activeWaybill]),
                        'kind' => 'success',
                        'trigger' => null,
                    ],
                    WaybillStatus::BillingReady->value => [
                        'label' => 'View Billing Basis',
                        'button_label' => 'View Billing Basis',
                        'helper' => 'This Waybill is billing ready and remains part of the Job billing basis.',
                        'url' => WaybillResource::getUrl('view', ['record' => $activeWaybill]),
                        'kind' => 'summary',
                        'trigger' => null,
                    ],
                    default => [
                        'label' => 'Record Waybill',
                        'button_label' => 'Record Waybill',
                        'helper' => 'This trucking Job is active and ready for its next Waybill.',
                        'url' => WaybillResource::getUrl('create', ['job' => $job]),
                        'kind' => 'primary',
                        'trigger' => null,
                    ],
                };
            }

            if ($completionBlockers === []) {
                return [
                    'label' => $canComplete ? 'Complete Job' : 'Awaiting Job Completion',
                    'button_label' => $canComplete ? 'Complete Job' : null,
                    'helper' => $canComplete
                        ? 'All recorded Waybills are ready for billing and this Job can now be completed.'
                        : 'All recorded Waybills are ready for billing and this Job is waiting for an authorized user to complete it.',
                    'url' => null,
                    'kind' => $canComplete ? 'success' : 'attention',
                    'trigger' => $canComplete ? 'complete' : null,
                ];
            }

            return [
                'label' => 'Record Waybill',
                'button_label' => 'Record Waybill',
                'helper' => 'This trucking Job is active and ready for its next Waybill.',
                'url' => WaybillResource::getUrl('create', ['job' => $job]),
                'kind' => 'primary',
                'trigger' => null,
            ];
        }

        if ($activeCard instanceof JobCard) {
            $canApproveCard = auth()->user() instanceof User
                && auth()->user()->can('approve', $activeCard);
            $canBillJobCard = auth()->user() instanceof User
                && auth()->user()->hasPermissionTo('job_cards.bill');

            return match ((string) $activeCard->getRawOriginal('approval_status')) {
                JobCardApprovalStatus::Returned->value => [
                    'label' => 'Correct Returned Card',
                    'button_label' => 'Correct Returned Card',
                    'helper' => $activeCard->return_reason ?: 'A returned Client Job Card needs correction before it can be resubmitted.',
                    'url' => JobCardResource::getUrl('edit', ['record' => $activeCard]),
                    'kind' => 'warning',
                    'trigger' => null,
                ],
                JobCardApprovalStatus::PendingVerification->value, JobCardApprovalStatus::Submitted->value => [
                    'label' => $canApproveCard ? 'Review for Billing' : 'Awaiting Accounts Review',
                    'button_label' => $canApproveCard ? 'Review for Billing' : null,
                    'helper' => $canApproveCard
                        ? 'A client-issued Job Card is waiting for Accounts review before billing can continue.'
                        : 'A client-issued Job Card is waiting for Finance or an authorized administrator to review it for billing.',
                    'url' => JobCardResource::getUrl('view', ['record' => $activeCard]),
                    'kind' => 'attention',
                    'trigger' => null,
                ],
                JobCardApprovalStatus::Recorded->value, JobCardApprovalStatus::Draft->value => [
                    'label' => 'Continue Client Job Card',
                    'button_label' => 'Continue Client Job Card',
                    'helper' => 'Continue recording the client-issued Job Card from completed work.',
                    'url' => JobCardResource::getUrl('edit', ['record' => $activeCard]),
                    'kind' => 'primary',
                    'trigger' => null,
                ],
                JobCardApprovalStatus::Verified->value => [
                    'label' => $canBillJobCard ? 'Prepare Billing Batch' : 'Awaiting Billing Batch Preparation',
                    'button_label' => $canBillJobCard ? 'Prepare Billing Batch' : null,
                    'helper' => $canBillJobCard
                        ? 'This Accounts-reviewed Client Job Card is ready to be added to a Billing Batch, where Atlas will resolve the rate agreement and snapshot the commercial basis.'
                        : 'This Accounts-reviewed Client Job Card is waiting for Finance or an authorized administrator to prepare a Billing Batch.',
                    'url' => $canBillJobCard ? BillingBatchResource::getUrl('index') : JobCardResource::getUrl('view', ['record' => $activeCard]),
                    'kind' => 'success',
                    'trigger' => null,
                ],
                JobCardApprovalStatus::BillingReady->value => $completionBlockers === []
                    ? [
                        'label' => $canComplete ? 'Complete Job' : 'Awaiting Job Completion',
                        'button_label' => $canComplete ? 'Complete Job' : null,
                        'helper' => $canComplete
                            ? 'All recorded Client Job Cards are billing ready and this Job can now be completed.'
                            : 'All recorded Client Job Cards are billing ready and this Job is waiting for an authorized user to complete it.',
                        'url' => null,
                        'kind' => $canComplete ? 'success' : 'attention',
                        'trigger' => $canComplete ? 'complete' : null,
                    ]
                    : [
                        'label' => 'View Billing Basis',
                        'button_label' => 'View Billing Basis',
                        'helper' => 'Review the billing-ready Job Card alongside any remaining outstanding cards.',
                        'url' => JobCardResource::getUrl('view', ['record' => $activeCard]),
                        'kind' => 'summary',
                        'trigger' => null,
                    ],
                default => [
                    'label' => 'Record Client Job Card',
                    'button_label' => 'Record Client Job Card',
                    'helper' => 'This heavy-machinery Job is active and ready for a recorded client-issued Job Card.',
                    'url' => JobCardResource::getUrl('create', ['job' => $job]),
                    'kind' => 'primary',
                    'trigger' => null,
                ],
            };
        }

        return [
            'label' => 'Record Client Job Card',
            'button_label' => 'Record Client Job Card',
            'helper' => 'This heavy-machinery Job is active and ready for a recorded client-issued Job Card.',
            'url' => JobCardResource::getUrl('create', ['job' => $job]),
            'kind' => 'primary',
            'trigger' => null,
        ];
    }

    /**
     * @param  Collection<int, JobCard>  $jobCards
     * @param  Collection<int, Waybill>  $waybills
     * @return array{current_stage:?array{key:string,label:string,state:string,description:?string},show_help:bool,tracker:list<array{key:string,label:string,state:string,description:?string}>}
     */
    private function workflowGuide(?Job $job, ?JobStatus $status, Collection $jobCards, Collection $waybills, bool $planningReady): array
    {
        if (! $job instanceof Job) {
            return [
                'current_stage' => null,
                'show_help' => false,
                'tracker' => [],
            ];
        }

        if (! $job->isTrucking()) {
            $stages = $this->heavyMachineryStageDefinitions();
            $currentStageKey = $this->resolveHeavyMachineryStageKey($job, $status, $jobCards, $waybills);
            $currentIndex = array_search($currentStageKey, array_column($stages, 'key'), true);
            $currentIndex = is_int($currentIndex) ? $currentIndex : 0;

            $tracker = [];

            foreach ($stages as $stageIndex => $stage) {
                $tracker[] = [
                    'key' => $stage['key'],
                    'label' => $stage['label'],
                    'description' => $stage['description'],
                    'state' => $stageIndex < $currentIndex ? 'complete' : ($stageIndex === $currentIndex ? 'current' : 'future'),
                ];
            }

            return [
                'current_stage' => $tracker[$currentIndex] ?? null,
                'show_help' => true,
                'tracker' => $tracker,
            ];
        }

        $steps = ['Planning', 'Deployment', 'Execution', 'Waybill', 'Verification', 'Billing'];
        $index = match (true) {
            $status === JobStatus::Completed => 5,
            $status === JobStatus::Cancelled => 0,
            $status === JobStatus::InProgress && $job->readyForCompletion() => 5,
            $status === JobStatus::InProgress && $this->hasVerifiedOperationalRecord($jobCards, $waybills, true) => 4,
            $status === JobStatus::InProgress && $this->hasRecordedOperationalRecord($jobCards, $waybills, true) => 3,
            $status === JobStatus::InProgress => 2,
            $status === JobStatus::Scheduled => 1,
            $status === JobStatus::Approved => 1,
            $status === JobStatus::Draft && $planningReady => 0,
            default => 0,
        };

        $tracker = [];

        foreach ($steps as $stepIndex => $label) {
            $tracker[] = [
                'key' => str($label)->slug('_')->value(),
                'label' => $label,
                'state' => $stepIndex < $index ? 'complete' : ($stepIndex === $index ? 'current' : 'future'),
                'description' => null,
            ];
        }

        return [
            'current_stage' => $tracker[$index],
            'show_help' => false,
            'tracker' => $tracker,
        ];
    }

    /**
     * @param  Collection<int, JobCard>  $jobCards
     * @param  Collection<int, Waybill>  $waybills
     */
    private function hasRecordedOperationalRecord(Collection $jobCards, Collection $waybills, bool $isTrucking): bool
    {
        if ($isTrucking) {
            return $waybills->isNotEmpty();
        }

        return $jobCards->isNotEmpty();
    }

    /**
     * @param  Collection<int, JobCard>  $jobCards
     * @param  Collection<int, Waybill>  $waybills
     */
    private function hasVerifiedOperationalRecord(Collection $jobCards, Collection $waybills, bool $isTrucking): bool
    {
        if ($isTrucking) {
            return $waybills->contains(
                fn (Waybill $waybill): bool => in_array((string) $waybill->getRawOriginal('status'), [WaybillStatus::Verified->value, WaybillStatus::BillingReady->value], true),
            );
        }

        return $jobCards->contains(
            fn (JobCard $card): bool => in_array((string) $card->getRawOriginal('approval_status'), [JobCardApprovalStatus::Verified->value, JobCardApprovalStatus::BillingReady->value], true),
        );
    }

    /**
     * @return list<array{key:string,label:string,description:string}>
     */
    private function heavyMachineryStageDefinitions(): array
    {
        return [
            [
                'key' => 'draft',
                'label' => 'Draft',
                'description' => 'Planning is still being prepared. You can continue editing this Job.',
            ],
            [
                'key' => 'ready_for_deployment',
                'label' => 'Ready for Deployment',
                'description' => 'Planning is complete and the machine is ready to be sent to the client-allocated work location.',
            ],
            [
                'key' => 'in_progress',
                'label' => 'In Progress',
                'description' => 'The machine has been deployed and work is underway.',
            ],
            [
                'key' => 'job_card_recorded',
                'label' => 'Job Card Recorded',
                'description' => 'The client-issued Job Card has been received and recorded.',
            ],
            [
                'key' => 'verified',
                'label' => 'Accounts Reviewed',
                'description' => 'Finance has reviewed the client-endorsed Job Card and confirmed it can move into billing preparation.',
            ],
            [
                'key' => 'billing_ready',
                'label' => 'Billing Ready',
                'description' => 'Hours and rates have been compiled and this work is ready for invoicing.',
            ],
            [
                'key' => 'completed',
                'label' => 'Completed',
                'description' => 'Operational processing for this Job is finished.',
            ],
        ];
    }

    /**
     * @param  Collection<int, JobCard>  $jobCards
     * @param  Collection<int, Waybill>  $waybills
     */
    private function resolveHeavyMachineryStageKey(Job $job, ?JobStatus $status, Collection $jobCards, Collection $waybills): string
    {
        if ($status === JobStatus::Completed) {
            return 'completed';
        }

        if ($status === JobStatus::InProgress && $this->hasBillingReadyOperationalRecord($jobCards, $waybills, false)) {
            return 'billing_ready';
        }

        if ($status === JobStatus::InProgress && $this->hasVerifiedOperationalRecord($jobCards, $waybills, false)) {
            return 'verified';
        }

        if ($status === JobStatus::InProgress && $this->hasRecordedOperationalRecord($jobCards, $waybills, false)) {
            return 'job_card_recorded';
        }

        if (in_array($status, [JobStatus::InProgress, JobStatus::OnHold], true)) {
            return 'in_progress';
        }

        if (in_array($status, [JobStatus::Scheduled, JobStatus::Approved], true)) {
            return 'ready_for_deployment';
        }

        return 'draft';
    }

    /**
     * @param  Collection<int, JobCard>  $jobCards
     * @param  Collection<int, Waybill>  $waybills
     */
    private function hasBillingReadyOperationalRecord(Collection $jobCards, Collection $waybills, bool $isTrucking): bool
    {
        if ($isTrucking) {
            return $waybills->contains(
                fn (Waybill $waybill): bool => (string) $waybill->getRawOriginal('status') === WaybillStatus::BillingReady->value,
            );
        }

        return $jobCards->contains(
            fn (JobCard $card): bool => (string) $card->getRawOriginal('approval_status') === JobCardApprovalStatus::BillingReady->value,
        );
    }
}
