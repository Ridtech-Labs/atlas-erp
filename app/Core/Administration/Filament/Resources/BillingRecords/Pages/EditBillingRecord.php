<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\BillingRecords\Pages;

use App\Core\Administration\Filament\Resources\BillingRecords\BillingRecordResource;
use App\Finance\Actions\BillingRecords\UpdateBillingRecordDraftAction;
use App\Finance\Enums\BillingRecordStatus;
use App\Finance\Models\BillingRecord;
use App\Models\User;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditBillingRecord extends EditRecord
{
    protected static string $resource = BillingRecordResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        if ($this->currentRecord()->status !== BillingRecordStatus::Draft) {
            $this->redirect(BillingRecordResource::getUrl('view', ['record' => $this->currentRecord()]));
        }
    }

    protected function getHeaderActions(): array
    {
        return [ViewAction::make()];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof BillingRecord) {
            throw new \RuntimeException('Expected Billing Record.');
        }

        return app(UpdateBillingRecordDraftAction::class)->execute($record, $data, $this->authenticatedUser());
    }

    private function currentRecord(): BillingRecord
    {
        if (! $this->record instanceof BillingRecord) {
            throw new \RuntimeException('Expected Billing Record.');
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
