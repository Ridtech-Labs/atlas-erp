<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Clients\Tables;

use App\CRM\Enums\ClientStatus;
use App\CRM\Enums\ClientType;
use App\CRM\Models\Client;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('legal_name')
                    ->label('Client')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Client $record): string => $record->client_code ?: 'Code pending'),
                TextColumn::make('trading_name')
                    ->label('Trading name')
                    ->searchable()
                    ->placeholder('Matches legal name'),
                TextColumn::make('client_type')
                    ->badge()
                    ->formatStateUsing(fn (ClientType $state): string => $state->label())
                    ->color(fn (ClientType $state): string => $state->color()),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (ClientStatus $state): string => $state->label())
                    ->color(fn (ClientStatus $state): string => $state->color()),
                TextColumn::make('email')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('jobs_count')
                    ->label('Jobs')
                    ->sortable(),
                TextColumn::make('contacts_count')
                    ->label('Contacts')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('country')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(collect(ClientStatus::cases())->mapWithKeys(fn (ClientStatus $status) => [$status->value => $status->label()])->all()),
                SelectFilter::make('client_type')
                    ->label('Client type')
                    ->options(collect(ClientType::cases())->mapWithKeys(fn (ClientType $type) => [$type->value => $type->label()])->all()),
                TrashedFilter::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->searchPlaceholder('Search clients, codes, contacts, or account emails')
            ->emptyStateHeading('No clients yet')
            ->emptyStateDescription('Create your first customer account to start managing sites, contacts, and jobs.')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->requiresConfirmation(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->paginated([10, 25, 50]);
    }
}
