<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Users;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\Users\Pages\CreateUser;
use App\Core\Administration\Filament\Resources\Users\Pages\EditUser;
use App\Core\Administration\Filament\Resources\Users\Pages\ListUsers;
use App\Core\Administration\Filament\Resources\Users\Pages\ViewUser;
use App\Core\Administration\Filament\Resources\Users\Schemas\UserForm;
use App\Core\Administration\Filament\Resources\Users\Schemas\UserInfolist;
use App\Core\Administration\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Users';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'first_name';

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return static::getEloquentQuery();
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['tenant', 'roles', 'companies'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        $user = auth()->user();
        $access = app(AdministrationAccessService::class);

        if ($user === null) {
            return $query->whereRaw('1 = 0');
        }

        if ($access->isSuperAdministrator($user)) {
            return $query;
        }

        $activeCompanyId = $access->activeCompanyId($user);

        if (! is_int($activeCompanyId)) {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->where('tenant_id', $user->tenant_id)
            ->whereHas('companies', fn (Builder $companyQuery) => $companyQuery->whereKey($activeCompanyId));
    }
}
