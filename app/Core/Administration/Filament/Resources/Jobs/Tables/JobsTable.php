<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Jobs\Tables;

use App\Core\Administration\Filament\Resources\Jobs\JobResource;
use App\Operations\Enums\JobPriority;
use App\Operations\Enums\JobStatus;
use App\Operations\Enums\JobType;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class JobsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('job_number')->searchable()->sortable(),
                TextColumn::make('title')->searchable()->wrap(),
                TextColumn::make('client.legal_name')->label('Client')->searchable()->toggleable(),
                TextColumn::make('site.name')->label('Site')->placeholder('No site linked')->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (JobStatus $state): string => $state->label())
                    ->color(fn (JobStatus $state): string => $state->color()),
                TextColumn::make('priority')
                    ->badge()
                    ->formatStateUsing(fn (JobPriority $state): string => $state->label())
                    ->color(fn (JobPriority $state): string => $state->color()),
                TextColumn::make('planned_start_date')->date()->label('Start')->sortable(),
                TextColumn::make('planned_end_date')->date()->label('End')->sortable()->toggleable(),
                TextColumn::make('estimated_value')
                    ->money(fn ($record) => $record->currency ?: 'USD')
                    ->label('Value')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('client_id')->label('Client')->relationship('client', 'legal_name')->searchable(),
                SelectFilter::make('job_type')->label('Operational type')->options(collect(JobType::cases())->mapWithKeys(fn (JobType $type): array => [$type->value => $type->label()])->all()),
                SelectFilter::make('status')
                    ->options(collect(JobStatus::cases())->mapWithKeys(fn (JobStatus $status) => [$status->value => $status->label()])->all()),
                SelectFilter::make('priority')
                    ->options(collect(JobPriority::cases())->mapWithKeys(fn (JobPriority $priority) => [$priority->value => $priority->label()])->all()),
                Filter::make('planned_window')->schema([DatePicker::make('from')->label('Planned from'), DatePicker::make('until')->label('Planned until')])->query(function (Builder $query, array $data): Builder {
                    return $query->when($data['from'] ?? null, fn (Builder $query, string $date): Builder => $query->whereDate('planned_start_date', '>=', $date))->when($data['until'] ?? null, fn (Builder $query, string $date): Builder => $query->whereDate('planned_start_date', '<=', $date));
                }),
            ])
            ->defaultSort('planned_start_date', 'desc')
            ->searchPlaceholder('Search jobs by number, title, client, or site')
            ->emptyStateHeading('No jobs yet')
            ->emptyStateDescription('Create the first job to begin scheduling work, approvals, and delivery tracking.')
            ->recordActions([
                ViewAction::make()->label('View Job'),
            ])
            ->recordUrl(fn ($record): string => JobResource::getUrl('view', ['record' => $record]))
            ->paginated([10, 25, 50]);
    }
}
