<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Jobs\Pages;

use App\Core\Administration\Filament\Resources\Jobs\JobResource;
use App\Models\User;
use App\Operations\Actions\Jobs\ApproveJobAction;
use App\Operations\Actions\Jobs\CancelJobAction;
use App\Operations\Actions\Jobs\CompleteJobAction;
use App\Operations\Actions\Jobs\HoldJobAction;
use App\Operations\Actions\Jobs\ResumeJobAction;
use App\Operations\Actions\Jobs\ReturnJobToDraftAction;
use App\Operations\Actions\Jobs\ScheduleJobAction;
use App\Operations\Actions\Jobs\StartJobAction;
use App\Operations\Actions\Jobs\SubmitJobForApprovalAction;
use App\Operations\Actions\Jobs\UpdateJobAction;
use App\Operations\Models\Job;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
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
                ->visible(fn () => $this->record instanceof Job),
            Action::make('returnToDraft')
                ->label('Return to draft')
                ->action(fn () => app(ReturnJobToDraftAction::class)->execute($this->currentJob(), $this->authenticatedUser()))
                ->visible(fn () => $this->record instanceof Job),
            Action::make('approve')
                ->label('Approve')
                ->action(fn () => app(ApproveJobAction::class)->execute($this->currentJob(), $this->authenticatedUser()))
                ->visible(fn () => $this->record instanceof Job),
            Action::make('schedule')
                ->label('Schedule')
                ->action(fn () => app(ScheduleJobAction::class)->execute($this->currentJob(), $this->authenticatedUser()))
                ->visible(fn () => $this->record instanceof Job),
            Action::make('start')
                ->label('Start job')
                ->action(fn () => app(StartJobAction::class)->execute($this->currentJob(), $this->authenticatedUser()))
                ->visible(fn () => $this->record instanceof Job),
            Action::make('hold')
                ->label('Put on hold')
                ->action(fn () => app(HoldJobAction::class)->execute($this->currentJob(), $this->authenticatedUser()))
                ->visible(fn () => $this->record instanceof Job),
            Action::make('resume')
                ->label('Resume')
                ->action(fn () => app(ResumeJobAction::class)->execute($this->currentJob(), $this->authenticatedUser()))
                ->visible(fn () => $this->record instanceof Job),
            Action::make('complete')
                ->label('Complete')
                ->form([
                    DateTimePicker::make('actual_end_date'),
                ])
                ->action(fn (array $data) => app(CompleteJobAction::class)->execute($this->currentJob(), $this->authenticatedUser(), $data))
                ->visible(fn () => $this->record instanceof Job),
            Action::make('cancel')
                ->label('Cancel')
                ->color('danger')
                ->form([
                    Textarea::make('cancellation_reason')->required(),
                ])
                ->action(fn (array $data) => app(CancelJobAction::class)->execute($this->currentJob(), $this->authenticatedUser(), $data))
                ->visible(fn () => $this->record instanceof Job),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof Job) {
            throw new \RuntimeException('Expected job record.');
        }

        return app(UpdateJobAction::class)->execute($record, $data, $this->authenticatedUser());
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
}
