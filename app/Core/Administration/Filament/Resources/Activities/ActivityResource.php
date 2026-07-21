<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Activities;

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Resources\Activities\Pages\ListActivities;
use App\Core\Administration\Filament\Resources\Activities\Pages\ViewActivity;
use App\Core\Administration\Filament\Resources\Activities\Schemas\ActivityInfolist;
use App\Core\Administration\Filament\Resources\Activities\Tables\ActivitiesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;
use UnitEnum;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Activity Log';

    protected static ?string $recordTitleAttribute = 'description';

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function infolist(Schema $schema): Schema
    {
        return ActivityInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ActivitiesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivities::route('/'),
            'view' => ViewActivity::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->latest();
        $user = auth()->user();

        if ($user === null) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasRole(RoleName::SuperAdministrator->value)) {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($user): void {
            $builder->where('properties->tenant_id', $user->tenant_id)
                ->orWhereNull('properties->tenant_id');
        });
    }
}
