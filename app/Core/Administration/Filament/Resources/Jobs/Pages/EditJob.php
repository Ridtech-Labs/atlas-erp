<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Jobs\Pages;

use App\Core\Administration\Filament\Resources\Jobs\JobResource;
use App\Models\User;
use App\Operations\Actions\Jobs\ApproveJobAction;
use App\Operations\Actions\Jobs\CancelJobAction;
use App\Operations\Actions\Jobs\CompleteJobAction;
use App\Operations\Actions\Jobs\DeleteJobAction;
use App\Operations\Actions\Jobs\HoldJobAction;
use App\Operations\Actions\Jobs\ResumeJobAction;
use App\Operations\Actions\Jobs\ReturnJobToDraftAction;
use App\Operations\Actions\Jobs\ScheduleJobAction;
use App\Operations\Actions\Jobs\StartJobAction;
use App\Operations\Actions\Jobs\SubmitJobForApprovalAction;
use App\Operations\Actions\Jobs\UpdateJobAction;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use App\Operations\Services\JobWorkflowService;
use App\Operations\Support\JobPlanningReadinessService;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditJob extends EditRecord
{
    protected static string $resource = JobResource::class;

    public function getSubheading(): ?string
    {
        return 'Manage planning details and workflow actions without losing operational context.';
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->label('View job'),
            Action::make('submit')
                ->label('Submit for approval')
                ->action(fn () => app(SubmitJobForApprovalAction::class)->execute($this->currentJob(), $this->authenticatedUser()))
                ->visible(fn () => $this->record instanceof Job
                    && $this->statusIs(JobStatus::Draft)
                    && app(JobWorkflowService::class)->approvalRequired($this->currentJob())
                    && $this->authenticatedUser()->can('submit', $this->currentJob())),
            Action::make('returnToDraft')
                ->label('Return to draft')
                ->action(fn () => app(ReturnJobToDraftAction::class)->execute($this->currentJob(), $this->authenticatedUser()))
                ->visible(fn () => $this->record instanceof Job && $this->statusIs(JobStatus::PendingApproval) && $this->authenticatedUser()->can('update', $this->currentJob())),
            Action::make('approve')
                ->label('Approve')
                ->action(fn () => app(ApproveJobAction::class)->execute($this->currentJob(), $this->authenticatedUser()))
                ->visible(fn () => $this->record instanceof Job && $this->statusIs(JobStatus::PendingApproval) && $this->authenticatedUser()->can('approve', $this->currentJob())),
            Action::make('schedule')
                ->label('Mark Ready for Deployment')
                ->action(fn () => app(ScheduleJobAction::class)->execute($this->currentJob(), $this->authenticatedUser()))
                ->visible(fn () => $this->record instanceof Job
                    && $this->statusIsOneOf([JobStatus::Draft, JobStatus::Approved])
                    && app(JobPlanningReadinessService::class)->isReadyToSchedule($this->currentJob())
                    && $this->authenticatedUser()->can('schedule', $this->currentJob())),
            Action::make('start')
                ->label('Start job')
                ->action(fn () => app(StartJobAction::class)->execute($this->currentJob(), $this->authenticatedUser()))
                ->visible(fn () => $this->record instanceof Job && $this->statusIs(JobStatus::Scheduled) && $this->authenticatedUser()->can('start', $this->currentJob())),
            Action::make('hold')
                ->label('Put on hold')
                ->action(fn () => app(HoldJobAction::class)->execute($this->currentJob(), $this->authenticatedUser()))
                ->visible(fn () => $this->record instanceof Job && $this->statusIs(JobStatus::InProgress) && $this->authenticatedUser()->can('hold', $this->currentJob())),
            Action::make('resume')
                ->label('Resume')
                ->action(fn () => app(ResumeJobAction::class)->execute($this->currentJob(), $this->authenticatedUser()))
                ->visible(fn () => $this->record instanceof Job && $this->statusIs(JobStatus::OnHold) && $this->authenticatedUser()->can('resume', $this->currentJob())),
            Action::make('complete')
                ->label('Complete')
                ->form([
                    DateTimePicker::make('actual_end_date'),
                ])
                ->action(fn (array $data) => app(CompleteJobAction::class)->execute($this->currentJob(), $this->authenticatedUser(), $data))
                ->visible(fn () => $this->record instanceof Job && $this->statusIs(JobStatus::InProgress) && $this->authenticatedUser()->can('complete', $this->currentJob())),
            Action::make('cancel')
                ->label('Cancel')
                ->color('danger')
                ->form([
                    Textarea::make('cancellation_reason')->required(),
                ])
                ->action(fn (array $data) => app(CancelJobAction::class)->execute($this->currentJob(), $this->authenticatedUser(), $data))
                ->visible(fn () => $this->record instanceof Job && app(JobWorkflowService::class)->canCancel($this->currentJob()) && $this->authenticatedUser()->can('cancel', $this->currentJob())),
            Action::make('deleteDraft')
                ->label('Delete draft')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function (): void {
                    app(DeleteJobAction::class)->execute($this->currentJob(), $this->authenticatedUser());

                    Notification::make()
                        ->success()
                        ->title('Draft job deleted')
                        ->send();

                    $this->redirect(JobResource::getUrl('index'));
                })
                ->visible(fn () => $this->record instanceof Job && $this->statusIs(JobStatus::Draft) && $this->authenticatedUser()->can('delete', $this->currentJob())),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof Job) {
            throw new \RuntimeException('Expected job record.');
        }

        return app(UpdateJobAction::class)->execute($record, $data, $this->authenticatedUser());
    }

    protected function getRedirectUrl(): string
    {
        return JobResource::getUrl('view', ['record' => $this->currentJob()]);
    }

    private function authenticatedUser(): User
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            abort(403);
        }

        return $user;
    }

    private function currentJob(): Job
    {
        if (! $this->record instanceof Job) {
            throw new \RuntimeException('Expected job record.');
        }

        return $this->record;
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
