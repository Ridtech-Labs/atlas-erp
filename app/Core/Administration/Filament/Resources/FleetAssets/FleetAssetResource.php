<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\FleetAssets;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\FleetAssets\Pages\CreateFleetAsset;
use App\Core\Administration\Filament\Resources\FleetAssets\Pages\EditFleetAsset;
use App\Core\Administration\Filament\Resources\FleetAssets\Pages\ListFleetAssets;
use App\Core\Administration\Filament\Resources\FleetAssets\Pages\ViewFleetAsset;
use App\Core\Administration\Filament\Resources\FleetAssets\Schemas\FleetAssetForm;
use App\Core\Administration\Filament\Resources\FleetAssets\Schemas\FleetAssetInfolist;
use App\Core\Administration\Filament\Resources\FleetAssets\Tables\FleetAssetsTable;
use App\Fleet\Models\FleetAsset;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class FleetAssetResource extends Resource
{
    protected static ?string $model = FleetAsset::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static string|UnitEnum|null $navigationGroup = 'Operations';

    protected static ?string $navigationLabel = 'Fleet Assets';

    protected static ?int $navigationSort = 30;

    protected static ?string $recordTitleAttribute = 'asset_number';

    public static function form(Schema $schema): Schema
    {
        return FleetAssetForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FleetAssetInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FleetAssetsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFleetAssets::route('/'),
            'create' => CreateFleetAsset::route('/create'),
            'view' => ViewFleetAsset::route('/{record}'),
            'edit' => EditFleetAsset::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $companyId = ($user = auth()->user()) ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;

        return is_int($companyId)
            ? parent::getEloquentQuery()->with('type')->where('company_id', $companyId)
            : parent::getEloquentQuery()->whereRaw('1 = 0');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return static::getEloquentQuery();
    }
}
