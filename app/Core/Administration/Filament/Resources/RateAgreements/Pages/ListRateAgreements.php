<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\RateAgreements\Pages;

use App\Core\Administration\Filament\Resources\RateAgreements\RateAgreementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRateAgreements extends ListRecords
{
    protected static string $resource = RateAgreementResource::class;

    protected static ?string $title = 'Rate Agreements';

    protected ?string $subheading = 'Maintain client pricing agreements that Finance will use when converting reviewed operational evidence into billable batches.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('New rate agreement'),
        ];
    }
}
