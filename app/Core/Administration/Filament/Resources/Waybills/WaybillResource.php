<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Waybills;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\Waybills\Pages\CreateWaybill;
use App\Core\Administration\Filament\Resources\Waybills\Pages\EditWaybill;
use App\Core\Administration\Filament\Resources\Waybills\Pages\ListWaybills;
use App\Core\Administration\Filament\Resources\Waybills\Pages\ViewWaybill;
use App\Core\Administration\Filament\Resources\Waybills\Schemas\WaybillForm;
use App\Core\Administration\Filament\Resources\Waybills\Schemas\WaybillInfolist;
use App\Core\Administration\Filament\Resources\Waybills\Tables\WaybillsTable;
use App\Operations\Models\Waybill;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WaybillResource extends Resource
{
    protected static ?string $model = Waybill::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return WaybillForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WaybillInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WaybillsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWaybills::route('/'),
            'create' => CreateWaybill::route('/create'),
            'view' => ViewWaybill::route('/{record}'),
            'edit' => EditWaybill::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['job', 'client'])
            ->withoutGlobalScopes([SoftDeletingScope::class]);

        $user = auth()->user();
        $access = app(AdministrationAccessService::class);

        if ($user === null) {
            return $query->whereRaw('1 = 0');
        }

        $companyId = $access->activeCompanyId($user);

        return $companyId === null
            ? $query->whereRaw('1 = 0')
            : $query->where('company_id', $companyId);
    }
}
