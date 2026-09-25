<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Waybills\Pages;

use App\Core\Administration\Filament\Resources\Waybills\WaybillResource;
use App\Models\User;
use App\Operations\Actions\Waybills\ReturnWaybillForCorrectionAction;
use App\Operations\Actions\Waybills\VerifyWaybillAction;
use App\Operations\Enums\WaybillStatus;
use App\Operations\Models\Waybill;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

class ViewWaybill extends ViewRecord
{
    protected static string $resource = WaybillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewEvidence')
                ->label('View Evidence')
                ->url(fn (): ?string => $this->evidenceUrl())
                ->openUrlInNewTab()
                ->visible(fn (): bool => $this->currentRecord()->getMedia('waybill-documents')->isNotEmpty()),
            Action::make('downloadEvidence')
                ->label('Download Evidence')
                ->url(fn (): ?string => $this->evidenceUrl(true))
                ->visible(fn (): bool => $this->currentRecord()->getMedia('waybill-documents')->isNotEmpty()),
            Action::make('verify')
                ->label('Verify Waybill')
                ->action(fn () => app(VerifyWaybillAction::class)->execute($this->currentRecord(), $this->authenticatedUser()))
                ->visible(fn (): bool => $this->canReview()),
            Action::make('return')
                ->label('Return for correction')
                ->color('danger')
                ->form([
                    Textarea::make('return_reason')->required(),
                ])
                ->action(fn (array $data) => app(ReturnWaybillForCorrectionAction::class)->execute($this->currentRecord(), $this->authenticatedUser(), (string) $data['return_reason']))
                ->visible(fn (): bool => $this->canReview()),
            EditAction::make()
                ->visible(fn (): bool => ! $this->currentRecord()->evidenceIsLockedForEditing()),
        ];
    }

    private function canReview(): bool
    {
        return (string) $this->currentRecord()->getRawOriginal('status') === WaybillStatus::PendingVerification->value
            && $this->authenticatedUser()->can('approve', $this->currentRecord());
    }

    private function evidenceUrl(bool $download = false): ?string
    {
        $media = $this->currentRecord()->getFirstMedia('waybill-documents');

        return $media === null ? null : route('atlas.waybills.evidence.show', [
            'waybill' => $this->currentRecord(),
            'media' => $media,
            'download' => $download ? 1 : null,
        ]);
    }

    private function currentRecord(): Waybill
    {
        if (! $this->record instanceof Waybill) {
            throw new \RuntimeException('Expected waybill record.');
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
