<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Clients\Schemas;

use App\Core\Tenancy\Models\Tenant;
use App\CRM\Enums\ClientStatus;
use App\CRM\Enums\ClientType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Client profile')
                ->description('Define the customer account, account type, and compliance details used throughout Atlas ERP.')
                ->schema([
                    Select::make('tenant_id')
                        ->label('Company')
                        ->options(fn () => Tenant::query()->orderBy('name')->pluck('name', 'id')->all())
                        ->default(auth()->user()?->tenant_id)
                        ->helperText('Super Administrators can place this account in any company workspace.')
                        ->visible(fn () => auth()->user()?->hasRole('Super Administrator'))
                        ->required(),
                    TextInput::make('client_code')
                        ->label('Client code')
                        ->placeholder('Auto-generated when left blank')
                        ->helperText('Leave blank to auto-generate a tenant-scoped client code.')
                        ->maxLength(50),
                    TextInput::make('legal_name')->required()->maxLength(255)->placeholder('Kadmay Global Limited'),
                    TextInput::make('trading_name')->maxLength(255)->placeholder('Kadmay Global'),
                    Select::make('client_type')
                        ->label('Client type')
                        ->options(collect(ClientType::cases())->mapWithKeys(fn (ClientType $type) => [$type->value => $type->label()])->all())
                        ->required(),
                    Select::make('status')
                        ->options(collect(ClientStatus::cases())->mapWithKeys(fn (ClientStatus $status) => [$status->value => $status->label()])->all())
                        ->required(),
                    TextInput::make('tax_identification_number')->label('Tax ID')->maxLength(100)->placeholder('C0001234567'),
                    TextInput::make('registration_number')->maxLength(100)->placeholder('CS1234562026'),
                ])->columns(2),
            Section::make('Contact and billing')
                ->description('Capture the commercial and operational information your team needs every day.')
                ->schema([
                    TextInput::make('email')->email()->maxLength(255)->placeholder('accounts@client.com'),
                    TextInput::make('phone')->tel()->maxLength(30)->placeholder('+233 20 000 0000'),
                    TextInput::make('alternate_phone')->tel()->maxLength(30)->placeholder('+233 24 000 0000'),
                    TextInput::make('website')->url()->maxLength(255)->placeholder('https://client.com'),
                    TextInput::make('payment_terms_days')->numeric()->integer()->minValue(0)->placeholder('30'),
                    TextInput::make('credit_limit')->numeric()->prefix('GHS')->step('0.01')->placeholder('0.00'),
                    Textarea::make('billing_address')->rows(3)->placeholder('Billing address used for invoices and statements.'),
                    Textarea::make('physical_address')->rows(3)->placeholder('Primary service or head-office location.'),
                    TextInput::make('city')->maxLength(120)->placeholder('Accra'),
                    TextInput::make('region')->maxLength(120)->placeholder('Greater Accra'),
                    TextInput::make('country')->default('Ghana')->maxLength(120),
                    Textarea::make('notes')->rows(4)->columnSpanFull()->placeholder('Key onboarding notes, operational constraints, or internal reminders.'),
                ])->columns(2),
        ]);
    }
}
