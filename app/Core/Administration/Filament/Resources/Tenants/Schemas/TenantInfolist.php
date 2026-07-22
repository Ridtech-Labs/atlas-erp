<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Tenants\Schemas;

use App\Core\Shared\Enums\TenantStatus;
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
                    ->description('Workspace identity, regional defaults, and company-facing contact details.')
                    ->schema([
                        TextEntry::make('name')->label('Company'),
                        TextEntry::make('slug')->placeholder('No slug generated'),
                        TextEntry::make('email')->placeholder('No email recorded'),
                        TextEntry::make('phone')->placeholder('No phone recorded'),
                        ImageEntry::make('logo_path')->disk('public'),
                        TextEntry::make('address')->columnSpanFull()->placeholder('No registered address'),
                        TextEntry::make('city')->placeholder('No city set'),
                        TextEntry::make('country')->placeholder('No country set'),
                        TextEntry::make('timezone'),
                        TextEntry::make('currency'),
                        TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn (TenantStatus $state): string => $state->label())
                            ->color(fn (TenantStatus $state): string => $state->color()),
                    ])
                    ->columns(2),
            ]);
    }
}
