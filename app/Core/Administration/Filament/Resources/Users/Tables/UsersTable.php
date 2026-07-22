<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Users\Tables;

use App\Core\Shared\Enums\UserStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')->searchable(['first_name', 'last_name'])->sortable(),
                TextColumn::make('tenant.name')->label('Company')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (UserStatus $state): string => $state->label())
                    ->color(fn (UserStatus $state): string => $state->color()),
                TextColumn::make('roles.name')->badge()->separator(', '),
                TextColumn::make('last_login_at')->since()->label('Last login'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(UserStatus::cases())->mapWithKeys(fn (UserStatus $status) => [$status->value => $status->label()])->all()),
                TrashedFilter::make(),
            ])
            ->defaultSort('first_name')
            ->searchPlaceholder('Search users by name, email, or company')
            ->emptyStateHeading('No users yet')
            ->emptyStateDescription('Invite your first team member to start collaborating inside this workspace.')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->requiresConfirmation(),
                    ForceDeleteBulkAction::make()->requiresConfirmation(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->paginated([10, 25, 50]);
    }
}
