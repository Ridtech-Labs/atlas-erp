<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Roles\Schemas;

use App\Administration\Enums\PermissionName;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Role')
                    ->description('Control the permission bundle granted to operational and platform users.')
                    ->schema([
                        TextInput::make('name')->required()->unique(ignoreRecord: true)->maxLength(255)->placeholder('Operations Supervisor'),
                        CheckboxList::make('permissions')
                            ->label('Permissions')
                            ->options(collect(PermissionName::values())->mapWithKeys(fn (string $permission) => [$permission => $permission])->all())
                            ->searchable()
                            ->columns(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }
}
