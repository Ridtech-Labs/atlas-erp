<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\FleetAssetTypes;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\FleetAssetTypes\Pages\CreateFleetAssetType;
use App\Core\Administration\Filament\Resources\FleetAssetTypes\Pages\EditFleetAssetType;
use App\Core\Administration\Filament\Resources\FleetAssetTypes\Pages\ListFleetAssetTypes;
use App\Core\Administration\Filament\Resources\FleetAssetTypes\Pages\ViewFleetAssetType;
use App\Core\Administration\Filament\Resources\FleetAssetTypes\Schemas\FleetAssetTypeForm;
use App\Core\Administration\Filament\Resources\FleetAssetTypes\Schemas\FleetAssetTypeInfolist;
use App\Core\Administration\Filament\Resources\FleetAssetTypes\Tables\FleetAssetTypesTable;
use App\Fleet\Models\FleetAssetType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class FleetAssetTypeResource extends Resource
{
    protected static ?string $model = FleetAssetType::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'Operations';

    protected static ?string $navigationLabel = 'Asset Types';

    protected static ?int $navigationSort = 31;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return FleetAssetTypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FleetAssetTypeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FleetAssetTypesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFleetAssetTypes::route('/'),
            'create' => CreateFleetAssetType::route('/create'),
            'view' => ViewFleetAssetType::route('/{record}'),
            'edit' => EditFleetAssetType::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $companyId = ($user = auth()->user()) ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;

        return is_int($companyId)
            ? parent::getEloquentQuery()->withCount('assets')->where('company_id', $companyId)
            : parent::getEloquentQuery()->whereRaw('1 = 0');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return static::getEloquentQuery();
    }
}
