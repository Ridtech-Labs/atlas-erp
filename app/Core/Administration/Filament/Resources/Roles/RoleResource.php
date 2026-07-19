<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Roles;

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Resources\Roles\Pages\CreateRole;
use App\Core\Administration\Filament\Resources\Roles\Pages\EditRole;
use App\Core\Administration\Filament\Resources\Roles\Pages\ListRoles;
use App\Core\Administration\Filament\Resources\Roles\Pages\ViewRole;
use App\Core\Administration\Filament\Resources\Roles\Schemas\RoleForm;
use App\Core\Administration\Filament\Resources\Roles\Schemas\RoleInfolist;
use App\Core\Administration\Filament\Resources\Roles\Tables\RolesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;
use UnitEnum;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RoleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'view' => ViewRole::route('/{record}'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (! auth()->user()?->hasRole(RoleName::SuperAdministrator->value)) {
            return $query->where('name', '!=', RoleName::SuperAdministrator->value);
        }

        return $query;
    }
}
