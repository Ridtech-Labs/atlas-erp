<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\RateAgreements;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\RateAgreements\Pages\CreateRateAgreement;
use App\Core\Administration\Filament\Resources\RateAgreements\Pages\EditRateAgreement;
use App\Core\Administration\Filament\Resources\RateAgreements\Pages\ListRateAgreements;
use App\Core\Administration\Filament\Resources\RateAgreements\Pages\ViewRateAgreement;
use App\Core\Administration\Filament\Resources\RateAgreements\Schemas\RateAgreementForm;
use App\Core\Administration\Filament\Resources\RateAgreements\Schemas\RateAgreementInfolist;
use App\Core\Administration\Filament\Resources\RateAgreements\Tables\RateAgreementsTable;
use App\Finance\Models\RateAgreement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class RateAgreementResource extends Resource
{
    protected static ?string $model = RateAgreement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Rate Agreements';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return RateAgreementForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RateAgreementInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RateAgreementsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRateAgreements::route('/'),
            'create' => CreateRateAgreement::route('/create'),
            'view' => ViewRateAgreement::route('/{record}'),
            'edit' => EditRateAgreement::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['client', 'company', 'lines'])
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
