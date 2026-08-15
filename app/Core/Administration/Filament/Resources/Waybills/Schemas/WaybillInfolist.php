<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Waybills\Schemas;

use App\Operations\Enums\WaybillStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WaybillInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Waybill')
                ->schema([
                    TextEntry::make('waybill_number')->label('Waybill number')->placeholder('Not assigned'),
                    TextEntry::make('client_reference')->placeholder('No client reference'),
                    TextEntry::make('waybill_date')->date(),
                    TextEntry::make('driver_name'),
                    TextEntry::make('truck_number'),
                    TextEntry::make('number_of_trips'),
                    TextEntry::make('pickup_point')->placeholder('Not captured'),
                    TextEntry::make('destination')->placeholder('Not captured'),
                    TextEntry::make('amount_paid'),
                    TextEntry::make('amount_paid_to_driver'),
                    TextEntry::make('signature_name')->placeholder('No signature captured'),
                    TextEntry::make('status')
                        ->badge()
                        ->formatStateUsing(fn (WaybillStatus $state): string => $state->label())
                        ->color(fn (WaybillStatus $state): string => $state->color()),
                ])
                ->columns(2),
        ]);
    }
}
