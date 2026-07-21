<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User profile')
                    ->schema([
                        TextEntry::make('full_name'),
                        TextEntry::make('tenant.name')->label('Company'),
                        TextEntry::make('email'),
                        TextEntry::make('phone'),
                        ImageEntry::make('avatar_path')->disk('public'),
                        TextEntry::make('status')->badge(),
                        TextEntry::make('last_login_at')->since(),
                        TextEntry::make('last_login_ip'),
                        TextEntry::make('roles.name')->badge(),
                    ])
                    ->columns(2),
            ]);
    }
}
