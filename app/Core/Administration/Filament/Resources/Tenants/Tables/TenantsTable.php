<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Tenants\Tables;

use App\Core\Administration\Filament\Pages\Dashboard;
use App\Core\Shared\Enums\TenantStatus;
use App\Core\Tenancy\Models\Tenant;
use App\Core\Tenancy\Services\SupportTenantWorkspaceService;
use App\Models\User;
use Filament\Actions\Action;
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
                TextColumn::make('defaultCompany.email')->label('Company Email')->placeholder('No email')->toggleable(),
                TextColumn::make('defaultCompany.country')->label('Country')->placeholder('No country'),
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
            ->searchPlaceholder('Search tenants by name or slug')
            ->emptyStateHeading('No tenants yet')
            ->emptyStateDescription('Add a tenant account to onboard a customer workspace into Atlas ERP.')
            ->recordActions([
                Action::make('enterWorkspace')
                    ->label('Enter Workspace')
                    ->icon('heroicon-o-arrow-right-start-on-rectangle')
                    ->visible(fn (Tenant $record): bool => self::canEnterWorkspace($record))
                    ->action(function (Tenant $record): mixed {
                        $actor = self::authenticatedUser();

                        app(SupportTenantWorkspaceService::class)->enter($actor, $record);

                        return redirect(Dashboard::getUrl());
                    }),
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

    private static function canEnterWorkspace(Tenant $record): bool
    {
        $actor = auth()->user();

        return $actor instanceof User
            && app(SupportTenantWorkspaceService::class)->canEnter($actor, $record);
    }

    private static function authenticatedUser(): User
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            abort(403);
        }

        return $user;
    }
}
