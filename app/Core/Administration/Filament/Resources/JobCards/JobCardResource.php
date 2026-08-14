<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\JobCards;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\JobCards\Pages\CreateJobCard;
use App\Core\Administration\Filament\Resources\JobCards\Pages\EditJobCard;
use App\Core\Administration\Filament\Resources\JobCards\Pages\ListJobCards;
use App\Core\Administration\Filament\Resources\JobCards\Pages\ViewJobCard;
use App\Core\Administration\Filament\Resources\JobCards\RelationManagers\JobCardWorkEntriesRelationManager;
use App\Core\Administration\Filament\Resources\JobCards\Schemas\JobCardForm;
use App\Core\Administration\Filament\Resources\JobCards\Schemas\JobCardInfolist;
use App\Core\Administration\Filament\Resources\JobCards\Tables\JobCardsTable;
use App\Operations\Models\JobCard;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JobCardResource extends Resource
{
    protected static ?string $model = JobCard::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return JobCardForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return JobCardInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobCardsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            JobCardWorkEntriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobCards::route('/'),
            'create' => CreateJobCard::route('/create'),
            'view' => ViewJobCard::route('/{record}'),
            'edit' => EditJobCard::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['job', 'operator', 'approver'])
            ->withoutGlobalScopes([SoftDeletingScope::class]);

        $user = auth()->user();
        $access = app(AdministrationAccessService::class);

        if ($user === null) {
            return $query->whereRaw('1 = 0');
        }

        $companyId = $access->activeCompanyId($user);

        if ($companyId === null) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('company_id', $companyId);
    }
}
