<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\RateAgreements\Tables;

use App\Finance\Enums\RateAgreementStatus;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RateAgreementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record): string => $record->reference ?: 'No reference'),
                TextColumn::make('client.display_name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (RateAgreementStatus $state): string => $state->label()),
                TextColumn::make('effective_from')->date()->sortable(),
                TextColumn::make('effective_to')->date()->placeholder('Open ended'),
                TextColumn::make('lines_count')->label('Lines')->sortable(),
                TextColumn::make('updated_at')->since()->label('Updated')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(RateAgreementStatus::cases())->mapWithKeys(fn (RateAgreementStatus $status) => [$status->value => $status->label()])->all()),
            ])
            ->defaultSort('effective_from', 'desc')
            ->searchPlaceholder('Search rate agreements by client, reference, or agreement name')
            ->emptyStateHeading('No rate agreements yet')
            ->emptyStateDescription('Create a client rate agreement before Finance prepares billing batches.')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()->requiresConfirmation(),
            ]);
    }
}
