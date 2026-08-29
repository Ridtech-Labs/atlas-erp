<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\FleetAssets\Schemas;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Models\Company;
use App\Fleet\Enums\FleetAssetCategory;
use App\Fleet\Enums\FleetAssetOperationalStatus;
use App\Fleet\Models\FleetAssetType;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FleetAssetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Fleet identity')
                ->description('Register the stable operational identity for machinery, transport, trailers, and other Fleet assets.')
                ->schema([
                    Placeholder::make('active_company')
                        ->label('Active company')
                        ->content(function (): string {
                            $company = auth()->user() ? app(AdministrationAccessService::class)->activeCompany(auth()->user()) : null;

                            return $company instanceof Company ? $company->name : 'No active company';
                        }),
                    TextInput::make('asset_number')
                        ->label('Asset number')
                        ->required()
                        ->maxLength(100)
                        ->helperText('Stable internal machine or asset identity. Atlas normalizes this to uppercase.')
                        ->placeholder('RS-003'),
                    Select::make('fleet_asset_type_id')
                        ->label('Asset type')
                        ->options(fn (): array => self::assetTypeOptions())
                        ->searchable()
                        ->required(),
                    TextInput::make('registration_number')
                        ->label('Registration number')
                        ->maxLength(100)
                        ->helperText('Optional. Use for trucks or road-registered assets. Atlas normalizes this to uppercase.')
                        ->placeholder('GT 1234-26'),
                    Select::make('operational_status')
                        ->label('Operational status')
                        ->options(collect(FleetAssetOperationalStatus::cases())->mapWithKeys(fn (FleetAssetOperationalStatus $status): array => [$status->value => $status->label()])->all())
                        ->default(FleetAssetOperationalStatus::Available->value)
                        ->required(),
                ])
                ->columns(2),
            Section::make('Manufacturer details')
                ->description('Optional manufacturer and identification details. These do not replace the Atlas asset number.')
                ->schema([
                    TextInput::make('make')->maxLength(120)->placeholder('Kalmar'),
                    TextInput::make('model')->maxLength(120)->placeholder('DRF450-65S5'),
                    TextInput::make('serial_number')->maxLength(120)->placeholder('Serial number'),
                    Textarea::make('notes')->rows(4)->columnSpanFull()->placeholder('Operational notes or identification details.'),
                ])
                ->columns(2),
        ]);
    }

    /** @return array<int, string> */
    private static function assetTypeOptions(): array
    {
        $user = auth()->user();
        $companyId = $user ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;

        if (! is_int($companyId)) {
            return [];
        }

        return FleetAssetType::query()
            ->where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (FleetAssetType $type): array => [
                $type->getKey() => sprintf('%s (%s)', $type->name, FleetAssetCategory::from((string) $type->getRawOriginal('category'))->label()),
            ])
            ->all();
    }
}
