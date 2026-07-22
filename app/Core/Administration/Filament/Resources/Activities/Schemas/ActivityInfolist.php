<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Activities\Schemas;

use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ActivityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Activity overview')
                    ->description('Read-only context for a recorded system or workspace event.')
                    ->schema([
                        TextEntry::make('log_name')->badge(),
                        TextEntry::make('event')->badge(),
                        TextEntry::make('description'),
                        TextEntry::make('causer.full_name')->label('Actor')->placeholder('System'),
                        TextEntry::make('subject_type')->label('Subject type'),
                        TextEntry::make('created_at')->since(),
                        KeyValueEntry::make('properties'),
                    ])
                    ->columns(2),
            ]);
    }
}
