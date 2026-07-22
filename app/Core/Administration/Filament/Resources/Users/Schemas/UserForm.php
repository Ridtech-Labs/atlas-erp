<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Users\Schemas;

use App\Administration\Enums\RoleName;
use App\Core\Shared\Enums\UserStatus;
use App\Core\Tenancy\Models\Tenant;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User account')
                    ->description('Create an internal user with the right company access, role, and workspace identity.')
                    ->schema([
                        Select::make('tenant_id')
                            ->label('Company')
                            ->options(fn () => Tenant::query()->orderBy('name')->pluck('name', 'id')->all())
                            ->default(auth()->user()?->tenant_id)
                            ->helperText('Company Administrators stay locked to their own company workspace.')
                            ->visible(fn () => auth()->user()?->hasRole(RoleName::SuperAdministrator->value))
                            ->required(),
                        TextInput::make('first_name')->required()->maxLength(255)->placeholder('Ridwan'),
                        TextInput::make('last_name')->required()->maxLength(255)->placeholder('Kadri'),
                        TextInput::make('email')->required()->email()->unique(ignoreRecord: true)->placeholder('name@company.com'),
                        TextInput::make('phone')->maxLength(30)->placeholder('+233 20 000 0000'),
                        FileUpload::make('avatar_path')->directory('user-avatars')->image(),
                        Select::make('status')
                            ->options(collect(UserStatus::cases())->mapWithKeys(fn (UserStatus $status) => [$status->value => $status->label()])->all())
                            ->required(),
                        Select::make('roles')
                            ->multiple()
                            ->options(function () {
                                $roles = collect(RoleName::values());

                                if (! auth()->user()?->hasRole(RoleName::SuperAdministrator->value)) {
                                    $roles = $roles->reject(fn (string $role) => $role === RoleName::SuperAdministrator->value);
                                }

                                return $roles->mapWithKeys(fn (string $role) => [$role => $role])->all();
                            })
                            ->helperText('Available roles are limited by your own administrative authority.')
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->placeholder(fn (string $operation): string => $operation === 'create' ? 'Set a secure password' : 'Leave blank to keep the current password')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->minLength(8),
                        TextInput::make('password_confirmation')
                            ->password()
                            ->same('password')
                            ->required(fn (Get $get): bool => filled($get('password'))),
                    ])
                    ->columns(2),
            ]);
    }
}
