<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Activities\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ActivitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->since()->sortable(),
                TextColumn::make('description')->searchable()->wrap(),
                TextColumn::make('event')
                    ->badge()
                    ->searchable()
                    ->formatStateUsing(fn (?string $state): string => str($state ?? 'activity')->replace('_', ' ')->title()->toString()),
                TextColumn::make('causer.full_name')->label('Actor')->searchable(['first_name', 'last_name']),
                TextColumn::make('subject_type')->label('Subject')->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : 'System'),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->options([
                        'administration' => 'Administration',
                        'auth' => 'Authentication',
                        'users' => 'Users',
                        'tenants' => 'Companies',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->searchPlaceholder('Search activity descriptions, events, and actors')
            ->emptyStateHeading('No activity recorded yet')
            ->emptyStateDescription('Operational and administration changes will appear here as your workspace becomes active.')
            ->recordActions([
                ViewAction::make(),
            ])
            ->paginated([10, 25, 50]);
    }
}
