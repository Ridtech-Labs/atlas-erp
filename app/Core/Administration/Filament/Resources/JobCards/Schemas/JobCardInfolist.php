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
            Section::make('Client Job Card')
                ->schema([
                    TextEntry::make('card_number')->label('Atlas record number'),
                    TextEntry::make('client_card_reference')->label('Client card reference')->placeholder('Not provided'),
                    TextEntry::make('job.job_number')->label('Job'),
                    TextEntry::make('card_date')->date(),
                    TextEntry::make('shift'),
                    TextEntry::make('equipment_reference')->label('Equipment'),
                    TextEntry::make('machine_number')->placeholder('Not captured'),
                    TextEntry::make('from_time')
                        ->label('First recorded start')
                        ->formatStateUsing(fn (mixed $state, $record): string => $record->displayStartTime() ?? 'Not captured'),
                    TextEntry::make('to_time')
                        ->label('Last recorded end')
                        ->formatStateUsing(fn (mixed $state, $record): string => $record->displayEndTime() ?? 'Not captured'),
                    TextEntry::make('operator_id')
                        ->label('Recorded operators')
                        ->formatStateUsing(fn (mixed $state, $record): string => $record->operatorDisplayName() ?? 'Not assigned'),
                    TextEntry::make('operated_by')->label('External operator name')->placeholder('No external operator recorded'),
                    TextEntry::make('supervising_officer_name')->label('Supervising officer')->placeholder('Not captured'),
                    TextEntry::make('total_hours')
                        ->label('Total recorded hours')
                        ->formatStateUsing(fn (mixed $state, $record): string => $record->displayTotalHours() !== null ? number_format((float) $record->displayTotalHours(), 2) : 'Not captured'),
                    TextEntry::make('client_endorsed')->badge()->formatStateUsing(fn (bool $state): string => $state ? 'Endorsed' : 'Not endorsed'),
                    TextEntry::make('client_stamped')->badge()->formatStateUsing(fn (bool $state): string => $state ? 'Stamped' : 'Not stamped'),
                    TextEntry::make('approval_status')
                        ->badge()
                        ->formatStateUsing(fn (JobCardApprovalStatus $state): string => $state->label())
                        ->color(fn (JobCardApprovalStatus $state): string => $state->color()),
                    TextEntry::make('verified_at')->label('Accounts reviewed')->since()->placeholder('Not yet reviewed'),
                    TextEntry::make('billing_ready_at')->since()->placeholder('Not yet billing ready'),
                    TextEntry::make('return_reason')->columnSpanFull()->placeholder('No return reason recorded'),
                    TextEntry::make('verification_notes')->label('Accounts review notes')->columnSpanFull()->placeholder('No Accounts review notes recorded'),
                    TextEntry::make('officer_remarks')->columnSpanFull()->placeholder('No officer remarks yet'),
                ])
                ->columns(2),
        ]);
    }
}
