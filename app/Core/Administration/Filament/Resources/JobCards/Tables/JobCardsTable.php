<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\JobCards\Tables;

use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Models\JobCard;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
            ->filters([
                SelectFilter::make('client_id')->label('Client')->relationship('client', 'legal_name')->searchable(),
                Filter::make('equipment_reference')->schema([Select::make('equipment_reference')->options(fn (): array => JobCard::query()->whereNotNull('equipment_reference')->distinct()->orderBy('equipment_reference')->pluck('equipment_reference', 'equipment_reference')->all())])->query(fn (Builder $query, array $data): Builder => $query->when($data['equipment_reference'] ?? null, fn (Builder $query, string $value): Builder => $query->where('equipment_reference', $value))),
                Filter::make('machine_number')->schema([Select::make('machine_number')->options(fn (): array => JobCard::query()->whereNotNull('machine_number')->distinct()->orderBy('machine_number')->pluck('machine_number', 'machine_number')->all())])->query(fn (Builder $query, array $data): Builder => $query->when($data['machine_number'] ?? null, fn (Builder $query, string $value): Builder => $query->where('machine_number', $value))),
                Filter::make('card_window')->schema([DatePicker::make('from')->label('From'), DatePicker::make('until')->label('Until')])->query(fn (Builder $query, array $data): Builder => $query->when($data['from'] ?? null, fn (Builder $query, string $date): Builder => $query->whereDate('card_date', '>=', $date))->when($data['until'] ?? null, fn (Builder $query, string $date): Builder => $query->whereDate('card_date', '<=', $date))),
                SelectFilter::make('approval_status')->label('Evidence status')->options(collect(JobCardApprovalStatus::cases())->mapWithKeys(fn (JobCardApprovalStatus $status): array => [$status->value => $status->label()])->all()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn ($record) => $record->approval_status !== JobCardApprovalStatus::BillingReady),
            ]);
    }
}
