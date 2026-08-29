<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\JobCards\Pages;

use App\Core\Administration\Filament\Resources\JobCards\JobCardResource;
use App\Models\User;
use App\Operations\Actions\JobCards\ApproveJobCardAction;
use App\Operations\Actions\JobCards\ReturnJobCardAction;
use App\Operations\Actions\JobCards\SubmitJobCardAction;
use App\Operations\Actions\JobCards\UpdateJobCardAction;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Models\JobCard;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditJobCard extends EditRecord
{
    protected static string $resource = JobCardResource::class;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        if ($this->currentRecord()->evidenceIsLockedForEditing()) {
            abort_unless(static::getResource()::canView($this->currentRecord()), 403);

            $this->redirect(JobCardResource::getUrl('view', ['record' => $this->currentRecord()]));

            return;
        }

        $this->authorizeAccess();
        $this->fillForm();
        $this->previousUrl = url()->previous();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('submit')
                ->label('Submit to Accounts')
                ->action(fn () => app(SubmitJobCardAction::class)->execute($this->currentRecord(), $this->authenticatedUser()))
                ->visible(fn (): bool => $this->canSubmit()),
            Action::make('approve')
                ->label('Review for Billing')
                ->form([
                    Textarea::make('verification_notes')
                        ->label('Accounts review notes')
                        ->placeholder('Record any Accounts review notes captured during billing review.'),
                ])
                ->action(fn (array $data) => app(ApproveJobCardAction::class)->execute($this->currentRecord(), $this->authenticatedUser(), $data['verification_notes'] ?? null))
                ->visible(fn (): bool => $this->canReview()),
            Action::make('return')
                ->label('Return to Operations')
                ->color('danger')
                ->form([
                    Textarea::make('return_reason')->label('Return reason')->required(),
                ])
                ->action(fn (array $data) => app(ReturnJobCardAction::class)->execute($this->currentRecord(), $this->authenticatedUser(), (string) $data['return_reason']))
                ->visible(fn (): bool => $this->canReview()),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof JobCard) {
            throw new \RuntimeException('Expected job card record.');
        }

        $attachments = $this->normalizeAttachments($data['attachments'] ?? []);
        unset($data['attachments']);

        return app(UpdateJobCardAction::class)->execute($record, $data, $this->authenticatedUser(), $attachments);
    }

    /**
     * @return list<string>
     */
    private function normalizeAttachments(mixed $attachments): array
    {
        if (! is_array($attachments)) {
            return [];
        }

        return array_values(array_filter($attachments, static fn (mixed $path): bool => is_string($path) && $path !== ''));
    }

    private function currentRecord(): JobCard
    {
        if (! $this->record instanceof JobCard) {
            throw new \RuntimeException('Expected job card record.');
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

    private function approvalStatusIs(JobCardApprovalStatus $status): bool
    {
        return (string) $this->currentRecord()->getRawOriginal('approval_status') === $status->value;
    }

    /**
     * @param  list<JobCardApprovalStatus>  $statuses
     */
    private function approvalStatusIsOneOf(array $statuses): bool
    {
        return in_array(
            (string) $this->currentRecord()->getRawOriginal('approval_status'),
            array_map(static fn (JobCardApprovalStatus $status): string => $status->value, $statuses),
            true,
        );
    }

    private function canSubmit(): bool
    {
        return $this->approvalStatusIsOneOf([JobCardApprovalStatus::Draft, JobCardApprovalStatus::Recorded, JobCardApprovalStatus::Returned])
            && $this->authenticatedUser()->hasPermissionTo('jobs.submit')
            && $this->authenticatedUser()->can('update', $this->currentRecord());
    }

    private function canReview(): bool
    {
        return $this->approvalStatusIs(JobCardApprovalStatus::PendingVerification)
            && $this->authenticatedUser()->can('approve', $this->currentRecord());
    }
}
