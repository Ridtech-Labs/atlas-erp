<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\RateAgreements\Schemas;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Models\Company;
use App\CRM\Models\Client;
use App\Finance\Enums\BillingUnit;
use App\Finance\Enums\RateAgreementStatus;
use App\Finance\Models\RateAgreement;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class RateAgreementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Commercial scope')
                ->description('Define the customer, date range, and agreement identity that commercial billing will resolve against.')
                ->schema([
                    Placeholder::make('active_company')
                        ->label('Active company')
                        ->content(function (): string {
                            $user = auth()->user();
                            $company = $user ? app(AdministrationAccessService::class)->activeCompany($user) : null;

                            return $company instanceof Company ? $company->name : 'No active company';
                        }),
                    Select::make('client_id')
                        ->label('Client')
                        ->options(fn (): array => self::clientOptions())
                        ->searchable()
                        ->live()
                        ->required()
                        ->disabled(fn (?RateAgreement $record): bool => $record instanceof RateAgreement)
                        ->dehydrated(fn (?RateAgreement $record): bool => ! ($record instanceof RateAgreement)),
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Kadmay heavy machinery hourly tariff'),
                    TextInput::make('reference')
                        ->maxLength(255)
                        ->placeholder('RA-KAD-2026-001'),
                    DatePicker::make('effective_from')->required(),
                    DatePicker::make('effective_to'),
                    Select::make('status')
                        ->options(collect(RateAgreementStatus::cases())->mapWithKeys(fn (RateAgreementStatus $status) => [$status->value => $status->label()])->all())
                        ->default(RateAgreementStatus::Active->value)
                        ->required(),
                    Textarea::make('notes')
                        ->rows(4)
                        ->columnSpanFull()
                        ->placeholder('Commercial notes, approval reference, or scope limitations.'),
                ])
                ->columns(2),
            Section::make('Rate lines')
                ->description('Atlas resolves billing by matching client, activity date, and either an exact machine override or the operational equipment class/type. Leave line dates blank to inherit the agreement period.')
                ->schema([
                    Repeater::make('lines')
                        ->label('Agreement lines')
                        ->required()
                        ->defaultItems(1)
                        ->reorderable(false)
                        ->schema([
                            TextInput::make('equipment_reference')
                                ->label('Equipment class / type')
                                ->maxLength(255)
                                ->placeholder('Forklift 4.5T/5T')
                                ->helperText('Use the commercial equipment class/type Atlas should match against operational Job Card evidence.')
                                ->datalist(fn (Get $get, ?RateAgreement $record): array => self::equipmentClassSuggestions(
                                    self::selectedClientId($get, $record),
                                ))
                                ->visible(fn (Get $get): bool => $get('billing_unit') !== BillingUnit::Trip->value),
                            TextInput::make('machine_number')
                                ->maxLength(255)
                                ->placeholder('Optional exact machine reference')
                                ->helperText('Use this only when one specific machine has its own override rate.')
                                ->visible(fn (Get $get): bool => $get('billing_unit') !== BillingUnit::Trip->value),
                            Select::make('billing_unit')
                                ->options(collect(BillingUnit::cases())->mapWithKeys(fn (BillingUnit $unit) => [$unit->value => $unit->label()])->all())
                                ->default(BillingUnit::Hourly->value)
                                ->live()
                                ->afterStateUpdated(function (?string $state, Set $set): void {
                                    if ($state === BillingUnit::Trip->value) {
                                        $set('equipment_reference', null);
                                        $set('machine_number', null);

                                        return;
                                    }

                                    $set('pickup_point', null);
                                    $set('destination', null);
                                })
                                ->required(),
                            TextInput::make('pickup_point')->label('Pickup point')->maxLength(255)->visible(fn (Get $get): bool => $get('billing_unit') === BillingUnit::Trip->value)->required(fn (Get $get): bool => $get('billing_unit') === BillingUnit::Trip->value),
                            TextInput::make('destination')->maxLength(255)->visible(fn (Get $get): bool => $get('billing_unit') === BillingUnit::Trip->value)->required(fn (Get $get): bool => $get('billing_unit') === BillingUnit::Trip->value),
                            TextInput::make('currency')
                                ->default('GHS')
                                ->length(3)
                                ->required(),
                            TextInput::make('rate')
                                ->numeric()
                                ->step('0.01')
                                ->required(),
                            DatePicker::make('effective_from')
                                ->helperText('Leave blank to inherit the agreement start date.'),
                            DatePicker::make('effective_to')
                                ->helperText('Leave blank to inherit the agreement end date.'),
                        ])
                        ->columns(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    /**
     * @return array<int, string>
     */
    private static function clientOptions(): array
    {
        $user = auth()->user();

        if ($user === null) {
            return [];
        }

        $companyId = app(AdministrationAccessService::class)->activeCompanyId($user);

        if (! is_int($companyId)) {
            return [];
        }

        return Client::query()
            ->where('company_id', $companyId)
            ->orderBy('legal_name')
            ->get()
            ->mapWithKeys(fn (Client $client): array => [$client->getKey() => $client->display_name])
            ->all();
    }

    private static function selectedClientId(Get $get, ?RateAgreement $record): ?int
    {
        return self::resolveSelectedClientId([
            $get('../../client_id'),
            $get('../client_id'),
            $get('client_id'),
        ], $record);
    }

    /**
     * @return list<string>
     */
    private static function equipmentClassSuggestions(?int $clientId): array
    {
        if (! is_int($clientId)) {
            return [];
        }

        $client = Client::query()->find($clientId);

        if (! $client instanceof Client) {
            return [];
        }

        return array_values(collect()
            ->merge(
                Job::query()
                    ->where('tenant_id', $client->tenant_id)
                    ->where('company_id', $client->company_id)
                    ->where('client_id', $client->getKey())
                    ->whereNotNull('equipment_requirement')
                    ->pluck('equipment_requirement'),
            )
            ->merge(
                JobCard::query()
                    ->where('tenant_id', $client->tenant_id)
                    ->where('company_id', $client->company_id)
                    ->where('client_id', $client->getKey())
                    ->whereNotNull('equipment_reference')
                    ->pluck('equipment_reference'),
            )
            ->filter(fn (mixed $value): bool => is_string($value) && trim($value) !== '')
            ->map(fn (string $value): string => preg_replace('/\s+/', ' ', trim($value)) ?? trim($value))
            ->unique()
            ->sort()
            ->values()
            ->all());
    }

    /**
     * @param  array<int, mixed>  $candidateClientIds
     */
    private static function resolveSelectedClientId(array $candidateClientIds, ?RateAgreement $record): ?int
    {
        foreach ($candidateClientIds as $candidateClientId) {
            if (is_numeric($candidateClientId)) {
                return (int) $candidateClientId;
            }
        }

        return $record?->client_id;
    }
}
