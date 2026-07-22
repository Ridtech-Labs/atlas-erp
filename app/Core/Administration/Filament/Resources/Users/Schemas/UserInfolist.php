<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Users\Schemas;

use App\Core\Shared\Enums\UserStatus;
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
                    ->description('Who this user is, which workspace they belong to, and how they are permitted to operate.')
                    ->schema([
                        TextEntry::make('full_name'),
                        TextEntry::make('tenant.name')->label('Company'),
                        TextEntry::make('email'),
                        TextEntry::make('phone')->placeholder('No phone recorded'),
                        ImageEntry::make('avatar_path')->disk('public'),
                        TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn (UserStatus $state): string => $state->label())
                            ->color(fn (UserStatus $state): string => $state->color()),
                        TextEntry::make('last_login_at')->since()->placeholder('No login recorded'),
                        TextEntry::make('last_login_ip')->placeholder('No IP recorded'),
                        TextEntry::make('roles.name')->badge(),
                    ])
                    ->columns(2),
            ]);
    }
}
