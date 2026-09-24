<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Waybills\Pages;

use App\Core\Administration\Filament\Resources\Waybills\WaybillResource;
use App\Models\User;
use App\Operations\Actions\Waybills\SubmitWaybillForVerificationAction;
use App\Operations\Actions\Waybills\UpdateWaybillAction;
use App\Operations\Enums\WaybillStatus;
use App\Operations\Models\Waybill;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditWaybill extends EditRecord
{
    protected static string $resource = WaybillResource::class;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        if ($this->currentRecord()->evidenceIsLockedForEditing()) {
            abort_unless(static::getResource()::canView($this->currentRecord()), 403);
            $this->redirect(WaybillResource::getUrl('view', ['record' => $this->currentRecord()]));

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
                ->label('Send for verification')
                ->action(fn () => app(SubmitWaybillForVerificationAction::class)->execute($this->currentRecord(), $this->authenticatedUser()))
                ->visible(fn (): bool => $this->statusIsOneOf([WaybillStatus::Recorded, WaybillStatus::Returned])),
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
