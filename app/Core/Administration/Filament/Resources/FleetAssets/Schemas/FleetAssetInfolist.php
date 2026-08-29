<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\FleetAssets\Schemas;

use App\Fleet\Enums\FleetAssetOperationalStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FleetAssetInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Fleet asset')
                ->schema([
                    TextEntry::make('asset_number')->label('Asset number')->weight('bold'),
                    TextEntry::make('type.name')->label('Asset type'),
                    TextEntry::make('type.category')->label('Category')->badge()->formatStateUsing(fn ($state): string => $state->label()),
                    TextEntry::make('operational_status')->label('Operational status')->badge()->formatStateUsing(fn (FleetAssetOperationalStatus $state): string => $state->label())->color(fn (FleetAssetOperationalStatus $state): string => $state->color()),
                    TextEntry::make('registration_number')->placeholder('Not registered'),
                    TextEntry::make('make')->placeholder('Not captured'),
                    TextEntry::make('model')->placeholder('Not captured'),
                    TextEntry::make('serial_number')->placeholder('Not captured'),
                    TextEntry::make('notes')->columnSpanFull()->placeholder('No operational notes recorded'),
                    TextEntry::make('updated_at')->since()->label('Last updated'),
                ])
                ->columns(2),
        ]);
    }
}
