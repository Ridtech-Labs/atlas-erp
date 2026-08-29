<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\JobCards\Pages;

use App\Core\Administration\Filament\Resources\JobCards\JobCardResource;
use App\Core\Administration\Filament\Resources\Jobs\JobResource;
use App\Models\User;
use App\Operations\Actions\JobCards\ApproveJobCardAction;
use App\Operations\Actions\JobCards\ReturnJobCardAction;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Models\JobCard;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

class ViewJobCard extends ViewRecord
{
    protected static string $resource = JobCardResource::class;

    public function getTitle(): string
    {
        $record = $this->getRecord();
        $cardDate = $record instanceof JobCard ? $record->getAttribute('card_date') : null;

        return $record instanceof JobCard
            ? sprintf('Client Job Card %s', filled($cardDate) ? CarbonImmutable::parse((string) $cardDate)->format('j M Y') : $record->getKey())
            : 'Client Job Card';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewJob')
                ->label('View Job')
                ->url(fn (): string => JobResource::getUrl('view', ['record' => $this->currentRecord()->job_id])),
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
            EditAction::make()
                ->visible(fn (): bool => ! $this->currentRecord()->evidenceIsLockedForEditing()),
        ];
    }

    private function canReview(): bool
    {
        return (string) $this->currentRecord()->getRawOriginal('approval_status') === JobCardApprovalStatus::PendingVerification->value
            && $this->authenticatedUser()->can('approve', $this->currentRecord());
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
}
