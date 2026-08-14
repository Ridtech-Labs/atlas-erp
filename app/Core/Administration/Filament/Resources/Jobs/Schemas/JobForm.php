<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Jobs\Schemas;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Models\Company;
use App\CRM\Enums\ClientSiteStatus;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use App\Operations\Enums\JobPriority;
use App\Operations\Enums\JobShift;
use App\Operations\Enums\JobStatus;
use App\Operations\Support\OperatorAssignmentService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class JobForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Job')
                ->description('Capture Kadmay’s planned operational record before job-card execution begins.')
                ->schema([
                    Placeholder::make('active_company')
                        ->label('Active company')
                        ->content(function (): string {
                            $user = auth()->user();
                            $company = $user ? app(AdministrationAccessService::class)->activeCompany($user) : null;

                            return $company instanceof Company ? $company->name : 'No active company selected';
                        }),
                    TextInput::make('job_reference')
                        ->label('Job reference')
                        ->maxLength(100)
                        ->placeholder('KAD-2026-001'),
                    TextInput::make('job_number')
                        ->label('Job number')
                        ->placeholder('Generated automatically after save')
                        ->helperText('Job numbers are generated from the active tenant sequence.')
                        ->maxLength(50)
                        ->disabled()
                        ->dehydrated(false),
                    Select::make('client_id')
                        ->label('Client')
                        ->searchable()
                        ->live()
                        ->required()
                        ->options(function (): array {
                            $user = auth()->user();
                            $companyId = $user ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;

                            if (! is_int($companyId)) {
                                return [];
                            }

                            return Client::query()
                                ->where('company_id', $companyId)
                                ->orderBy('legal_name')
                                ->limit(50)
                                ->pluck('legal_name', 'id')
                                ->all();
                        })
                        ->placeholder('Select a client account')
                        ->afterStateUpdated(fn (Set $set) => $set('client_site_id', null)),
                    Select::make('client_site_id')
                        ->label('Site')
                        ->searchable()
                        ->options(function (Get $get): array {
                            $user = auth()->user();
                            $companyId = $user ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;
                            $clientId = $get('client_id');

                            if (! is_int($companyId) || ! filled($clientId)) {
                                return [];
                            }

                            $client = Client::query()
                                ->whereKey((int) $clientId)
                                ->where('company_id', $companyId)
                                ->first();

                            if (! $client instanceof Client) {
                                return [];
                            }

                            $selectedSiteId = $get('client_site_id');

                            return ClientSite::query()
                                ->where('client_id', $client->getKey())
                                ->where('company_id', $companyId)
                                ->where(function ($query) use ($selectedSiteId): void {
                                    $query->where('status', ClientSiteStatus::Active->value);

                                    if (filled($selectedSiteId)) {
                                        $query->orWhere(fn ($siteQuery) => $siteQuery->whereKey((int) $selectedSiteId));
                                    }
                                })
                                ->orderBy('name')
                                ->limit(50)
                                ->pluck('name', 'id')
                                ->all();
                        })
                        ->placeholder('Choose a client site')
                        ->helperText('Only active sites for the selected client are available.'),
                    TextInput::make('title')->label('Operational description')->required()->maxLength(255)->placeholder('Night-shift plant support for GPHA operations'),
                    TextInput::make('vessel')->maxLength(255)->placeholder('MV Atlantic Trader'),
                    TextInput::make('work_area')->maxLength(255)->placeholder('Jubilee Terminal - Berth 2'),
                    TextInput::make('equipment_requirement')->label('Required equipment or asset')->maxLength(255)->placeholder('Forklift FL-12'),
                    Select::make('operator_source')
                        ->label('Operator type')
                        ->options([
                            'company_personnel' => 'Company personnel',
                            'external' => 'External / temporary operator',
                        ])
                        ->placeholder('Select operator type')
                        ->live()
                        ->dehydrated(false)
                        ->afterStateHydrated(function (Set $set, Get $get): void {
                            if (filled($get('assigned_operator_id'))) {
                                $set('operator_source', 'company_personnel');

                                return;
                            }

                            if (filled($get('assigned_operator_name'))) {
                                $set('operator_source', 'external');
                            }
                        })
                        ->afterStateUpdated(function (?string $state, Set $set): void {
                            if ($state === 'company_personnel') {
                                $set('assigned_operator_name', null);

                                return;
                            }

                            if ($state === 'external') {
                                $set('assigned_operator_id', null);

                                return;
                            }

                            $set('assigned_operator_id', null);
                            $set('assigned_operator_name', null);
                        }),
                    Select::make('assigned_operator_id')
                        ->label('Operator')
                        ->searchable()
                        ->visible(fn (Get $get): bool => $get('operator_source') === 'company_personnel')
                        ->options(function (): array {
                            $user = auth()->user();
                            $companyId = $user ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;

                            if (! $user || ! is_int($companyId)) {
                                return [];
                            }

                            return app(OperatorAssignmentService::class)->companyOperatorOptions($user->tenant_id, $companyId);
                        })
                        ->placeholder('Select company personnel'),
                    TextInput::make('assigned_operator_name')
                        ->label('External or temporary operator name')
                        ->visible(fn (Get $get): bool => $get('operator_source') === 'external')
                        ->maxLength(255)
                        ->placeholder('Kwame Mensah'),
                    Select::make('shift')
                        ->options(collect(JobShift::cases())->mapWithKeys(fn (JobShift $shift) => [$shift->value => $shift->label()])->all())
                        ->default(JobShift::Day->value)
                        ->required(),
                    Textarea::make('description')->label('Notes')->rows(4)->columnSpanFull()->placeholder('Dispatch notes, safety instructions, berth access details, or client-specific operational remarks.'),
                    Select::make('status')
                        ->options(collect(JobStatus::cases())->mapWithKeys(fn (JobStatus $status) => [$status->value => $status->label()])->all())
                        ->default(JobStatus::Draft->value)
                        ->disabled(fn (string $operation) => $operation !== 'create'),
                    Select::make('priority')
                        ->options(collect(JobPriority::cases())->mapWithKeys(fn (JobPriority $priority) => [$priority->value => $priority->label()])->all())
                        ->default(JobPriority::Normal->value)
                        ->required(),
                ])->columns(2),
            Section::make('Schedule')
                ->description('Record the intended operating window and commercial references for the planned job.')
                ->schema([
                    DatePicker::make('requested_start_date'),
                    DatePicker::make('planned_start_date'),
                    TimePicker::make('planned_start_time')->seconds(false),
                    DatePicker::make('planned_end_date'),
                    TimePicker::make('planned_end_time')->seconds(false),
                    TextInput::make('client_reference')->label('Client reference')->maxLength(255)->placeholder('PO-2026-001'),
                    TextInput::make('internal_reference')->label('Internal reference')->maxLength(255)->placeholder('OPS-PLANNER'),
                    TextInput::make('estimated_value')
                        ->numeric()
                        ->prefix(function (): string {
                            $user = auth()->user();
                            $company = $user ? app(AdministrationAccessService::class)->activeCompany($user) : null;

                            return $company instanceof Company ? strtoupper((string) $company->currency) : 'CUR';
                        })
                        ->step('0.01')
                        ->placeholder('0.00'),
                ])->columns(2),
        ]);
    }
}
