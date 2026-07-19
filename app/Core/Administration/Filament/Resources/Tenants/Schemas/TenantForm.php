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
                    ->schema([
                        TextInput::make('name')->required()->maxLength(255),
                        TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                        TextInput::make('email')->email()->maxLength(255),
                        TextInput::make('phone')->maxLength(30),
                        FileUpload::make('logo_path')->directory('company-logos')->image(),
                        Textarea::make('address')->rows(3)->columnSpanFull(),
                        TextInput::make('city')->maxLength(120),
                        TextInput::make('country')->maxLength(120),
                        TextInput::make('timezone')->required()->maxLength(100),
                        TextInput::make('currency')->required()->maxLength(10),
                        Select::make('status')
                            ->options(collect(TenantStatus::cases())->mapWithKeys(fn (TenantStatus $status) => [$status->value => ucfirst($status->value)])->all())
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
