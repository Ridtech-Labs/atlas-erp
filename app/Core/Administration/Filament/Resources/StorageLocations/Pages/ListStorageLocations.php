<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\StorageLocations\Pages;

use App\Core\Administration\Filament\Resources\StorageLocations\StorageLocationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStorageLocations extends ListRecords
{
    protected static string $resource = StorageLocationResource::class;

    protected static ?string $title = 'Storage Locations';

    protected ?string $subheading = 'Manage active storage locations within the current company Warehouses.';

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('New Storage Location')];
    }
}
