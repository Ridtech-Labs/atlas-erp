<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\BillingRecords;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\BillingRecords\Pages\CreateBillingRecord;
use App\Core\Administration\Filament\Resources\BillingRecords\Pages\EditBillingRecord;
use App\Core\Administration\Filament\Resources\BillingRecords\Pages\ListBillingRecords;
use App\Core\Administration\Filament\Resources\BillingRecords\Pages\ViewBillingRecord;
use App\Core\Administration\Filament\Resources\BillingRecords\Schemas\BillingRecordForm;
use App\Core\Administration\Filament\Resources\BillingRecords\Schemas\BillingRecordInfolist;
use App\Core\Administration\Filament\Resources\BillingRecords\Tables\BillingRecordsTable;
use App\Finance\Models\BillingRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class BillingRecordResource extends Resource
{
    protected static ?string $model = BillingRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Billing Records';

    protected static ?int $navigationSort = 30;

    protected static ?string $recordTitleAttribute = 'record_number';

    public static function form(Schema $schema): Schema
    {
        return BillingRecordForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BillingRecordInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BillingRecordsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBillingRecords::route('/'),
            'create' => CreateBillingRecord::route('/create'),
            'view' => ViewBillingRecord::route('/{record}'),
            'edit' => EditBillingRecord::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['client', 'company', 'billingBatch'])
            ->withoutGlobalScopes([SoftDeletingScope::class]);

        $user = auth()->user();
        $companyId = $user ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;

        return is_int($companyId)
            ? $query->where('company_id', $companyId)
            : $query->whereRaw('1 = 0');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return static::getEloquentQuery();
    }
}
