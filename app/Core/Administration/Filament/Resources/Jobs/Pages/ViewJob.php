<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Jobs\Pages;

use App\Core\Administration\Filament\Resources\Jobs\JobResource;
use App\Core\Administration\Filament\Widgets\JobWorkspaceWidget;
use App\Models\User;
use App\Operations\Actions\Jobs\ApproveJobAction;
use App\Operations\Actions\Jobs\CancelJobAction;
use App\Operations\Actions\Jobs\CompleteJobAction;
use App\Operations\Actions\Jobs\DeleteJobAction;
use App\Operations\Actions\Jobs\HoldJobAction;
use App\Operations\Actions\Jobs\ResumeJobAction;
use App\Operations\Actions\Jobs\ScheduleJobAction;
use App\Operations\Actions\Jobs\StartJobAction;
use App\Operations\Actions\Jobs\SubmitJobForApprovalAction;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use App\Operations\Services\JobWorkflowService;
use App\Operations\Support\JobPlanningReadinessService;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewJob extends ViewRecord
{
    protected static string $resource = JobResource::class;

    public function getTitle(): string
    {
        $record = $this->getRecord();

        return $record instanceof Job ? $record->job_number : 'Job';
    }

    public function getSubheading(): ?string
    {
        return 'Operational summary, commercial context, and workflow progress.';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            JobWorkspaceWidget::make(['record' => $this->getRecord()]),
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label('Edit Planning')
                ->hidden()
                ->url(fn (): string => JobResource::getUrl('edit', ['record' => $this->currentJob()])),
            Action::make('submit')
                ->label('Submit for Approval')
                ->hidden()
                ->action(fn () => $this->submitJob())
                ->visible(fn (): bool => $this->record instanceof Job
                    && $this->statusIs(JobStatus::Draft)
                    && app(JobWorkflowService::class)->approvalRequired($this->currentJob())
                    && $this->authenticatedUser()->can('submit', $this->record)),
            Action::make('approve')
                ->label('Approve Job')
                ->hidden()
                ->action(fn () => $this->approveJob())
                ->visible(fn (): bool => $this->record instanceof Job && $this->statusIs(JobStatus::PendingApproval) && $this->authenticatedUser()->can('approve', $this->record)),
            Action::make('schedule')
                ->label('Mark Ready for Deployment')
                ->hidden()
                ->action(fn () => $this->scheduleJob())
                ->visible(fn (): bool => $this->record instanceof Job
                    && $this->statusIsOneOf([JobStatus::Draft, JobStatus::Approved])
                    && (! app(JobWorkflowService::class)->approvalRequired($this->currentJob()) || $this->statusIs(JobStatus::Approved))
                    && app(JobPlanningReadinessService::class)->isReadyToSchedule($this->currentJob())
                    && $this->authenticatedUser()->can('schedule', $this->record)),
            Action::make('start')
                ->label('Start Job')
                ->hidden()
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Start this Job?')
                ->modalDescription('Atlas will move the Job to In Progress. The client-issued operational document can then be recorded from live work.')
                ->modalSubmitActionLabel('Start Job')
                ->action(fn () => $this->startJob())
                ->visible(fn (): bool => $this->record instanceof Job && $this->statusIs(JobStatus::Scheduled) && $this->authenticatedUser()->can('start', $this->record)),
            Action::make('hold')
                ->label('Put On Hold')
                ->hidden()
                ->action(fn () => $this->holdJob())
                ->visible(fn (): bool => $this->record instanceof Job && $this->statusIs(JobStatus::InProgress) && $this->authenticatedUser()->can('hold', $this->record)),
            Action::make('resume')
                ->label('Resume')
                ->hidden()
                ->action(fn () => $this->resumeJob())
                ->visible(fn (): bool => $this->record instanceof Job && $this->statusIs(JobStatus::OnHold) && $this->authenticatedUser()->can('resume', $this->record)),
            Action::make('complete')
                ->label('Complete Job')
                ->hidden()
                ->form([
                    DateTimePicker::make('actual_end_date'),
                ])
                ->action(fn (array $data) => $this->completeJob($data))
                ->visible(fn (): bool => $this->record instanceof Job && $this->statusIs(JobStatus::InProgress) && $this->authenticatedUser()->can('complete', $this->record)),
            Action::make('cancel')
                ->label('Cancel Job')
                ->color('danger')
                ->form([
                    Textarea::make('cancellation_reason')->required(),
                ])
                ->action(fn (array $data) => $this->cancelJob($data))
                ->visible(fn (): bool => $this->record instanceof Job
                    && app(JobWorkflowService::class)->canCancel($this->currentJob())
                    && $this->authenticatedUser()->can('cancel', $this->record)),
            Action::make('deleteDraft')
                ->label('Delete Job')
                ->hidden()
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Delete this draft Job?')
                ->modalDescription('Use delete only when this Job was created accidentally and should not remain in operational history.')
                ->action(fn () => $this->deleteJob())
                ->visible(fn (): bool => $this->record instanceof Job && $this->statusIs(JobStatus::Draft) && $this->authenticatedUser()->can('delete', $this->record)),
        ];
    }

    protected function hasInfolist(): bool
    {
        return false;
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function getRelationManagers(): array
    {
        return [];
    }

    private function submitJob(): void
    {
        $job = app(SubmitJobForApprovalAction::class)->execute($this->currentJob(), $this->authenticatedUser());

        Notification::make()
            ->success()
            ->title('Job submitted for approval')
            ->body("{$job->job_number} is now awaiting approval.")
            ->send();

        $this->redirect(JobResource::getUrl('view', ['record' => $job]));
    }

    private function approveJob(): void
    {
        $job = app(ApproveJobAction::class)->execute($this->currentJob(), $this->authenticatedUser());

        Notification::make()
            ->success()
            ->title('Job approved')
            ->body("{$job->job_number} can now be marked ready for deployment.")
            ->send();

        $this->redirect(JobResource::getUrl('view', ['record' => $job]));
    }

    private function scheduleJob(): void
    {
        $job = app(ScheduleJobAction::class)->execute($this->currentJob(), $this->authenticatedUser());

        Notification::make()
            ->success()
            ->title('Job marked ready for deployment')
            ->body("{$job->job_number} is ready to be sent to the client-allocated work location.")
            ->send();

        $this->redirect(JobResource::getUrl('view', ['record' => $job]));
    }

    private function startJob(): void
    {
        $job = app(StartJobAction::class)->execute($this->currentJob(), $this->authenticatedUser());

        Notification::make()
            ->success()
            ->title('Job started')
            ->body("{$job->job_number} is now in progress. Record the client-issued operational document from live work when it is available.")
            ->send();

        $this->redirect(JobResource::getUrl('view', ['record' => $job]));
    }

    private function holdJob(): void
    {
        $job = app(HoldJobAction::class)->execute($this->currentJob(), $this->authenticatedUser());

        Notification::make()
            ->warning()
            ->title('Job placed on hold')
            ->body("{$job->job_number} is paused. Existing draft work remains intact.")
            ->send();

        $this->redirect(JobResource::getUrl('view', ['record' => $job]));
    }

    private function resumeJob(): void
    {
        $job = app(ResumeJobAction::class)->execute($this->currentJob(), $this->authenticatedUser());

        Notification::make()
            ->success()
            ->title('Job resumed')
            ->body("{$job->job_number} is back in progress.")
            ->send();

        $this->redirect(JobResource::getUrl('view', ['record' => $job]));
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function completeJob(array $data): void
    {
        $job = app(CompleteJobAction::class)->execute($this->currentJob(), $this->authenticatedUser(), $data);

        Notification::make()
            ->success()
            ->title('Job completed')
            ->body("{$job->job_number} has been completed successfully.")
            ->send();

        $this->redirect(JobResource::getUrl('view', ['record' => $job]));
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function cancelJob(array $data): void
    {
        $job = app(CancelJobAction::class)->execute($this->currentJob(), $this->authenticatedUser(), $data);

        Notification::make()
            ->warning()
            ->title('Job cancelled')
            ->body("{$job->job_number} has been cancelled.")
            ->send();

        $this->redirect(JobResource::getUrl('view', ['record' => $job]));
    }

    private function deleteJob(): void
    {
        $jobNumber = $this->currentJob()->job_number;

        app(DeleteJobAction::class)->execute($this->currentJob(), $this->authenticatedUser());

        Notification::make()
            ->success()
            ->title('Draft job deleted')
            ->body("{$jobNumber} has been removed safely.")
            ->send();

        $this->redirect(JobResource::getUrl('index'));
    }

    private function currentJob(): Job
    {
        if (! $this->record instanceof Job) {
            throw new \RuntimeException('Expected job record.');
        }

        return $this->record;
    }

    private function authenticatedUser(): User
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            abort(403);
        }

        return $user;
    }

    private function statusIs(JobStatus $status): bool
    {
        return (string) $this->currentJob()->getRawOriginal('status') === $status->value;
    }

    /**
     * @param  list<JobStatus>  $statuses
     */
    private function statusIsOneOf(array $statuses): bool
    {
        return in_array(
            (string) $this->currentJob()->getRawOriginal('status'),
            array_map(static fn (JobStatus $status): string => $status->value, $statuses),
            true,
        );
    }
}
