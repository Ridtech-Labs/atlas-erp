<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Warehouses\RelationManagers;

use App\Core\Administration\Filament\Resources\StorageLocations\StorageLocationResource;
use App\Inventory\Models\StorageLocation;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StorageLocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'storageLocations';

    protected static ?string $title = 'Storage Locations';

    protected static bool $isLazy = false;

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('code')->url(fn (StorageLocation $record): string => StorageLocationResource::getUrl('view', ['record' => $record])),
            TextColumn::make('name'), IconColumn::make('is_default')->label('Default')->boolean(), IconColumn::make('is_active')->label('Active')->boolean(), TextColumn::make('updated_at')->label('Updated')->since(),
        ])->defaultSort('name')->emptyStateHeading('No Storage Locations yet');
    }
}
