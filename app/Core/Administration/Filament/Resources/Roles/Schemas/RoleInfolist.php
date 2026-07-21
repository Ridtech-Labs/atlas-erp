<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Roles\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Role')
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('permissions.name')->badge(),
                    ]),
            ]);
    }
}
