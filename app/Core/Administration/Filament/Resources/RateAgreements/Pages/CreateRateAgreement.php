<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\RateAgreements\Pages;

use App\Core\Administration\Filament\Resources\RateAgreements\RateAgreementResource;
use App\Finance\Actions\RateAgreements\CreateRateAgreementAction;
use App\Finance\Models\RateAgreement;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateRateAgreement extends CreateRecord
{
    protected static string $resource = RateAgreementResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateRateAgreementAction::class)->execute($data, $this->authenticatedUser());
    }

    protected function getRedirectUrl(): string
    {
        $record = $this->getRecord();

        if (! $record instanceof RateAgreement) {
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
