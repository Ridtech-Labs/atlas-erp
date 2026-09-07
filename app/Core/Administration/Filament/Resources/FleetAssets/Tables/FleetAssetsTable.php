<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\FleetAssets\Tables;

use App\Fleet\Enums\FleetAssetCategory;
use App\Fleet\Enums\FleetAssetOperationalStatus;
use App\Fleet\Models\FleetAsset;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FleetAssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('asset_number')->label('Asset number')->searchable()->sortable()->weight('bold'),
                TextColumn::make('type.name')->label('Type')->searchable()->sortable(),
                TextColumn::make('type.category')->label('Category')->badge()->formatStateUsing(fn (FleetAssetCategory $state): string => $state->label()),
                TextColumn::make('registration_number')->label('Registration')->searchable()->placeholder('Not registered'),
                TextColumn::make('make')->searchable()->toggleable(),
                TextColumn::make('model')->searchable()->toggleable(),
                TextColumn::make('current_state')
                    ->label('Current state')
                    ->badge()
                    ->state(fn (FleetAsset $record): string => $record->derivedAvailability()['label'])
                    ->description(function (FleetAsset $record): ?string {
                        $assignment = $record->derivedAvailability()['assignment'];
                        if ($assignment === null) {
                            return null;
                        }

                        return $assignment->job->job_number ?? 'Assigned Job';
                    })
                    ->color(fn (FleetAsset $record): string => match ($record->derivedAvailability()['label']) {
                        'Dispatched' => 'warning', 'Assigned' => 'info', 'Available' => 'success', default => 'gray',
                    }),
                TextColumn::make('operational_status')->label('Operational status')->badge()->formatStateUsing(fn (FleetAssetOperationalStatus $state): string => $state->label())->color(fn (FleetAssetOperationalStatus $state): string => $state->color()),
                TextColumn::make('updated_at')->since()->label('Updated')->sortable()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('fleet_asset_type_id')->label('Asset type')->relationship('type', 'name'),
                SelectFilter::make('operational_status')->label('Operational status')->options(collect(FleetAssetOperationalStatus::cases())->mapWithKeys(fn (FleetAssetOperationalStatus $status): array => [$status->value => $status->label()])->all()),
            ])
            ->defaultSort('asset_number')
            ->searchPlaceholder('Search asset number, registration, make, model, or type')
            ->emptyStateHeading('No Fleet assets yet')
            ->emptyStateDescription('Register an operational asset after setting up the relevant Fleet asset type.')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()->requiresConfirmation(),
            ]);
    }
}
