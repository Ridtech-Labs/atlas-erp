<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\FleetAssetTypes\Tables;

use App\Fleet\Enums\FleetAssetCategory;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class FleetAssetTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('category')->badge()->formatStateUsing(fn (FleetAssetCategory $state): string => $state->label()),
                IconColumn::make('is_active')->label('Active')->boolean(),
                TextColumn::make('assets_count')->label('Assets')->sortable(),
                TextColumn::make('updated_at')->since()->label('Updated')->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')->options(collect(FleetAssetCategory::cases())->mapWithKeys(fn (FleetAssetCategory $category): array => [$category->value => $category->label()])->all()),
                TernaryFilter::make('is_active')->label('Active status'),
            ])
            ->defaultSort('name')
            ->searchPlaceholder('Search Fleet asset types')
            ->emptyStateHeading('No Fleet asset types yet')
            ->emptyStateDescription('Create controlled asset types before registering Fleet machinery or transport assets.')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()->requiresConfirmation(),
            ]);
    }
}
