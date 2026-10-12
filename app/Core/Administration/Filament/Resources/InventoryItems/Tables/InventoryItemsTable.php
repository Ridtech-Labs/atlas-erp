<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\InventoryItems\Tables;

use App\Inventory\Enums\InventoryItemStatus;
use App\Inventory\Enums\InventoryStockStatus;
use App\Inventory\Enums\UnitOfMeasure;
use App\Inventory\Models\InventoryItem;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InventoryItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->label('Code')->searchable()->sortable(),
                TextColumn::make('name')->label('Item')->searchable()->sortable(),
                TextColumn::make('category')->searchable()->sortable(),
                TextColumn::make('unit_of_measure')->label('Unit')->badge()->formatStateUsing(fn (UnitOfMeasure $state): string => $state->label()),
                TextColumn::make('quantity_on_hand')->label('Quantity on hand')->numeric(decimalPlaces: 3)->sortable(),
                TextColumn::make('minimum_stock_level')->label('Minimum level')->numeric(decimalPlaces: 3)->sortable(),
                TextColumn::make('stock_status')
                    ->label('Stock status')
                    ->state(fn (InventoryItem $record): string => $record->stockStatus()->label())
                    ->badge()
                    ->color(fn (InventoryItem $record): string => $record->stockStatus()->color()),
                TextColumn::make('status')->badge()->formatStateUsing(fn (InventoryItemStatus $state): string => $state->label())->color(fn (InventoryItemStatus $state): string => $state->color()),
            ])
            ->filters([
                SelectFilter::make('status')->options(collect(InventoryItemStatus::cases())->mapWithKeys(fn (InventoryItemStatus $status): array => [$status->value => $status->label()])->all()),
                SelectFilter::make('unit_of_measure')->label('Unit')->options(collect(UnitOfMeasure::cases())->mapWithKeys(fn (UnitOfMeasure $unit): array => [$unit->value => $unit->label()])->all()),
                SelectFilter::make('stock_status')
                    ->options(collect(InventoryStockStatus::cases())->mapWithKeys(fn (InventoryStockStatus $status): array => [$status->value => $status->label()])->all())
                    ->query(
                        /** @param Builder<InventoryItem> $query */
                        function (Builder $query, array $data): Builder {
                            $status = InventoryStockStatus::tryFrom($data['value'] ?? '');

                            return $status instanceof InventoryStockStatus
                                ? InventoryItem::applyStockStatus($query, $status)
                                : $query;
                        }),
            ])
            ->defaultSort('name')
            ->searchPlaceholder('Search Inventory by code, item, or category')
            ->emptyStateHeading('No Inventory items yet')
            ->emptyStateDescription('Create controlled Item Master records before recording opening balances and stock movements.')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()->requiresConfirmation(),
            ]);
    }
}
