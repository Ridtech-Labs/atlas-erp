<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\StorageLocations\Pages;

use App\Core\Administration\Filament\Resources\StorageLocations\StorageLocationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStorageLocation extends ViewRecord
{
    protected static string $resource = StorageLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
