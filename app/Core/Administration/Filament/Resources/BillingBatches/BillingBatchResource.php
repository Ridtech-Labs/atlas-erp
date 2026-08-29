<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\BillingBatches;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\BillingBatches\Pages\CreateBillingBatch;
use App\Core\Administration\Filament\Resources\BillingBatches\Pages\EditBillingBatch;
use App\Core\Administration\Filament\Resources\BillingBatches\Pages\ListBillingBatches;
use App\Core\Administration\Filament\Resources\BillingBatches\Pages\ViewBillingBatch;
use App\Core\Administration\Filament\Resources\BillingBatches\RelationManagers\BillingBatchLinesRelationManager;
use App\Core\Administration\Filament\Resources\BillingBatches\Schemas\BillingBatchForm;
use App\Core\Administration\Filament\Resources\BillingBatches\Schemas\BillingBatchInfolist;
use App\Core\Administration\Filament\Resources\BillingBatches\Tables\BillingBatchesTable;
use App\Finance\Models\BillingBatch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class BillingBatchResource extends Resource
{
    protected static ?string $model = BillingBatch::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static string|UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Billing Batches';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'batch_number';

    public static function form(Schema $schema): Schema
    {
        return BillingBatchForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BillingBatchInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BillingBatchesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            BillingBatchLinesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBillingBatches::route('/'),
            'create' => CreateBillingBatch::route('/create'),
            'view' => ViewBillingBatch::route('/{record}'),
            'edit' => EditBillingBatch::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['client', 'company'])
            ->withCount('lines')
            ->withoutGlobalScopes([SoftDeletingScope::class]);

        $user = auth()->user();
        $access = app(AdministrationAccessService::class);

        if ($user === null) {
            return $query->whereRaw('1 = 0');
        }

        $companyId = $access->activeCompanyId($user);

        return is_int($companyId)
            ? $query->where('company_id', $companyId)
            : $query->whereRaw('1 = 0');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return static::getEloquentQuery();
    }
}
