<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\JobCards\Schemas;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Models\Company;
use App\Models\User;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Enums\JobShift;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Support\OperatorAssignmentService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                    Placeholder::make('recording_guidance')
                        ->label('Recording guidance')
                        ->content('Capture only what appears on the client-issued Job Card. Accounts review and billing are completed separately after this record is saved.')
                        ->columnSpanFull(),
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
                    Placeholder::make('work_entry_guidance')
                        ->label('Work Entries')
                        ->content(fn (?JobCard $record): string => $record instanceof JobCard
                            ? 'Use Work Entries below to capture each physical row on the client-issued Job Card. Atlas totals the recorded hours automatically from those entries.'
                            : 'After saving this Job Card header, add one or more Work Entries to record the actual rows from the physical client-issued Job Card.'),
                    Placeholder::make('calculated_total_hours')
                        ->label('Total Recorded Hours')
                        ->content(fn (?JobCard $record): string => $record instanceof JobCard && $record->displayTotalHours() !== null
                            ? number_format((float) $record->displayTotalHours(), 2)
                            : 'Atlas will calculate this from Work Entries.'),
                    Toggle::make('client_endorsed')->label('Client endorsed'),
                    Toggle::make('client_stamped')->label('Client stamp confirmed'),
                    Textarea::make('officer_remarks')->rows(4)->columnSpanFull(),
                    FileUpload::make('attachments')
                        ->multiple()
                        ->disk('local')
                        ->directory('job-card-uploads')
                        ->preserveFilenames()
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Accounts Review')
                ->schema([
                    Placeholder::make('verification_guidance')
                        ->label('Accounts review stage')
                        ->content(function ($record): string {
                            if (! $record instanceof JobCard) {
                                return 'After recording, Finance or Accounts will review the Job Card against the uploaded client document before billing.';
                            }

                            return match ((string) $record->getRawOriginal('approval_status')) {
                                JobCardApprovalStatus::PendingVerification->value => 'This Job Card is awaiting Accounts review against the client-issued document before billing can continue.',
                                JobCardApprovalStatus::Returned->value => 'This Job Card was returned to Operations. Update the recorded evidence, then submit it back to Accounts.',
                                JobCardApprovalStatus::Verified->value => 'This Job Card has been reviewed by Accounts and can now move into billing preparation.',
                                JobCardApprovalStatus::BillingReady->value => 'This Job Card has completed Accounts review and has a billing basis ready for invoicing.',
                                default => 'Use the Accounts review action after recording to confirm the evidence on the client-issued Job Card.',
                            };
                        })
                        ->columnSpanFull(),
                    Placeholder::make('verification_notes_display')
                        ->label('Accounts review notes')
                        ->content(fn ($record): string => $record instanceof JobCard && filled($record->verification_notes) ? (string) $record->verification_notes : 'No Accounts review notes recorded yet.')
                        ->visible(fn ($record): bool => $record instanceof JobCard),
                ])
                ->columns(1),
            Section::make('Billing Preparation')
                ->schema([
                    Placeholder::make('billing_guidance')
                        ->label('Billing stage')
                        ->content(function ($record): string {
                            if (! $record instanceof JobCard) {
                                return 'After Accounts review, Finance prepares a Billing Batch that resolves rates from active Rate Agreements and snapshots the commercial basis for invoicing.';
                            }

                            return match ((string) $record->getRawOriginal('approval_status')) {
                                JobCardApprovalStatus::Verified->value => 'This Job Card has passed Accounts review and is ready for Billing Batch preparation. Rates are resolved from active Rate Agreements when Finance adds reviewed Work Entries to a Billing Batch.',
                                JobCardApprovalStatus::BillingReady->value => 'This Job Card has been included in billing preparation. Its commercial snapshot now belongs to the related Billing Batch lines, not manual rate entry on the Job Card.',
                                default => 'After Accounts review, Finance prepares a Billing Batch that resolves rates from active Rate Agreements and snapshots the commercial basis for invoicing.',
                            };
                        })
                        ->columnSpanFull(),
                    Placeholder::make('billing_batch_summary')
                        ->label('Commercial basis')
                        ->content(function ($record): string {
                            if (! $record instanceof JobCard) {
                                return 'Billing Batch preparation will resolve the applicable rate and snapshot the commercial totals.';
                            }

                            if ((string) $record->getRawOriginal('approval_status') === JobCardApprovalStatus::BillingReady->value) {
                                return 'Billing Batch preparation has marked this Job Card billing ready. Review the related Billing Batch lines for the resolved rate and commercial totals.';
                            }

                            return 'No commercial values are captured directly on this Job Card in the Phase 2B workflow. Finance completes rate resolution and billing snapshots from Billing Batches.';
                        })
                        ->columnSpanFull(),
                ])
                ->columns(1)
                ->visible(fn ($record): bool => $record instanceof JobCard
                    && self::canManageBilling($record)
                    && in_array((string) $record->getRawOriginal('approval_status'), [JobCardApprovalStatus::Verified->value, JobCardApprovalStatus::BillingReady->value], true)),
        ]);
    }

    private static function canManageBilling(JobCard $jobCard): bool
    {
        $user = auth()->user();

        return $user instanceof User
            && $user->hasPermissionTo(PermissionName::JobCardsBill->value);
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
