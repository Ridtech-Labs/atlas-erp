<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\JobCards\Tables;

use App\Operations\Enums\JobCardApprovalStatus;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JobCardsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('card_number')->label('Atlas Record')->searchable()->sortable(),
                TextColumn::make('client_card_reference')->label('Client Card')->searchable()->toggleable(),
                TextColumn::make('job.job_number')->label('Job')->searchable(),
                TextColumn::make('card_date')->date()->sortable(),
                TextColumn::make('shift'),
                TextColumn::make('equipment_reference')->label('Equipment')->toggleable(),
                TextColumn::make('machine_number')->label('Machine No.')->toggleable(),
                TextColumn::make('operator_id')
                    ->label('Operators')
                    ->formatStateUsing(fn (mixed $state, $record): string => $record->operatorDisplayName() ?? 'Not assigned')
                    ->toggleable(),
                TextColumn::make('work_entries_count')->counts('workEntries')->label('Entries'),
                TextColumn::make('total_hours')->label('Hours'),
                TextColumn::make('approval_status')
                    ->badge()
                    ->formatStateUsing(fn (JobCardApprovalStatus $state): string => $state->label())
                    ->color(fn (JobCardApprovalStatus $state): string => $state->color()),
                TextColumn::make('verified_at')->since()->label('Accounts Reviewed')->toggleable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn ($record) => $record->approval_status !== JobCardApprovalStatus::BillingReady),
            ]);
    }
}
