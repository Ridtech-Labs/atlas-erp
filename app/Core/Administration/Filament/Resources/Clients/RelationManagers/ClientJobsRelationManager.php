<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Clients\RelationManagers;

use App\Operations\Enums\JobPriority;
use App\Operations\Enums\JobStatus;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientJobsRelationManager extends RelationManager
{
    protected static string $relationship = 'jobs';

    protected static ?string $title = 'Jobs';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('job_number')->searchable()->label('Job no.'),
                TextColumn::make('title')->searchable()->wrap(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (JobStatus $state): string => $state->label())
                    ->color(fn (JobStatus $state): string => $state->color()),
                TextColumn::make('priority')
                    ->badge()
                    ->formatStateUsing(fn (JobPriority $state): string => $state->label())
                    ->color(fn (JobPriority $state): string => $state->color()),
                TextColumn::make('planned_start_date')->date()->label('Planned start'),
            ])
            ->searchPlaceholder('Search client jobs by number or title')
            ->emptyStateHeading('No jobs for this client')
            ->emptyStateDescription('Jobs linked to this customer account will appear here once work is created.')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->paginated([5, 10, 25]);
    }
}
