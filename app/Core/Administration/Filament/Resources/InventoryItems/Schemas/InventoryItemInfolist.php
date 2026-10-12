<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\InventoryItems\Schemas;

use App\Inventory\Enums\InventoryItemStatus;
use App\Inventory\Models\InventoryItem;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InventoryItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Inventory item')
                ->schema([
                    TextEntry::make('code')->label('Item code'),
                    TextEntry::make('name')->label('Item name'),
                    TextEntry::make('category'),
                    TextEntry::make('unit_of_measure')->label('Unit')->formatStateUsing(fn ($state): string => $state->label()),
                    TextEntry::make('quantity_on_hand')->label('Quantity on hand')->numeric(decimalPlaces: 3),
                    TextEntry::make('minimum_stock_level')->label('Minimum level')->numeric(decimalPlaces: 3),
                    TextEntry::make('stock_status')
                        ->label('Stock status')
                        ->state(fn (InventoryItem $record): string => $record->stockStatus()->label())
                        ->badge()
                        ->color(fn (InventoryItem $record): string => $record->stockStatus()->color()),
                    TextEntry::make('status')->badge()->formatStateUsing(fn (InventoryItemStatus $state): string => $state->label())->color(fn (InventoryItemStatus $state): string => $state->color()),
                    TextEntry::make('description')->placeholder('No description recorded')->columnSpanFull(),
                    TextEntry::make('creator.full_name')->label('Created by')->placeholder('System')->visible(fn (InventoryItem $record): bool => $record->creator !== null),
                    TextEntry::make('updated_at')->label('Last updated')->since(),
                ])
                ->columns(2),
        ]);
    }
}
