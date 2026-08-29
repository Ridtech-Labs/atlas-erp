<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\BillingBatches\Pages;

use App\Core\Administration\Filament\Resources\BillingBatches\BillingBatchResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBillingBatches extends ListRecords
{
    protected static string $resource = BillingBatchResource::class;

    protected static ?string $title = 'Billing Batches';

    protected ?string $subheading = 'Prepare commercial billing batches from Accounts-reviewed operational evidence without mutating the source records.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('New billing batch'),
        ];
    }
}
