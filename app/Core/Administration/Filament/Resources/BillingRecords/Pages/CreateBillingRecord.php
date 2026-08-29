<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\BillingRecords\Pages;

use App\Core\Administration\Filament\Resources\BillingRecords\BillingRecordResource;
use App\Finance\Actions\BillingRecords\CreateBillingRecordAction;
use App\Finance\Models\BillingRecord;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBillingRecord extends CreateRecord
{
    protected static string $resource = BillingRecordResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateBillingRecordAction::class)->execute($data, $this->authenticatedUser());
    }

    protected function getRedirectUrl(): string
    {
        $record = $this->getRecord();

        return $record instanceof BillingRecord
            ? static::getResource()::getUrl('view', ['record' => $record])
            : static::getResource()::getUrl('index');
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
