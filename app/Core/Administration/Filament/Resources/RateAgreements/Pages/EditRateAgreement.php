<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\RateAgreements\Pages;

use App\Core\Administration\Filament\Resources\RateAgreements\RateAgreementResource;
use App\Finance\Actions\RateAgreements\UpdateRateAgreementAction;
use App\Finance\Models\RateAgreement;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditRateAgreement extends EditRecord
{
    protected static string $resource = RateAgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()->requiresConfirmation(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof RateAgreement) {
            throw new \RuntimeException('Expected rate agreement record.');
        }

        return app(UpdateRateAgreementAction::class)->execute($record, $data, $this->authenticatedUser());
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
