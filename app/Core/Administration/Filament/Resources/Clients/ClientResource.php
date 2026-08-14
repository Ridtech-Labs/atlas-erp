<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Clients;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\Clients\Pages\CreateClient;
use App\Core\Administration\Filament\Resources\Clients\Pages\EditClient;
use App\Core\Administration\Filament\Resources\Clients\Pages\ListClients;
use App\Core\Administration\Filament\Resources\Clients\Pages\ViewClient;
use App\Core\Administration\Filament\Resources\Clients\RelationManagers\ClientContactsRelationManager;
use App\Core\Administration\Filament\Resources\Clients\RelationManagers\ClientJobsRelationManager;
use App\Core\Administration\Filament\Resources\Clients\RelationManagers\ClientSitesRelationManager;
use App\Core\Administration\Filament\Resources\Clients\Schemas\ClientForm;
use App\Core\Administration\Filament\Resources\Clients\Schemas\ClientInfolist;
use App\Core\Administration\Filament\Resources\Clients\Tables\ClientsTable;
use App\CRM\Models\Client;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'CRM';

    protected static ?string $navigationLabel = 'Clients';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'legal_name';

    public static function form(Schema $schema): Schema
    {
        return ClientForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ClientInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClientsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ClientContactsRelationManager::class,
            ClientSitesRelationManager::class,
            ClientJobsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClients::route('/'),
            'create' => CreateClient::route('/create'),
            'view' => ViewClient::route('/{record}'),
            'edit' => EditClient::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['tenant'])
            ->withCount(['contacts', 'sites', 'jobs'])
            ->withoutGlobalScopes([SoftDeletingScope::class]);

        $user = auth()->user();
        $access = app(AdministrationAccessService::class);

        if ($user === null) {
            return $query->whereRaw('1 = 0');
        }

        if ($access->isSuperAdministrator($user)) {
            $companyId = $access->activeCompanyId($user);

            return $companyId === null
                ? $query->whereRaw('1 = 0')
                : $query->where('company_id', $companyId);
        }

        $companyId = $access->activeCompanyId($user);

        return $companyId === null
            ? $query->whereRaw('1 = 0')
            : $query->where('company_id', $companyId);
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->with(['tenant', 'contacts', 'sites', 'jobs'])
            ->withCount(['contacts', 'sites', 'jobs'])
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
