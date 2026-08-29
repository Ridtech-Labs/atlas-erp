<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\RateAgreements\Pages;

use App\Core\Administration\Filament\Resources\RateAgreements\RateAgreementResource;
use App\Finance\Models\RateAgreement;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRateAgreement extends ViewRecord
{
    protected static string $resource = RateAgreementResource::class;

    public function getTitle(): string
    {
        $record = $this->getRecord();

        return $record instanceof RateAgreement ? $record->name : 'Rate Agreement';
    }

    public function getSubheading(): ?string
    {
        return 'Client commercial pricing, date-effective rate lines, and the matching criteria Finance will rely on during billing preparation.';
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
