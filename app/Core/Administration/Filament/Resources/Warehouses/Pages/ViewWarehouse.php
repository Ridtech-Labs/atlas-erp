<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Warehouses\Pages;

use App\Core\Administration\Filament\Resources\Warehouses\WarehouseResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWarehouse extends ViewRecord
{
    protected static string $resource = WarehouseResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
