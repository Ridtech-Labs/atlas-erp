<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Administration\Enums\RoleName;
use App\Core\Shared\Enums\TenantStatus;
use App\Core\Shared\Enums\UserStatus;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BootstrapProductionCommand extends Command
{
    protected $signature = 'atlas:bootstrap-production
        {--tenant-name= : Customer account name}
        {--tenant-slug= : Unique customer account slug}
        {--company-name= : Operating company name}
        {--company-code= : Unique company code within the customer account}
        {--first-name= : Initial administrator first name}
        {--last-name= : Initial administrator last name}
        {--email= : Initial administrator email address}';

    protected $description = 'Create one production tenant, company, and initial Company Administrator without demo data';

    public function handle(): int
    {
        $tenantName = trim((string) ($this->option('tenant-name') ?: $this->ask('Tenant name')));
        $tenantSlug = Str::slug((string) ($this->option('tenant-slug') ?: $this->ask('Tenant slug')));
        $companyName = trim((string) ($this->option('company-name') ?: $this->ask('Company name')));
        $companyCode = strtoupper(trim((string) ($this->option('company-code') ?: $this->ask('Company code'))));
        $firstName = trim((string) ($this->option('first-name') ?: $this->ask('Initial administrator first name')));
        $lastName = trim((string) ($this->option('last-name') ?: $this->ask('Initial administrator last name')));
        $email = strtolower(trim((string) ($this->option('email') ?: $this->ask('Initial administrator email'))));
        $password = (string) $this->secret('Initial administrator password');
        $confirmation = (string) $this->secret('Confirm initial administrator password');

        if (in_array('', [$tenantName, $tenantSlug, $companyName, $companyCode, $firstName, $lastName, $email, $password], true)
            || ! filter_var($email, FILTER_VALIDATE_EMAIL)
            || $password !== $confirmation) {
            $this->error('Provide complete, valid details and matching passwords. No records were created.');

            return self::FAILURE;
        }

        if (! $this->confirm('Create this production tenant, company, and Company Administrator?', false)) {
            $this->warn('Production bootstrap cancelled.');

            return self::SUCCESS;
        }

        if (Tenant::query()->where('slug', $tenantSlug)->exists()
            || User::query()->where('email', $email)->exists()) {
            $this->error('A tenant with this slug or a user with this email already exists. No records were created.');

            return self::FAILURE;
        }

        app(RoleAndPermissionSeeder::class)->run();

        DB::transaction(function () use ($tenantName, $tenantSlug, $companyName, $companyCode, $firstName, $lastName, $email, $password): void {
            $tenant = Tenant::query()->create([
                'uuid' => (string) Str::uuid(),
                'name' => $tenantName,
                'slug' => $tenantSlug,
                'timezone' => config('app.timezone'),
                'currency' => 'GHS',
                'status' => TenantStatus::Active->value,
            ]);

            $company = Company::query()->create([
                'uuid' => (string) Str::uuid(),
                'tenant_id' => $tenant->getKey(),
                'name' => $companyName,
                'legal_name' => $companyName,
                'code' => $companyCode,
                'status' => TenantStatus::Active->value,
                'currency' => 'GHS',
                'is_default' => true,
            ]);

            $user = User::query()->create([
                'uuid' => (string) Str::uuid(),
                'tenant_id' => $tenant->getKey(),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'status' => UserStatus::Active->value,
                'email_verified_at' => now(),
                'password' => Hash::make($password),
            ]);

            $user->companies()->attach($company->getKey());
            $user->assignRole(RoleName::CompanyAdministrator->value);
        });

        $this->info('Production bootstrap completed. Store the administrator password in an approved secret manager.');

        return self::SUCCESS;
    }
}
