<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Jobs\Schemas;

use App\Operations\Enums\JobPriority;
use App\Operations\Enums\JobStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JobInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Job workspace')
                ->description('A concise operational view of timing, ownership, status, and commercial references.')
                ->schema([
                    TextEntry::make('job_number'),
                    TextEntry::make('client.legal_name')->label('Client'),
                    TextEntry::make('site.name')->label('Site')->placeholder('No site linked'),
                    TextEntry::make('title'),
                    TextEntry::make('status')
                        ->badge()
                        ->formatStateUsing(fn (JobStatus $state): string => $state->label())
                        ->color(fn (JobStatus $state): string => $state->color()),
                    TextEntry::make('priority')
                        ->badge()
                        ->formatStateUsing(fn (JobPriority $state): string => $state->label())
                        ->color(fn (JobPriority $state): string => $state->color()),
                    TextEntry::make('requested_start_date')->date(),
                    TextEntry::make('planned_start_date')->date(),
                    TextEntry::make('planned_end_date')->date(),
                    TextEntry::make('estimated_value')->money('GHS')->placeholder('Not estimated'),
                    TextEntry::make('approved_at')->since()->placeholder('Awaiting approval'),
                    TextEntry::make('completed_at')->since()->placeholder('Still open'),
                ])->columns(2),
        ]);
    }
}
