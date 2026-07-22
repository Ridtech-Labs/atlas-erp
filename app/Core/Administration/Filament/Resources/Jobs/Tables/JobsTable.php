<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Jobs\Tables;

use App\Operations\Enums\JobPriority;
use App\Operations\Enums\JobStatus;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

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
                TextColumn::make('estimated_value')->money('GHS')->label('Value')->sortable()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(JobStatus::cases())->mapWithKeys(fn (JobStatus $status) => [$status->value => $status->label()])->all()),
                SelectFilter::make('priority')
                    ->options(collect(JobPriority::cases())->mapWithKeys(fn (JobPriority $priority) => [$priority->value => $priority->label()])->all()),
            ])
            ->defaultSort('planned_start_date', 'desc')
            ->searchPlaceholder('Search jobs by number, title, client, or site')
            ->emptyStateHeading('No jobs yet')
            ->emptyStateDescription('Create the first job to begin scheduling work, approvals, and delivery tracking.')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->paginated([10, 25, 50]);
    }
}
