<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\InventoryItems\RelationManagers;

use App\Inventory\Enums\StockMovementType;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockMovementsRelationManager extends RelationManager
{
    protected static string $relationship = 'stockMovements';

    protected static ?string $title = 'Movement History';

    protected static bool $isLazy = false;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('occurred_at')->label('Date / time')->dateTime()->sortable(),
                TextColumn::make('movement_type')->label('Type')->badge()->formatStateUsing(fn (StockMovementType $state): string => $state->label()),
                TextColumn::make('signed_quantity_delta')
                    ->label('Quantity change')
                    ->formatStateUsing(fn (mixed $state): string => str_starts_with((string) $state, '-') ? number_format((float) $state, 3) : '+'.number_format((float) $state, 3))
                    ->color(fn (mixed $state): string => str_starts_with((string) $state, '-') ? 'danger' : 'success'),
                TextColumn::make('quantity_before')->label('Before')->numeric(decimalPlaces: 3),
                TextColumn::make('quantity_after')->label('After')->numeric(decimalPlaces: 3),
                TextColumn::make('reference')->placeholder('Not recorded'),
                TextColumn::make('fleetAsset.asset_number')->label('Fleet asset')->placeholder('Not linked'),
                TextColumn::make('job.job_number')->label('Job')->placeholder('Not linked'),
                TextColumn::make('personnel.full_name')->label('Personnel')->placeholder('Not linked'),
                TextColumn::make('performer.full_name')->label('Performed by')->placeholder('System'),
            ])
            ->defaultSort('occurred_at', 'desc')
            ->emptyStateHeading('No stock movements yet')
            ->emptyStateDescription('Record an opening balance, receipt, issue, or adjustment to establish the item ledger.');
    }
}
