<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\JobCards\Schemas;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Models\Company;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Enums\JobShift;
use App\Operations\Models\Job;
use App\Operations\Support\OperatorAssignmentService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class JobCardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Hidden::make('job_id')->default((int) request()->integer('job')),
            Section::make('Client Job Card')
                ->schema([
                    Placeholder::make('active_company')
                        ->label('Active company')
                        ->content(function (): string {
                            $user = auth()->user();
                            $company = $user ? app(AdministrationAccessService::class)->activeCompany($user) : null;

                            return $company instanceof Company ? $company->name : 'No active company';
                        }),
                    TextInput::make('client_card_reference')
                        ->label('Client Job Card reference')
                        ->maxLength(255)
                        ->placeholder('GPHA-JC-2026-0041'),
                    DatePicker::make('card_date')->required(),
                    Select::make('shift')
                        ->options(collect(JobShift::cases())->mapWithKeys(fn (JobShift $shift) => [$shift->value => $shift->label()])->all())
                        ->required(),
                    TextInput::make('equipment_reference')->label('Forklift No. / equipment')->maxLength(255),
                    TextInput::make('machine_number')->maxLength(255)->placeholder('FLT-16T-04'),
                    TimePicker::make('from_time')->seconds(false),
                    TimePicker::make('to_time')->seconds(false),
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
                            if (filled($get('operator_id'))) {
                                $set('operator_source', 'company_personnel');

                                return;
                            }

                            if (filled($get('operated_by'))) {
                                $set('operator_source', 'external');

                                return;
                            }

                            $default = self::defaultOperatorAssignment((int) ($get('job_id') ?? request()->integer('job')));
                            $set('operator_source', $default['source']);
                        })
                        ->afterStateUpdated(function (?string $state, Set $set): void {
                            if ($state === 'company_personnel') {
                                $set('operated_by', null);

                                return;
                            }

                            if ($state === 'external') {
                                $set('operator_id', null);

                                return;
                            }

                            $set('operator_id', null);
                            $set('operated_by', null);
                        }),
                    Select::make('operator_id')
                        ->label('Operator')
                        ->searchable()
                        ->visible(fn (Get $get): bool => $get('operator_source') === 'company_personnel')
                        ->default(fn (Get $get): ?int => self::defaultOperatorAssignment((int) ($get('job_id') ?? request()->integer('job')))['operator_id'])
                        ->options(function (): array {
                            $user = auth()->user();
                            $companyId = $user ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;

                            if (! $user || ! is_int($companyId)) {
                                return [];
                            }

                            return app(OperatorAssignmentService::class)->companyOperatorOptions($user->tenant_id, $companyId);
                        }),
                    TextInput::make('operated_by')
                        ->label('External or temporary operator name')
                        ->visible(fn (Get $get): bool => $get('operator_source') === 'external')
                        ->default(fn (Get $get): ?string => self::defaultOperatorAssignment((int) ($get('job_id') ?? request()->integer('job')))['external_name'])
                        ->maxLength(255)
                        ->placeholder('Kofi Asante'),
                    Repeater::make('operators')
                        ->label('Recorded operators')
                        ->schema([
                            Select::make('user_id')
                                ->label('Company personnel')
                                ->searchable()
                                ->options(function (): array {
                                    $user = auth()->user();
                                    $companyId = $user ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;

                                    if (! $user || ! is_int($companyId)) {
                                        return [];
                                    }

                                    return app(OperatorAssignmentService::class)->companyOperatorOptions($user->tenant_id, $companyId);
                                }),
                            TextInput::make('operator_name')
                                ->label('External or temporary operator name')
                                ->maxLength(255),
                        ])
                        ->defaultItems(1)
                        ->reorderable(false)
                        ->columnSpanFull(),
                    TextInput::make('supervising_officer_name')->maxLength(255),
                    TextInput::make('header_hours')->numeric()->step('0.01')->placeholder('8.00'),
                    TextInput::make('total_hours')->numeric()->step('0.01')->placeholder('8.00'),
                    Toggle::make('client_endorsed')->label('Client endorsed'),
                    Toggle::make('client_stamped')->label('Client stamp confirmed'),
                    Select::make('approval_status')
                        ->options(collect(JobCardApprovalStatus::cases())->mapWithKeys(fn (JobCardApprovalStatus $status) => [$status->value => $status->label()])->all())
                        ->default(JobCardApprovalStatus::Recorded->value)
                        ->disabled(),
                    TextInput::make('rate_currency')->length(3)->placeholder('GHS'),
                    TextInput::make('hourly_rate')->numeric()->step('0.01')->placeholder('0.00'),
                    TextInput::make('exchange_rate')->numeric()->step('0.0001')->placeholder('1.0000'),
                    TextInput::make('converted_hourly_rate')->numeric()->step('0.01')->placeholder('0.00'),
                    TextInput::make('billable_amount')->numeric()->step('0.01')->placeholder('Calculated from hours and rate'),
                    Textarea::make('rate_notes')->rows(3)->columnSpanFull(),
                    Textarea::make('verification_notes')->rows(3)->columnSpanFull(),
                    Textarea::make('officer_remarks')->rows(4)->columnSpanFull(),
                    FileUpload::make('attachments')
                        ->multiple()
                        ->disk('local')
                        ->directory('job-card-uploads')
                        ->preserveFilenames()
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    /**
     * @return array{operator_id:?int, external_name:?string, source:?string}
     */
    private static function defaultOperatorAssignment(int $jobId): array
    {
        if ($jobId <= 0) {
            return [
                'operator_id' => null,
                'external_name' => null,
                'source' => null,
            ];
        }

        $job = Job::query()->with('jobCards')->find($jobId);

        if (! $job instanceof Job) {
            return [
                'operator_id' => null,
                'external_name' => null,
                'source' => null,
            ];
        }

        return app(OperatorAssignmentService::class)->defaultJobCardAssignment($job);
    }
}
