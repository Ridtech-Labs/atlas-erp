<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Jobs\Schemas;

use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use App\Operations\Enums\JobPriority;
use App\Operations\Enums\JobStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                ->description('Capture the work request, operational context, and commercial reference for this job.')
                ->schema([
                    TextInput::make('job_number')
                        ->label('Job number')
                        ->placeholder('Auto-generated when left blank')
                        ->helperText('Leave blank to auto-generate a tenant-scoped job number.')
                        ->maxLength(50),
                    Select::make('client_id')
                        ->label('Client')
                        ->searchable()
                        ->live()
                        ->required()
                        ->options(fn () => Client::query()->orderBy('legal_name')->limit(50)->pluck('legal_name', 'id')->all())
                        ->placeholder('Select a client account')
                        ->afterStateUpdated(fn (Set $set) => $set('client_site_id', null)),
                    Select::make('client_site_id')
                        ->label('Site')
                        ->searchable()
                        ->options(fn (Get $get) => filled($get('client_id'))
                            ? ClientSite::query()
                                ->where('client_id', $get('client_id'))
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->limit(50)
                                ->pluck('name', 'id')
                                ->all()
                            : [])
                        ->placeholder('Choose a client site')
                        ->helperText('Only active sites for the selected client are available.'),
                    TextInput::make('title')->required()->maxLength(255)->placeholder('Routine maintenance visit'),
                    Textarea::make('description')->rows(4)->columnSpanFull()->placeholder('Describe the work scope, service expectations, and delivery notes.'),
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
                ->description('Plan timing, references, and expected value without exposing raw workflow controls.')
                ->schema([
                    DatePicker::make('requested_start_date'),
                    DatePicker::make('planned_start_date'),
                    DatePicker::make('planned_end_date'),
                    TextInput::make('client_reference')->maxLength(255)->placeholder('PO-2026-001'),
                    TextInput::make('internal_reference')->maxLength(255)->placeholder('OPS-PLANNER'),
                    TextInput::make('estimated_value')->numeric()->prefix('GHS')->step('0.01')->placeholder('0.00'),
                ])->columns(2),
        ]);
    }
}
