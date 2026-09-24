<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Waybills\Schemas;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Models\Company;
use App\Operations\Enums\WaybillStatus;
use App\Operations\Support\OperatorAssignmentService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WaybillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Hidden::make('job_id')->default((int) request()->integer('job')),
            Section::make('Waybill')
                ->schema([
                    Placeholder::make('active_company')
                        ->label('Active company')
                        ->content(function (): string {
                            $user = auth()->user();
                            $company = $user ? app(AdministrationAccessService::class)->activeCompany($user) : null;

                            return $company instanceof Company ? $company->name : 'No active company';
                        }),
                    TextInput::make('waybill_number')->label('Waybill number')->maxLength(255),
                    TextInput::make('client_reference')->label('Client-issued reference')->maxLength(255),
                    DatePicker::make('waybill_date')->required(),
                    Select::make('driver_personnel_id')->label('Driver / Personnel')->searchable()->options(function (): array {
                        $user = auth()->user();
                        $companyId = $user ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;

                        return $user && is_int($companyId) ? app(OperatorAssignmentService::class)->companyDriverOptions($user->tenant_id, $companyId) : [];
                    }),
                    TextInput::make('driver_name')->label('External driver')->maxLength(255),
                    TextInput::make('truck_number')->required()->maxLength(255),
                    TextInput::make('number_of_trips')->numeric()->default(1)->required(),
                    TextInput::make('pickup_point')->maxLength(255),
                    TextInput::make('destination')->maxLength(255),
                    TextInput::make('amount_paid')
                        ->label('Operational amount paid')
                        ->helperText('Internal operational payment information only. Client billing is determined from the applicable Finance Rate Agreement.')
                        ->numeric()
                        ->step('0.01'),
                    TextInput::make('amount_paid_to_driver')
                        ->label('Amount paid to driver')
                        ->helperText('Driver payment tracking only. This does not determine the amount billed to the client.')
                        ->numeric()
                        ->step('0.01'),
                    TextInput::make('signature_name')->maxLength(255),
                    Placeholder::make('status_summary')
                        ->label('Workflow status')
                        ->content(fn ($record): string => $record?->status instanceof WaybillStatus ? $record->status->label() : WaybillStatus::Recorded->label()),
                    FileUpload::make('attachments')
                        ->multiple()
                        ->disk('local')
                        ->directory('waybill-uploads')
                        ->preserveFilenames()
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}
