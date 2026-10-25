<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Tenants\Schemas;

use App\Core\Shared\Enums\TenantStatus;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'lg' => 2,
                ])
                    ->columnSpanFull()
                    ->extraAttributes(['class' => 'gap-6'])
                    ->schema([
                        Group::make([
                            Section::make('Tenant account')
                                ->extraAttributes(['class' => 'order-1'])
                                ->description('Define the Atlas customer account identity and platform account status.')
                                ->schema([
                                    TextInput::make('name')->required()->maxLength(255)->placeholder('Kadmay Holdings'),
                                    TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255)->placeholder('kadmay-holdings'),
                                    Select::make('status')
                                        ->options(collect(TenantStatus::cases())->mapWithKeys(fn (TenantStatus $status) => [$status->value => $status->label()])->all())
                                        ->required(),
                                ])
                                ->columns(2),
                            Section::make('Tenant branding')
                                ->extraAttributes(['class' => 'order-3'])
                                ->description('Optional public identity for this tenant’s login entry and operational workspace. Atlas branding is used when these values are blank.')
                                ->schema([
                                    TextInput::make('brand_display_name')
                                        ->label('Display name')
                                        ->maxLength(255)
                                        ->placeholder('Kadmay'),
                                    ColorPicker::make('brand_primary_color')
                                        ->label('Primary brand color')
                                        ->hexColor()
                                        ->helperText('Used for tenant workspace accents. Leave blank to retain Atlas blue.'),
                                    FileUpload::make('brand_logo_path')
                                        ->label('Brand logo')
                                        ->disk('public')
                                        ->directory(fn (Get $get): string => 'tenant-branding/'.Str::slug((string) ($get('slug') ?: 'unassigned')).'/logos')
                                        ->image()
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                        ->maxSize(2048)
                                        ->helperText('PNG, JPEG, or WebP up to 2 MB. This logo is public because it appears before sign-in.'),
                                    FileUpload::make('brand_login_hero_path')
                                        ->label('Login hero image')
                                        ->disk('public')
                                        ->directory(fn (Get $get): string => 'tenant-branding/'.Str::slug((string) ($get('slug') ?: 'unassigned')).'/hero')
                                        ->image()
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                        ->maxSize(6144)
                                        ->helperText('Optional PNG, JPEG, or WebP up to 6 MB. A tenant image is shown only on that tenant’s public login entry.'),
                                ])
                                ->columns(2),
                        ])
                            ->extraAttributes(['class' => 'contents lg:block lg:space-y-6']),
                        Section::make('Default company profile')
                            ->extraAttributes(['class' => 'order-2 lg:order-none'])
                            ->description('Store the operational business identity for the tenant’s default company.')
                            ->schema([
                                TextInput::make('email')->email()->maxLength(255)->placeholder('info@company.com'),
                                TextInput::make('phone')->maxLength(30)->placeholder('+233 20 000 0000'),
                                FileUpload::make('logo_path')->directory('company-logos')->image(),
                                Textarea::make('address')->rows(3)->columnSpanFull()->placeholder('Registered office or operating headquarters.'),
                                TextInput::make('city')->maxLength(120)->placeholder('Accra'),
                                TextInput::make('country')->maxLength(120)->placeholder('Ghana'),
                                TextInput::make('timezone')->required()->maxLength(100)->placeholder('Africa/Accra'),
                                TextInput::make('currency')->required()->maxLength(10)->placeholder('GHS'),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
