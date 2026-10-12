<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\InventoryItems\Schemas;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Models\Company;
use App\Inventory\Enums\InventoryItemStatus;
use App\Inventory\Enums\UnitOfMeasure;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InventoryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Inventory item')
                ->description('Maintain controlled company stock master data. Opening balances and future quantity changes are recorded through auditable Inventory movements.')
                ->schema([
                    Placeholder::make('active_company')
                        ->label('Active company')
                        ->content(function (): string {
                            $company = auth()->user() ? app(AdministrationAccessService::class)->activeCompany(auth()->user()) : null;

                            return $company instanceof Company ? $company->name : 'No active company';
                        }),
                    TextInput::make('code')
                        ->label('Item code')
                        ->required()
                        ->maxLength(100)
                        ->placeholder('FLT-HYD-001'),
                    TextInput::make('name')
                        ->label('Item name')
                        ->required()
                        ->maxLength(180)
                        ->placeholder('Hydraulic Filter'),
                    TextInput::make('category')
                        ->required()
                        ->maxLength(120)
                        ->placeholder('Filters'),
                    Select::make('unit_of_measure')
                        ->label('Unit of measure')
                        ->options(collect(UnitOfMeasure::cases())->mapWithKeys(fn (UnitOfMeasure $unit): array => [$unit->value => $unit->label()])->all())
                        ->required(),
                    TextInput::make('minimum_stock_level')
                        ->label('Minimum stock level')
                        ->numeric()
                        ->minValue(0)
                        ->default('0.000')
                        ->required()
                        ->helperText('Atlas flags the item as low stock when quantity on hand is at or below this level.'),
                    Select::make('status')
                        ->options(collect(InventoryItemStatus::cases())->mapWithKeys(fn (InventoryItemStatus $status): array => [$status->value => $status->label()])->all())
                        ->default(InventoryItemStatus::Active->value)
                        ->required(),
                    Placeholder::make('quantity_on_hand')
                        ->label('Quantity on hand')
                        ->content(fn ($record): string => $record === null ? '0.000' : number_format((float) $record->quantity_on_hand, 3))
                        ->helperText('Quantity changes only through auditable Inventory movements. Opening balance will be available in the next Inventory phase.'),
                    Textarea::make('description')
                        ->label('Description')
                        ->rows(4)
                        ->maxLength(2000)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}
