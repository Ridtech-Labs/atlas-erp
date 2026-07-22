<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Tenants;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\Tenants\Pages\CreateTenant;
use App\Core\Administration\Filament\Resources\Tenants\Pages\EditTenant;
use App\Core\Administration\Filament\Resources\Tenants\Pages\ListTenants;
use App\Core\Administration\Filament\Resources\Tenants\Pages\ViewTenant;
use App\Core\Administration\Filament\Resources\Tenants\Schemas\TenantForm;
use App\Core\Administration\Filament\Resources\Tenants\Schemas\TenantInfolist;
use App\Core\Administration\Filament\Resources\Tenants\Tables\TenantsTable;
use App\Core\Tenancy\Models\Tenant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static string|UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Companies';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TenantForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TenantInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TenantsTable::configure($table);
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
            'index' => ListTenants::route('/'),
            'create' => CreateTenant::route('/create'),
            'view' => ViewTenant::route('/{record}'),
            'edit' => EditTenant::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withCount(['users', 'clients', 'jobs'])
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

        return $query->whereKey($user->tenant_id);
    }

    public static function getModelLabel(): string
    {
        return 'Company';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Companies';
    }
}
