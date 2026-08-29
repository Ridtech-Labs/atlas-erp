<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\RateAgreements\Schemas;

use App\Finance\Enums\RateAgreementStatus;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RateAgreementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Agreement overview')
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('reference')->placeholder('No reference'),
                    TextEntry::make('client.display_name')->label('Client'),
                    TextEntry::make('status')
                        ->badge()
                        ->formatStateUsing(fn (RateAgreementStatus $state): string => $state->label()),
                    TextEntry::make('effective_from')->date()->placeholder('Open start'),
                    TextEntry::make('effective_to')->date()->placeholder('Open ended'),
                    TextEntry::make('lines_count')->label('Rate lines'),
                    TextEntry::make('updated_at')->since()->label('Updated'),
                    TextEntry::make('notes')->columnSpanFull()->placeholder('No notes recorded'),
                ])
                ->columns(2),
            Section::make('Resolved lines')
                ->schema([
                    RepeatableEntry::make('lines')
                        ->label('')
                        ->schema([
                            TextEntry::make('equipment_reference')->label('Equipment class / type')->placeholder('No class recorded'),
                            TextEntry::make('machine_number')->placeholder('No machine-specific override'),
                            TextEntry::make('billing_unit'),
                            TextEntry::make('currency'),
                            TextEntry::make('rate')->numeric(decimalPlaces: 2),
                            TextEntry::make('effective_from')
                                ->label('Line effective from')
                                ->state(fn ($record) => $record?->effectiveFromOrAgreement())
                                ->date()
                                ->placeholder('Agreement start'),
                            TextEntry::make('effective_to')
                                ->label('Line effective to')
                                ->state(fn ($record) => $record?->effectiveToOrAgreement())
                                ->date()
                                ->placeholder('Agreement expiry'),
                        ])
                        ->columns(3),
                ]),
        ]);
    }
}
