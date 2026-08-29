<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\BillingBatches\Pages;

use App\Core\Administration\Filament\Resources\BillingBatches\BillingBatchResource;
use App\Finance\Actions\BillingBatches\CreateBillingBatchAction;
use App\Finance\Models\BillingBatch;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBillingBatch extends CreateRecord
{
    protected static string $resource = BillingBatchResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateBillingBatchAction::class)->execute($data, $this->authenticatedUser());
    }

    protected function getRedirectUrl(): string
    {
        $record = $this->getRecord();

        if (! $record instanceof BillingBatch) {
            return static::getResource()::getUrl('index');
        }

        return static::getResource()::getUrl('view', ['record' => $record]);
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
