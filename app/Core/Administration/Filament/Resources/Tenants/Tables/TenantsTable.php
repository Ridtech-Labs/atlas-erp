<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Tenants\Tables;

use App\Core\Shared\Enums\TenantStatus;
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

class TenantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('slug')->searchable()->toggleable(),
                TextColumn::make('email')->searchable()->placeholder('No email')->toggleable(),
                TextColumn::make('country')->searchable()->placeholder('No country'),
                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn (TenantStatus $state): string => $state->label())
                    ->color(fn (TenantStatus $state): string => $state->color()),
                TextColumn::make('users_count')->counts('users')->label('Users'),
                TextColumn::make('clients_count')->label('Clients')->sortable(),
                TextColumn::make('jobs_count')->label('Jobs')->sortable()->toggleable(),
                TextColumn::make('updated_at')->since()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(TenantStatus::cases())->mapWithKeys(fn (TenantStatus $status) => [$status->value => $status->label()])->all()),
                TrashedFilter::make(),
            ])
            ->defaultSort('name')
            ->searchPlaceholder('Search companies by name, slug, country, or email')
            ->emptyStateHeading('No companies yet')
            ->emptyStateDescription('Add a company workspace to onboard teams, clients, and jobs.')
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
