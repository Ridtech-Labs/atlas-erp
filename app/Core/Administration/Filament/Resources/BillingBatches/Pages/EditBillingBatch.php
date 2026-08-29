<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\BillingBatches\Pages;

use App\Core\Administration\Filament\Resources\BillingBatches\BillingBatchResource;
use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Models\BillingBatch;
use App\Models\User;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditBillingBatch extends EditRecord
{
    protected static string $resource = BillingBatchResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        if ((string) $this->currentRecord()->getRawOriginal('status') === BillingBatchStatus::Prepared->value) {
            $this->redirect(BillingBatchResource::getUrl('view', ['record' => $this->currentRecord()]));
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof BillingBatch) {
            throw new \RuntimeException('Expected billing batch record.');
        }

        if ((string) $record->getRawOriginal('status') !== BillingBatchStatus::Draft->value) {
            throw new \RuntimeException('Prepared billing batches are immutable.');
        }

        $record->fill([
            'notes' => $data['notes'] ?? null,
            'updated_by' => $this->authenticatedUser()->getKey(),
        ]);
        $record->save();

        return $record;
    }

    private function currentRecord(): BillingBatch
    {
        if (! $this->record instanceof BillingBatch) {
            throw new \RuntimeException('Expected billing batch record.');
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
