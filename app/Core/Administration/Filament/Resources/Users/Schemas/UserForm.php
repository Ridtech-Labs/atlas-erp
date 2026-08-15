<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Users\Schemas;

use App\Administration\Enums\RoleName;
use App\Administration\Services\AdministrationAccessService;
use App\Core\Shared\Enums\UserStatus;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        $access = app(AdministrationAccessService::class);

        return $schema
            ->components([
                Section::make('User account')
                    ->description('Create an internal user with the right company access, role, and workspace identity.')
                    ->schema([
                        Placeholder::make('company_context')
                            ->label(fn (string $operation): string => $operation === 'create' ? 'User will be created under' : 'Company')
                            ->content(fn (Get $get, ?User $record, string $operation): string => self::resolveCompanyContextLabel($access, $get, $record, $operation))
                            ->columnSpanFull(),
                        Select::make('tenant_id')
                            ->label('Tenant account')
                            ->options(fn () => Tenant::query()->orderBy('name')->pluck('name', 'id')->all())
                            ->default(auth()->user()?->tenant_id)
                            ->helperText('Platform administrators can choose which tenant account this user belongs to.')
                            ->visible(fn (string $operation): bool => $operation === 'create' && (auth()->user()?->hasRole(RoleName::SuperAdministrator->value) ?? false))
                            ->live()
                            ->required(),
                        Select::make('company_id')
                            ->label('Company')
                            ->options(fn (Get $get): array => self::companyOptionsForTenant($get('tenant_id')))
                            ->helperText('This company membership will be assigned automatically at creation time.')
                            ->visible(fn (string $operation): bool => $operation === 'create' && (auth()->user()?->hasRole(RoleName::SuperAdministrator->value) ?? false))
                            ->required(fn (string $operation): bool => $operation === 'create' && (auth()->user()?->hasRole(RoleName::SuperAdministrator->value) ?? false)),
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
                                $user = auth()->user();

                                return collect(RoleName::values())
                                    ->filter(fn (string $role): bool => $user instanceof User && app(AdministrationAccessService::class)->canManageRole($user, $role))
                                    ->mapWithKeys(fn (string $role) => [$role => $role])
                                    ->all();
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

    private static function resolveCompanyContextLabel(AdministrationAccessService $access, Get $get, ?User $record, string $operation): string
    {
        if ($record instanceof User) {
            $names = self::companyNames($record->companies);

            return $names !== '' ? $names : 'No company membership assigned';
        }

        $user = auth()->user();

        if ($operation === 'create' && $user instanceof User && ! $access->isSuperAdministrator($user)) {
            $company = $access->activeCompany($user);

            return $company instanceof Company ? $company->name : 'No active company selected';
        }

        if ($operation === 'create' && filled($get('company_id'))) {
            return Company::query()->whereKey((int) $get('company_id'))->value('name') ?? 'Select a company';
        }

        return 'Select a company';
    }

    /**
     * @return array<int, string>
     */
    private static function companyOptionsForTenant(mixed $tenantId): array
    {
        if (! filled($tenantId)) {
            return [];
        }

        return Company::query()
            ->where('tenant_id', (int) $tenantId)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }

    /**
     * @param  Collection<int, Company>  $companies
     */
    private static function companyNames(Collection $companies): string
    {
        return $companies
            ->pluck('name')
            ->filter()
            ->implode(', ');
    }
}
