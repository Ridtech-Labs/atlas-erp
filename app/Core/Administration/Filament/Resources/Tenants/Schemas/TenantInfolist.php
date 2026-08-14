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
                Section::make('Tenant account')
                    ->description('Atlas account identity and platform status for this subscribed tenant.')
                    ->schema([
                        TextEntry::make('name')->label('Tenant'),
                        TextEntry::make('slug')->placeholder('No slug generated'),
                        TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn (TenantStatus $state): string => $state->label())
                            ->color(fn (TenantStatus $state): string => $state->color()),
                    ])
                    ->columns(2),
                Section::make('Default company profile')
                    ->description('Operational business information stored on the tenant’s default company record.')
                    ->schema([
                        TextEntry::make('defaultCompany.email')->placeholder('No email recorded'),
                        TextEntry::make('defaultCompany.phone')->placeholder('No phone recorded'),
                        ImageEntry::make('defaultCompany.logo_path')->disk('public'),
                        TextEntry::make('defaultCompany.address')->columnSpanFull()->placeholder('No registered address'),
                        TextEntry::make('defaultCompany.city')->placeholder('No city set'),
                        TextEntry::make('defaultCompany.country')->placeholder('No country set'),
                        TextEntry::make('defaultCompany.timezone')->placeholder('No timezone set'),
                        TextEntry::make('defaultCompany.currency')->placeholder('No currency set'),
                    ])
                    ->columns(2),
            ]);
    }
}
