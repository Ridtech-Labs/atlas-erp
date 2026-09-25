<?php

use App\Administration\Enums\RoleName;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

test('production bootstrap creates an explicit company administrator without demo data', function () {
    $this->artisan('atlas:bootstrap-production', [
        '--tenant-name' => 'Production Account',
        '--tenant-slug' => 'production-account',
        '--company-name' => 'Production Operations',
        '--company-code' => 'PROD-001',
        '--first-name' => 'Initial',
        '--last-name' => 'Administrator',
        '--email' => 'admin@production.test',
    ])
        ->expectsQuestion('Initial administrator password', 'secure-password')
        ->expectsQuestion('Confirm initial administrator password', 'secure-password')
        ->expectsConfirmation('Create this production tenant, company, and Company Administrator?', 'yes')
        ->assertSuccessful();

    $tenant = Tenant::query()->where('slug', 'production-account')->firstOrFail();
    $company = Company::query()->where('tenant_id', $tenant->getKey())->firstOrFail();
    $user = User::query()->where('email', 'admin@production.test')->firstOrFail();

    expect($company->is_default)->toBeTrue()
        ->and($user->tenant_id)->toBe($tenant->getKey())
        ->and($user->companies()->whereKey($company->getKey())->exists())->toBeTrue()
        ->and($user->hasRole(RoleName::CompanyAdministrator->value))->toBeTrue();
});

test('production bootstrap refuses duplicate tenant or administrator details', function () {
    Tenant::factory()->create(['slug' => 'existing-account']);

    $this->artisan('atlas:bootstrap-production', [
        '--tenant-name' => 'Existing Account',
        '--tenant-slug' => 'existing-account',
        '--company-name' => 'Existing Operations',
        '--company-code' => 'EXIST-001',
        '--first-name' => 'Initial',
        '--last-name' => 'Administrator',
        '--email' => 'admin@existing.test',
    ])
        ->expectsQuestion('Initial administrator password', 'secure-password')
        ->expectsQuestion('Confirm initial administrator password', 'secure-password')
        ->expectsConfirmation('Create this production tenant, company, and Company Administrator?', 'yes')
        ->expectsOutput('A tenant with this slug or a user with this email already exists. No records were created.')
        ->assertFailed();

    expect(User::query()->where('email', 'admin@existing.test')->exists())->toBeFalse();
});

test('the local demonstration seeder refuses to run in production', function () {
    $environment = app()->environment();
    app()->instance('env', 'production');

    try {
        expect(fn () => app(DatabaseSeeder::class)->run())
            ->toThrow(LogicException::class, 'must not run in production');
    } finally {
        app()->instance('env', $environment);
    }
});
