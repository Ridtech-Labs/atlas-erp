<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Jobs;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\Jobs\Pages\CreateJob;
use App\Core\Administration\Filament\Resources\Jobs\Pages\EditJob;
use App\Core\Administration\Filament\Resources\Jobs\Pages\ListJobs;
use App\Core\Administration\Filament\Resources\Jobs\Pages\ViewJob;
use App\Core\Administration\Filament\Resources\Jobs\Schemas\JobForm;
use App\Core\Administration\Filament\Resources\Jobs\Schemas\JobInfolist;
use App\Core\Administration\Filament\Resources\Jobs\Tables\JobsTable;
use App\Operations\Models\Job;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|UnitEnum|null $navigationGroup = 'Operations';

    protected static ?string $navigationLabel = 'Jobs';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'job_number';

    public static function form(Schema $schema): Schema
    {
        return JobForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return JobInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobs::route('/'),
            'create' => CreateJob::route('/create'),
            'view' => ViewJob::route('/{record}'),
            'edit' => EditJob::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['client', 'site'])
            ->withoutGlobalScopes([SoftDeletingScope::class]);

        $user = auth()->user();
        $access = app(AdministrationAccessService::class);

        if ($user === null) {
            return $query->whereRaw('1 = 0');
        }

        if ($access->isSuperAdministrator($user)) {
            return $query;
        }

        return $query->where('tenant_id', $user->tenant_id);
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->with(['client', 'site'])
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
