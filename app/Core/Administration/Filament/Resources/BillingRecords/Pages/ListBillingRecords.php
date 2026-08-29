<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\BillingRecords\Pages;

use App\Core\Administration\Filament\Resources\BillingRecords\BillingRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBillingRecords extends ListRecords
{
    protected static string $resource = BillingRecordResource::class;

    protected static ?string $title = 'Billing Records';

    protected ?string $subheading = 'Track externally issued VAT receipts and payment against prepared commercial billing snapshots.';

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('New Billing Record')];
    }
}
