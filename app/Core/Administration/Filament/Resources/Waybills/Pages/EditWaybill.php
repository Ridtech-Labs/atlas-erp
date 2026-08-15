<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Waybills\Pages;

use App\Core\Administration\Filament\Resources\Waybills\WaybillResource;
use App\Models\User;
use App\Operations\Actions\Waybills\MarkWaybillBillingReadyAction;
use App\Operations\Actions\Waybills\ReturnWaybillForCorrectionAction;
use App\Operations\Actions\Waybills\SubmitWaybillForVerificationAction;
use App\Operations\Actions\Waybills\UpdateWaybillAction;
use App\Operations\Actions\Waybills\VerifyWaybillAction;
use App\Operations\Enums\WaybillStatus;
use App\Operations\Models\Waybill;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditWaybill extends EditRecord
{
    protected static string $resource = WaybillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('submit')
                ->label('Send for verification')
                ->action(fn () => app(SubmitWaybillForVerificationAction::class)->execute($this->currentRecord(), $this->authenticatedUser()))
                ->visible(fn (): bool => $this->statusIsOneOf([WaybillStatus::Recorded, WaybillStatus::Returned])),
            Action::make('verify')
                ->label('Verify Waybill')
                ->action(fn () => app(VerifyWaybillAction::class)->execute($this->currentRecord(), $this->authenticatedUser()))
                ->visible(fn (): bool => $this->statusIs(WaybillStatus::PendingVerification)),
            Action::make('billingReady')
                ->label('Mark Billing Ready')
                ->action(fn () => app(MarkWaybillBillingReadyAction::class)->execute($this->currentRecord(), $this->authenticatedUser()))
                ->visible(fn (): bool => $this->statusIs(WaybillStatus::Verified)),
            Action::make('return')
                ->label('Return for correction')
                ->color('danger')
                ->form([
                    Textarea::make('return_reason')->required(),
                ])
                ->action(fn (array $data) => app(ReturnWaybillForCorrectionAction::class)->execute($this->currentRecord(), $this->authenticatedUser(), (string) $data['return_reason']))
                ->visible(fn (): bool => $this->statusIs(WaybillStatus::PendingVerification)),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof Waybill) {
            throw new \RuntimeException('Expected waybill record.');
        }

        $attachments = $this->normalizeAttachments($data['attachments'] ?? []);
        unset($data['attachments']);

        return app(UpdateWaybillAction::class)->execute($record, $data, $this->authenticatedUser(), $attachments);
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

    private function statusIs(WaybillStatus $status): bool
    {
        return (string) $this->currentRecord()->getRawOriginal('status') === $status->value;
    }

    /**
     * @param  list<WaybillStatus>  $statuses
     */
    private function statusIsOneOf(array $statuses): bool
    {
        return in_array(
            (string) $this->currentRecord()->getRawOriginal('status'),
            array_map(static fn (WaybillStatus $status): string => $status->value, $statuses),
            true,
        );
    }
}
