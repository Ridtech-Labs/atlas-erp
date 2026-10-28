<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Warehouses;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\Warehouses\Pages\CreateWarehouse;
use App\Core\Administration\Filament\Resources\Warehouses\Pages\EditWarehouse;
use App\Core\Administration\Filament\Resources\Warehouses\Pages\ListWarehouses;
use App\Core\Administration\Filament\Resources\Warehouses\Pages\ViewWarehouse;
use App\Core\Administration\Filament\Resources\Warehouses\RelationManagers\StorageLocationsRelationManager;
use App\Inventory\Models\Warehouse;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static string|UnitEnum|null $navigationGroup = 'Operations';

    protected static ?string $navigationLabel = 'Warehouses';

    protected static ?int $navigationSort = 41;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Warehouse')->description('Maintain company storage sites. Creating a Warehouse automatically adds its Default storage location.')->schema([
                TextInput::make('code')->required()->maxLength(100)->placeholder('MAIN'),
                TextInput::make('name')->required()->maxLength(180)->placeholder('Main Warehouse'),
                Textarea::make('notes')->label('Address or notes')->rows(4)->columnSpanFull(),
                Toggle::make('is_active')->label('Active for future stock workflows')->default(true),
            ])->columns(2),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Warehouse')->schema([
                TextEntry::make('code'), TextEntry::make('name'),
                TextEntry::make('is_active')->label('Status')->badge()->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive')->color(fn (bool $state): string => $state ? 'success' : 'gray'),
                TextEntry::make('storage_locations_count')->label('Storage locations'),
                TextEntry::make('notes')->label('Address or notes')->placeholder('Not recorded')->columnSpanFull(),
                TextEntry::make('updated_at')->label('Last updated')->since(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('code')->searchable()->sortable(), TextColumn::make('name')->searchable()->sortable(),
            IconColumn::make('is_active')->label('Active')->boolean(), TextColumn::make('storage_locations_count')->label('Locations')->sortable(),
            TextColumn::make('updated_at')->since()->label('Updated')->sortable(),
        ])->filters([TernaryFilter::make('is_active')->label('Active status')])->defaultSort('name')->searchPlaceholder('Search Warehouses')->emptyStateHeading('No Warehouses yet')->emptyStateDescription('Create a company Warehouse before configuring its Storage Locations.');
    }

    public static function getPages(): array
    {
        return ['index' => ListWarehouses::route('/'), 'create' => CreateWarehouse::route('/create'), 'view' => ViewWarehouse::route('/{record}'), 'edit' => EditWarehouse::route('/{record}/edit')];
    }

    public static function getRelations(): array
    {
        return [StorageLocationsRelationManager::class];
    }

    public static function getEloquentQuery(): Builder
    {
        $companyId = ($user = auth()->user()) ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;

        return is_int($companyId) ? parent::getEloquentQuery()->withCount('storageLocations')->where('company_id', $companyId) : parent::getEloquentQuery()->whereRaw('1 = 0');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return static::getEloquentQuery();
    }
}
