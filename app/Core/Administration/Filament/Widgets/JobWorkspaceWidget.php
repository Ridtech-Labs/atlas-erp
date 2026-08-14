<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Core\Administration\Filament\Resources\JobCards\JobCardResource;
use App\Core\Administration\Filament\Resources\Jobs\JobResource;
use App\Models\User;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use Carbon\CarbonInterface;
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
            'jobCards.approver',
            'jobCards.returnedBy',
            'jobCards.workEntries',
            'completer',
        ]);

        $jobStatus = $job instanceof Job
            ? JobStatus::from((string) $job->getRawOriginal('status'))
            : null;

        $activities = $job instanceof Job
            ? Activity::query()
                ->where(function ($query) use ($job): void {
                    $query->where('subject_type', $job->getMorphClass())
                        ->where('subject_id', $job->getKey());
                })
                ->latest()
                ->limit(12)
                ->get()
            : collect();

        $jobCards = $job instanceof Job ? $job->jobCards->sortByDesc('card_date')->values() : collect();
        $workEntries = $jobCards->flatMap(fn (JobCard $card): Collection => $card->workEntries);
        $statusCounts = $jobCards
            ->map(fn (JobCard $card): string => (string) $card->getRawOriginal('approval_status'))
            ->countBy();
        $totalNormalHours = round((float) $workEntries->sum('normal_hours'), 2);
        $totalOvertimeHours = round((float) $workEntries->sum('overtime_hours'), 2);
        $totalRecordedHours = round((float) $workEntries->sum('total_hours'), 2);
        $latestCard = $jobCards->first();
        $activeCard = $this->resolveActiveCard($jobCards);
        $plannedOperatorName = $job?->plannedOperatorName();
        $latestCardOperatorName = $latestCard?->operatorDisplayName();
        $submittedCards = $jobCards->filter(fn (JobCard $card): bool => (string) $card->getRawOriginal('approval_status') === JobCardApprovalStatus::Submitted->value);
        $returnedCards = $jobCards->filter(fn (JobCard $card): bool => (string) $card->getRawOriginal('approval_status') === JobCardApprovalStatus::Returned->value);
        $draftCards = $jobCards->filter(fn (JobCard $card): bool => (string) $card->getRawOriginal('approval_status') === JobCardApprovalStatus::Draft->value);
        $latestTransition = $this->latestTransition($activities);
        $completionBlockers = $this->completionBlockers($jobStatus, $jobCards, $submittedCards, $returnedCards);
        $primaryNextAction = $this->resolvePrimaryNextAction($job, $jobStatus, $activeCard, $submittedCards, $returnedCards, $draftCards, $user);
        $activeCardAction = $this->resolveCardAction($activeCard, $user);
        $completionEligible = $jobStatus === JobStatus::InProgress
            && $completionBlockers === []
            && $user instanceof User
            && $user->can('complete', $job);

        return [
            'job' => $job,
            'jobStatus' => $jobStatus,
            'jobCards' => $jobCards,
            'latestApprovedCard' => $jobCards->first(fn (JobCard $card) => $card->approved_at !== null),
            'latestCard' => $latestCard,
            'activeCard' => $activeCard,
            'activeCardAction' => $activeCardAction,
            'activityItems' => $activities,
            'attachmentCount' => $jobCards->sum(fn (JobCard $card): int => $card->getMedia('job-card-documents')->count()),
            'jobCardCount' => $jobCards->count(),
            'approvedJobCardCount' => (int) ($statusCounts['approved'] ?? 0),
            'draftJobCardCount' => (int) ($statusCounts['draft'] ?? 0),
            'submittedJobCardCount' => (int) ($statusCounts['submitted'] ?? 0),
            'returnedJobCardCount' => (int) ($statusCounts['returned'] ?? 0),
            'totalWorkEntries' => $workEntries->count(),
            'totalNormalHours' => $totalNormalHours,
            'totalOvertimeHours' => $totalOvertimeHours,
            'totalRecordedHours' => $totalRecordedHours,
            'plannedOperatorName' => $plannedOperatorName,
            'latestCardOperatorName' => $latestCardOperatorName,
            'crewCount' => $plannedOperatorName !== null ? 1 : 0,
            'equipmentCount' => filled($job?->equipment_requirement) ? 1 : 0,
            'submittedAttentionCount' => $submittedCards->count(),
            'returnedAttentionCount' => $returnedCards->count(),
            'latestTransition' => $latestTransition,
            'statusPanel' => $this->statusPanel($jobStatus, $submittedCards->count(), $returnedCards->count(), $latestTransition, $primaryNextAction),
            'primaryNextAction' => $primaryNextAction,
            'completionBlockers' => $completionBlockers,
            'completionEligible' => $completionEligible,
            'isPlanningHeavy' => in_array($jobStatus, [JobStatus::Draft, JobStatus::Scheduled], true),
        ];
    }

    /**
     * @param  Collection<int, JobCard>  $jobCards
     */
    private function resolveActiveCard(Collection $jobCards): ?JobCard
    {
        foreach ([JobCardApprovalStatus::Returned, JobCardApprovalStatus::Submitted, JobCardApprovalStatus::Draft, JobCardApprovalStatus::Approved] as $status) {
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
     * @param  Collection<int, JobCard>  $submittedCards
     * @param  Collection<int, JobCard>  $returnedCards
     * @return list<string>
     */
    private function completionBlockers(?JobStatus $status, Collection $jobCards, Collection $submittedCards, Collection $returnedCards): array
    {
        if ($status !== JobStatus::InProgress) {
            return [];
        }

        $blockers = [];

        if ($jobCards->isEmpty()) {
            $blockers[] = 'No Job Cards have been created yet.';
        }

        if ($submittedCards->isNotEmpty()) {
            $blockers[] = sprintf('%d Job Card%s still await approval.', $submittedCards->count(), $submittedCards->count() === 1 ? '' : 's');
        }

        if ($returnedCards->isNotEmpty()) {
            $blockers[] = sprintf('%d returned Job Card%s require correction.', $returnedCards->count(), $returnedCards->count() === 1 ? '' : 's');
        }

        if ($jobCards->isNotEmpty() && $jobCards->every(
            fn (JobCard $card): bool => (string) $card->getRawOriginal('approval_status') !== JobCardApprovalStatus::Approved->value,
        )) {
            $blockers[] = 'No approved Job Cards exist yet.';
        }

        return $blockers;
    }

    /**
     * @param  array{label:string,helper:string,url:?string,kind:string,trigger:?string}|null  $primaryNextAction
     * @param  array{label:string,time:?CarbonInterface}|null  $latestTransition
     * @return array{title:string,status:string,message:string,next_action_label:?string,last_transition_label:?string,last_transition_time:?CarbonInterface,color:string}
     */
    private function statusPanel(?JobStatus $status, int $submittedCards, int $returnedCards, ?array $latestTransition, ?array $primaryNextAction): array
    {
        $message = match ($status) {
            JobStatus::Draft => 'Planning is still in preparation.',
            JobStatus::PendingApproval => 'This Job is waiting for internal approval.',
            JobStatus::Approved => 'The Job is approved and can now be scheduled.',
            JobStatus::Scheduled => 'Ready for operational work.',
            JobStatus::InProgress => 'Operational work is currently underway.',
            JobStatus::OnHold => 'Work is temporarily paused.',
            JobStatus::Completed => 'Operational work is complete.',
            JobStatus::Cancelled => 'This Job has been cancelled.',
            null => 'Status is not available.',
        };

        if ($submittedCards > 0) {
            $message = sprintf('%d Job Card%s %s awaiting review.', $submittedCards, $submittedCards === 1 ? ' is' : 's are', $submittedCards === 1 ? 'is' : 'are');
        } elseif ($returnedCards > 0) {
            $message = sprintf('%d Job Card%s %s returned for correction.', $returnedCards, $returnedCards === 1 ? ' has' : 's have', $returnedCards === 1 ? 'has' : 'have');
        }

        return [
            'title' => 'Job Status',
            'status' => $status?->label() ?? 'Unknown',
            'message' => $message,
            'next_action_label' => $primaryNextAction['label'] ?? null,
            'last_transition_label' => $latestTransition['label'] ?? null,
            'last_transition_time' => $latestTransition['time'] ?? null,
            'color' => $status?->color() ?? 'gray',
        ];
    }

    /**
     * @param  Collection<int, JobCard>  $submittedCards
     * @param  Collection<int, JobCard>  $returnedCards
     * @param  Collection<int, JobCard>  $draftCards
     * @return array{label:string,helper:string,url:?string,kind:string,trigger:?string}|null
     */
    private function resolvePrimaryNextAction(?Job $job, ?JobStatus $status, ?JobCard $activeCard, Collection $submittedCards, Collection $returnedCards, Collection $draftCards, mixed $user): ?array
    {
        if (! $job instanceof Job || ! $user instanceof User) {
            return null;
        }

        $canApproveCards = $user->hasPermissionTo('jobs.approve');
        $canUpdateCards = $user->hasPermissionTo('jobs.update');
        $canCompleteJob = $user->can('complete', $job);
        $completionBlockers = $this->completionBlockers($status, $job->jobCards, $submittedCards, $returnedCards);

        if ($status === JobStatus::Completed) {
            return [
                'label' => 'View Completion Summary',
                'helper' => 'Operational work is complete and all recorded history remains available below.',
                'url' => null,
                'kind' => 'summary',
                'trigger' => null,
            ];
        }

        if ($status === JobStatus::Cancelled) {
            return null;
        }

        if ($returnedCards->isNotEmpty() && $canUpdateCards) {
            $card = $returnedCards->first();

            return [
                'label' => 'Correct Returned Card',
                'helper' => 'A returned Job Card needs correction and resubmission before work can move forward.',
                'url' => JobCardResource::getUrl('edit', ['record' => $card]),
                'kind' => 'warning',
                'trigger' => null,
            ];
        }

        if ($submittedCards->isNotEmpty()) {
            $card = $submittedCards->first();

            return [
                'label' => $canApproveCards ? 'Review Submitted Card' : 'Awaiting Approval',
                'helper' => $canApproveCards
                    ? 'A submitted Job Card is waiting for approval attention.'
                    : 'A submitted Job Card is waiting for an approver.',
                'url' => JobCardResource::getUrl($canApproveCards ? 'edit' : 'view', ['record' => $card]),
                'kind' => 'attention',
                'trigger' => null,
            ];
        }

        if ($status === JobStatus::Draft) {
            return [
                'label' => 'Edit Planning',
                'helper' => 'Complete the planning details before routing this Job for approval and scheduling.',
                'url' => JobResource::getUrl('edit', ['record' => $job]),
                'kind' => 'planning',
                'trigger' => null,
            ];
        }

        if ($status === JobStatus::PendingApproval) {
            return [
                'label' => 'Awaiting Job Approval',
                'helper' => 'This Job is waiting for approval before it can be scheduled for operations.',
                'url' => null,
                'kind' => 'attention',
                'trigger' => null,
            ];
        }

        if ($status === JobStatus::Approved) {
            if ($job->plannedOperatorName() === null) {
                return [
                    'label' => 'Edit Planning',
                    'helper' => 'Assign an operator before scheduling this Job for operations.',
                    'url' => JobResource::getUrl('edit', ['record' => $job]),
                    'kind' => 'planning',
                    'trigger' => null,
                ];
            }

            return [
                'label' => 'Schedule Job',
                'helper' => 'Move the Job into the scheduled state so operations can begin at the planned time.',
                'url' => null,
                'kind' => 'primary',
                'trigger' => 'schedule',
            ];
        }

        if ($status === JobStatus::Scheduled) {
            if ($job->plannedOperatorName() === null) {
                return [
                    'label' => 'Edit Planning',
                    'helper' => 'Assign an operator before starting this Job.',
                    'url' => JobResource::getUrl('edit', ['record' => $job]),
                    'kind' => 'planning',
                    'trigger' => null,
                ];
            }

            return [
                'label' => 'Start Job',
                'helper' => 'Starting the Job will move it into active operations and create the first Job Card automatically.',
                'url' => null,
                'kind' => 'primary',
                'trigger' => 'start',
            ];
        }

        if ($status === JobStatus::OnHold) {
            return [
                'label' => 'Resume Job',
                'helper' => 'Resume the Job before creating new operational work.',
                'url' => null,
                'kind' => 'primary',
                'trigger' => 'resume',
            ];
        }

        if ($status === JobStatus::InProgress) {
            if ($canCompleteJob && $completionBlockers === []) {
                return [
                    'label' => 'Complete Job',
                    'helper' => 'All Job Cards are approved and this Job is ready to be closed out.',
                    'url' => null,
                    'kind' => 'success',
                    'trigger' => 'complete',
                ];
            }

            if ($draftCards->isNotEmpty()) {
                $card = $draftCards->first();

                return [
                    'label' => 'Continue Job Card',
                    'helper' => 'Continue the active draft Job Card and keep recording operational work.',
                    'url' => JobCardResource::getUrl('edit', ['record' => $card]),
                    'kind' => 'primary',
                    'trigger' => null,
                ];
            }

            return [
                'label' => 'Create New Job Card',
                'helper' => $activeCard instanceof JobCard
                    ? 'The current Job is active and ready for the next operational card.'
                    : 'This Job is active but has no draft card open. Create the next operational record.',
                'url' => JobCardResource::getUrl('create', ['job' => $job]),
                'kind' => 'primary',
                'trigger' => null,
            ];
        }

        return null;
    }

    /**
     * @return array{label:string,url:string}|null
     */
    private function resolveCardAction(?JobCard $card, mixed $user): ?array
    {
        if (! $card instanceof JobCard || ! $user instanceof User) {
            return null;
        }

        $status = JobCardApprovalStatus::from((string) $card->getRawOriginal('approval_status'));

        return match ($status) {
            JobCardApprovalStatus::Draft => [
                'label' => 'Continue',
                'url' => JobCardResource::getUrl('edit', ['record' => $card]),
            ],
            JobCardApprovalStatus::Submitted => [
                'label' => $user->hasPermissionTo('jobs.approve') ? 'Review' : 'Awaiting Approval',
                'url' => JobCardResource::getUrl($user->hasPermissionTo('jobs.approve') ? 'edit' : 'view', ['record' => $card]),
            ],
            JobCardApprovalStatus::Returned => [
                'label' => 'Correct and Resubmit',
                'url' => JobCardResource::getUrl('edit', ['record' => $card]),
            ],
            JobCardApprovalStatus::Approved => [
                'label' => 'View',
                'url' => JobCardResource::getUrl('view', ['record' => $card]),
            ],
        };
    }
}
