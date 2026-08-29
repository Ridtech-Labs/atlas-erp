<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\FleetAssetTypes\Schemas;

use App\Fleet\Enums\FleetAssetCategory;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FleetAssetTypeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Asset type')
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('category')->badge()->formatStateUsing(fn (FleetAssetCategory $state): string => $state->label()),
                    TextEntry::make('is_active')
                        ->label('Available for new assets')
                        ->badge()
                        ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive')
                        ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
                    TextEntry::make('assets_count')->label('Fleet assets'),
                    TextEntry::make('updated_at')->since()->label('Last updated'),
                ])
                ->columns(2),
        ]);
    }
}
