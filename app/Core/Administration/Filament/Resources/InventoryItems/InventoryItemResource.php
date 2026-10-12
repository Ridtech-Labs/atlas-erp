<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\InventoryItems;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\InventoryItems\Pages\CreateInventoryItem;
use App\Core\Administration\Filament\Resources\InventoryItems\Pages\EditInventoryItem;
use App\Core\Administration\Filament\Resources\InventoryItems\Pages\ListInventoryItems;
use App\Core\Administration\Filament\Resources\InventoryItems\Pages\ViewInventoryItem;
use App\Core\Administration\Filament\Resources\InventoryItems\Schemas\InventoryItemForm;
use App\Core\Administration\Filament\Resources\InventoryItems\Schemas\InventoryItemInfolist;
use App\Core\Administration\Filament\Resources\InventoryItems\Tables\InventoryItemsTable;
use App\Inventory\Models\InventoryItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class InventoryItemResource extends Resource
{
    protected static ?string $model = InventoryItem::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static string|UnitEnum|null $navigationGroup = 'Operations';

    protected static ?string $navigationLabel = 'Inventory';

    protected static ?int $navigationSort = 40;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return InventoryItemForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InventoryItemInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventoryItemsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInventoryItems::route('/'),
            'create' => CreateInventoryItem::route('/create'),
            'view' => ViewInventoryItem::route('/{record}'),
            'edit' => EditInventoryItem::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $companyId = ($user = auth()->user()) ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;

        return is_int($companyId)
            ? parent::getEloquentQuery()->where('company_id', $companyId)
            : parent::getEloquentQuery()->whereRaw('1 = 0');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return static::getEloquentQuery();
    }
}
