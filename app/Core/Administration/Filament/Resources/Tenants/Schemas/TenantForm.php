<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Tenants\Schemas;

use App\Core\Shared\Enums\TenantStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Company details')
                    ->description('Define the tenant workspace identity, operating locale, and basic company contact details.')
                    ->schema([
                        TextInput::make('name')->required()->maxLength(255)->placeholder('Kadmay Global Limited'),
                        TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255)->placeholder('kadmay-global'),
                        TextInput::make('email')->email()->maxLength(255)->placeholder('info@company.com'),
                        TextInput::make('phone')->maxLength(30)->placeholder('+233 20 000 0000'),
                        FileUpload::make('logo_path')->directory('company-logos')->image(),
                        Textarea::make('address')->rows(3)->columnSpanFull()->placeholder('Registered office or operating headquarters.'),
                        TextInput::make('city')->maxLength(120)->placeholder('Accra'),
                        TextInput::make('country')->maxLength(120)->placeholder('Ghana'),
                        TextInput::make('timezone')->required()->maxLength(100)->placeholder('Africa/Accra'),
                        TextInput::make('currency')->required()->maxLength(10)->placeholder('GHS'),
                        Select::make('status')
                            ->options(collect(TenantStatus::cases())->mapWithKeys(fn (TenantStatus $status) => [$status->value => $status->label()])->all())
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
