<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\FleetAssetTypes\Schemas;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Models\Company;
use App\Fleet\Enums\FleetAssetCategory;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FleetAssetTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Asset type')
                ->description('Define a controlled Fleet classification that Operations will use to identify machinery and transport assets.')
                ->schema([
                    Placeholder::make('active_company')
                        ->label('Active company')
                        ->content(function (): string {
                            $company = auth()->user() ? app(AdministrationAccessService::class)->activeCompany(auth()->user()) : null;

                            return $company instanceof Company ? $company->name : 'No active company';
                        }),
                    TextInput::make('name')
                        ->required()
                        ->maxLength(150)
                        ->placeholder('Reach Stacker'),
                    Select::make('category')
                        ->options(collect(FleetAssetCategory::cases())->mapWithKeys(fn (FleetAssetCategory $category): array => [$category->value => $category->label()])->all())
                        ->required(),
                    Toggle::make('is_active')
                        ->label('Active for new Fleet assets')
                        ->default(true)
                        ->helperText('Inactive types remain on historical assets but cannot be selected for new or updated assets.'),
                ])
                ->columns(2),
        ]);
    }
}
