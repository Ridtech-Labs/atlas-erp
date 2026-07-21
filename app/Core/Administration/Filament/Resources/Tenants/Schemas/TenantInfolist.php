<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Tenants\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TenantInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Company profile')
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('slug'),
                        TextEntry::make('email'),
                        TextEntry::make('phone'),
                        ImageEntry::make('logo_path')->disk('public'),
                        TextEntry::make('address')->columnSpanFull(),
                        TextEntry::make('city'),
                        TextEntry::make('country'),
                        TextEntry::make('timezone'),
                        TextEntry::make('currency'),
                        TextEntry::make('status')->badge(),
                    ])
                    ->columns(2),
            ]);
    }
}
