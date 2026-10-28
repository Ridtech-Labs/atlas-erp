<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Warehouses\Pages;

use App\Core\Administration\Filament\Resources\Warehouses\WarehouseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWarehouses extends ListRecords
{
    protected static string $resource = WarehouseResource::class;

    protected static ?string $title = 'Warehouses';

    protected ?string $subheading = 'Manage company storage sites and their default storage locations.';

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('New Warehouse')];
    }
}
