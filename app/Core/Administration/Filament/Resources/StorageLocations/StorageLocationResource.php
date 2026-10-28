<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\StorageLocations;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\StorageLocations\Pages\CreateStorageLocation;
use App\Core\Administration\Filament\Resources\StorageLocations\Pages\EditStorageLocation;
use App\Core\Administration\Filament\Resources\StorageLocations\Pages\ListStorageLocations;
use App\Core\Administration\Filament\Resources\StorageLocations\Pages\ViewStorageLocation;
use App\Inventory\Models\StorageLocation;
use App\Inventory\Models\Warehouse;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class StorageLocationResource extends Resource
{
    protected static ?string $model = StorageLocation::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|UnitEnum|null $navigationGroup = 'Operations';

    protected static ?string $navigationLabel = 'Storage Locations';

    protected static ?int $navigationSort = 42;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Storage Location')->description('Maintain active storage points inside an active company Warehouse. Default locations are created automatically with their Warehouse.')->schema([
                Select::make('warehouse_id')->label('Warehouse')->options(fn (): array => static::warehouseOptions())->searchable()->required(),
                TextInput::make('code')->required()->maxLength(100)->placeholder('BAY-A01'),
                TextInput::make('name')->required()->maxLength(180)->placeholder('Bay A01'),
                Toggle::make('is_active')->label('Active for future stock workflows')->default(true),
                Toggle::make('is_default')->label('Default location')->disabled()->dehydrated(false)->visibleOn('edit')->helperText('The default location is created with its Warehouse and must remain active.'),
            ])->columns(2),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Storage Location')->schema([
                TextEntry::make('warehouse.name')->label('Warehouse'), TextEntry::make('code'), TextEntry::make('name'),
                TextEntry::make('is_default')->label('Default')->badge()->formatStateUsing(fn (bool $state): string => $state ? 'Default' : 'Standard')->color(fn (bool $state): string => $state ? 'primary' : 'gray'),
                TextEntry::make('is_active')->label('Status')->badge()->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive')->color(fn (bool $state): string => $state ? 'success' : 'gray'),
                TextEntry::make('updated_at')->label('Last updated')->since(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('warehouse.name')->label('Warehouse')->searchable()->sortable(), TextColumn::make('code')->searchable()->sortable(), TextColumn::make('name')->searchable()->sortable(),
            IconColumn::make('is_default')->label('Default')->boolean(), IconColumn::make('is_active')->label('Active')->boolean(), TextColumn::make('updated_at')->label('Updated')->since()->sortable(),
        ])->filters([
            SelectFilter::make('warehouse_id')->label('Warehouse')->options(fn (): array => static::warehouseOptions()), TernaryFilter::make('is_active')->label('Active status'),
        ])->defaultSort('warehouse_id')->searchPlaceholder('Search Storage Locations')->emptyStateHeading('No Storage Locations yet')->emptyStateDescription('Each Warehouse already has a Default storage location. Add additional locations only where operations need them.');
    }

    public static function getPages(): array
    {
        return ['index' => ListStorageLocations::route('/'), 'create' => CreateStorageLocation::route('/create'), 'view' => ViewStorageLocation::route('/{record}'), 'edit' => EditStorageLocation::route('/{record}/edit')];
    }

    public static function getEloquentQuery(): Builder
    {
        $companyId = ($user = auth()->user()) ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;

        return is_int($companyId) ? parent::getEloquentQuery()->with('warehouse')->where('company_id', $companyId) : parent::getEloquentQuery()->whereRaw('1 = 0');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return static::getEloquentQuery();
    }

    /** @return array<int, string> */
    public static function warehouseOptions(): array
    {
        $user = auth()->user();
        $companyId = $user ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;

        return is_int($companyId) ? Warehouse::query()->where('company_id', $companyId)->where('is_active', true)->orderBy('name')->pluck('name', 'id')->all() : [];
    }
}
