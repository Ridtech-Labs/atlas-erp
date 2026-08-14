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
                TextColumn::make('card_number')->label('Card')->searchable()->sortable(),
                TextColumn::make('job.job_number')->label('Job')->searchable(),
                TextColumn::make('card_date')->date()->sortable(),
                TextColumn::make('shift'),
                TextColumn::make('equipment_reference')->label('Equipment')->toggleable(),
                TextColumn::make('operator_id')
                    ->label('Operator')
                    ->formatStateUsing(fn (mixed $state, $record): string => $record->operatorDisplayName() ?? 'Not assigned')
                    ->toggleable(),
                TextColumn::make('work_entries_count')->counts('workEntries')->label('Entries'),
                TextColumn::make('work_entries_sum_total_hours')->sum('workEntries', 'total_hours')->label('Hours'),
                TextColumn::make('approval_status')
                    ->badge()
                    ->formatStateUsing(fn (JobCardApprovalStatus $state): string => $state->label())
                    ->color(fn (JobCardApprovalStatus $state): string => $state->color()),
                TextColumn::make('approved_at')->since()->label('Approved')->toggleable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn ($record) => $record->approval_status !== JobCardApprovalStatus::Approved),
            ]);
    }
}
