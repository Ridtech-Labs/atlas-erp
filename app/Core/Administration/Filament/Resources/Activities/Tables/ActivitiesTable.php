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
                TextColumn::make('event')->badge()->searchable(),
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
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
