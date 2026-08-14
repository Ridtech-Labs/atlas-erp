<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\JobCards\Schemas;

use App\Operations\Enums\JobCardApprovalStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JobCardInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Job card')
                ->schema([
                    TextEntry::make('card_number')->label('Job Card'),
                    TextEntry::make('job.job_number')->label('Job'),
                    TextEntry::make('card_date')->date(),
                    TextEntry::make('shift'),
                    TextEntry::make('equipment_reference')->label('Equipment'),
                    TextEntry::make('operator_id')
                        ->label('Operator')
                        ->formatStateUsing(fn (mixed $state, $record): string => $record->operatorDisplayName() ?? 'Not assigned'),
                    TextEntry::make('operated_by')->label('External operator name')->placeholder('No external operator recorded'),
                    TextEntry::make('supervising_officer_name')->label('Supervising officer')->placeholder('Not captured'),
                    TextEntry::make('approval_status')
                        ->badge()
                        ->formatStateUsing(fn (JobCardApprovalStatus $state): string => $state->label())
                        ->color(fn (JobCardApprovalStatus $state): string => $state->color()),
                    TextEntry::make('approved_at')->since()->placeholder('Not yet approved'),
                    TextEntry::make('return_reason')->columnSpanFull()->placeholder('No return reason recorded'),
                    TextEntry::make('officer_remarks')->columnSpanFull()->placeholder('No officer remarks yet'),
                ])
                ->columns(2),
        ]);
    }
}
