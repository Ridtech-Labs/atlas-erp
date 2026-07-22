<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Roles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('permissions_count')->counts('permissions')->label('Permissions')->sortable(),
                TextColumn::make('updated_at')->since()->label('Updated')->sortable(),
            ])
            ->defaultSort('name')
            ->searchPlaceholder('Search roles by name')
            ->emptyStateHeading('No roles yet')
            ->emptyStateDescription('Roles help you package permissions into safe, repeatable access levels.')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->requiresConfirmation(),
                ]),
            ])
            ->paginated([10, 25, 50]);
    }
}
