<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Personnel;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\Personnel\Pages\CreatePersonnel;
use App\Core\Administration\Filament\Resources\Personnel\Pages\EditPersonnel;
use App\Core\Administration\Filament\Resources\Personnel\Pages\ListPersonnel;
use App\Operations\Models\Personnel;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PersonnelResource extends Resource
{
    protected static ?string $model = Personnel::class;

    protected static ?string $slug = 'personnel';

    protected static ?string $modelLabel = 'Personnel';

    protected static ?string $pluralModelLabel = 'Personnel';

    protected static bool $shouldRegisterNavigation = false;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected static ?string $navigationLabel = 'Personnel';

    protected static ?int $navigationSort = 25;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([Section::make('Personnel')->schema([
            TextInput::make('first_name')->required()->maxLength(120), TextInput::make('last_name')->maxLength(120), TextInput::make('phone')->maxLength(50),
            Checkbox::make('can_operate_equipment')->label('Equipment Operator'), Checkbox::make('can_drive')->label('Driver'),
            Select::make('status')->options(['active' => 'Active', 'inactive' => 'Inactive'])->default('active')->required(),
            Select::make('user_id')->label('Linked Atlas User')->searchable()->options(fn (): array => self::userOptions()), Textarea::make('notes')->columnSpanFull(),
        ])->columns(2)]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('full_name')->label('Personnel')->searchable(['first_name', 'last_name']), TextColumn::make('can_operate_equipment')->label('Operator')->badge()->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No'), TextColumn::make('can_drive')->label('Driver')->badge()->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No'), TextColumn::make('status')->badge()])->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => ListPersonnel::route('/'), 'create' => CreatePersonnel::route('/create'), 'edit' => EditPersonnel::route('/{record}/edit')];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $companyId = $user ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;

        return is_int($companyId) ? parent::getEloquentQuery()->where('company_id', $companyId) : parent::getEloquentQuery()->whereRaw('1 = 0');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return static::getEloquentQuery();
    }

    /** @return array<int,string> */
    private static function userOptions(): array
    {
        $user = auth()->user();
        $companyId = $user ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;

        return $user && is_int($companyId) ? $user->newQuery()->where('tenant_id', $user->tenant_id)->whereHas('companies', fn ($q) => $q->whereKey($companyId))->orderBy('first_name')->get()->mapWithKeys(fn ($u) => [$u->id => $u->full_name])->all() : [];
    }
}
