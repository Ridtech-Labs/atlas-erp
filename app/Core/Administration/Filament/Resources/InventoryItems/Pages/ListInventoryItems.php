<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\InventoryItems\Pages;

use App\Core\Administration\Filament\Resources\InventoryItems\InventoryItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInventoryItems extends ListRecords
{
    protected static string $resource = InventoryItemResource::class;

    protected static ?string $title = 'Inventory Items';

    protected ?string $subheading = 'Maintain the company Item Master. Stock quantities change only through auditable Inventory movements.';

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('New Inventory item')];
    }
}
