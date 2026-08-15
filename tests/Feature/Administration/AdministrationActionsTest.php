<?php

use App\Administration\Actions\Companies\CreateCompanyAction;
use App\Administration\Actions\Companies\UpdateCompanyAction;
use App\Administration\Actions\Users\CreateUserAction;
use App\Administration\Actions\Users\UpdateUserAction;
use App\Administration\Actions\Users\UpdateUserStatusAction;
use App\Administration\Enums\RoleName;
use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\Users\UserResource;
use App\Core\Shared\Enums\UserStatus;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Tenancy\Support\ActiveCompanyResolver;
use App\CRM\Models\Client;
use App\Models\User;
use Livewire\Volt\Volt;
use Spatie\Activitylog\Models\Activity;

test('company creation is logged', function () {
    $this->seedAccessControl();
    $actor = $this->actingAsRole(RoleName::SuperAdministrator->value);

    $tenant = app(CreateCompanyAction::class)->execute([
        'name' => 'New Company',
        'slug' => 'new-company',
        'timezone' => 'Africa/Accra',
        'currency' => 'GHS',
    ], $actor);

    expect($tenant->name)->toBe('New Company')
        ->and(Activity::query()->where('event', 'company.created')->exists())->toBeTrue();
});

test('company update is logged', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $actor = $this->actingAsRole(RoleName::SuperAdministrator->value, $tenant);

    $updated = app(UpdateCompanyAction::class)->execute($tenant, [
        'name' => 'Updated Company',
    ], $actor);

    expect($updated->name)->toBe('Updated Company')
        ->and(Activity::query()->where('event', 'company.updated')->exists())->toBeTrue();
});

test('company administrator can manage users in their company', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $user = app(CreateUserAction::class)->execute([
        'tenant_id' => $tenant->getKey(),
        'first_name' => 'Grace',
        'last_name' => 'Admin',
        'email' => 'grace@example.test',
        'password' => 'password',
    ], [RoleName::StandardUser->value], $actor);

    $updated = app(UpdateUserAction::class)->execute($user, [
        'first_name' => 'Grace Updated',
    ], [RoleName::StandardUser->value], $actor);

    expect($updated->first_name)->toBe('Grace Updated')
        ->and($updated->tenant_id)->toBe($tenant->getKey())
        ->and($updated->companies()->pluck('companies.id')->all())->toContain($company->getKey());
});

test('company administrator can not assign super administrator role', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $actor = $this->actingAsCompanyAdministrator($tenant);

    expect(fn () => app(CreateUserAction::class)->execute([
        'tenant_id' => $tenant->getKey(),
        'first_name' => 'Blocked',
        'last_name' => 'User',
        'email' => 'blocked@example.test',
        'password' => 'password',
    ], [RoleName::SuperAdministrator->value], $actor))
        ->toThrow(BusinessException::class);
});

test('standard users can not manage users', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $actor = $this->actingAsRole(RoleName::StandardUser->value, $tenant);

    expect(fn () => app(CreateUserAction::class)->execute([
        'tenant_id' => $tenant->getKey(),
        'first_name' => 'Blocked',
        'last_name' => 'User',
        'email' => 'standard-blocked@example.test',
        'password' => 'password',
    ], [RoleName::StandardUser->value], $actor))
        ->toThrow(BusinessException::class);
});

test('user status changes are logged', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $user = $this->tenantUser($tenant, [], [RoleName::StandardUser->value]);

    $updated = app(UpdateUserStatusAction::class)->execute($user, UserStatus::Suspended, $actor);

    expect($updated->status)->toBe(UserStatus::Suspended)
        ->and(Activity::query()->where('event', 'user.status_changed')->exists())->toBeTrue();
});

test('role assignment is applied during user creation', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $user = app(CreateUserAction::class)->execute([
        'tenant_id' => $tenant->getKey(),
        'first_name' => 'Role',
        'last_name' => 'Assigned',
        'email' => 'role-assigned@example.test',
        'password' => 'password',
    ], [RoleName::StandardUser->value], $actor);

    expect($user->hasRole(RoleName::StandardUser->value))->toBeTrue()
        ->and($user->companies()->pluck('companies.id')->all())->toContain($company->getKey());
});

test('administrator created operations manager can log in immediately with scoped permissions', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $otherCompany = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $operationsManager = app(CreateUserAction::class)->execute([
        'tenant_id' => $tenant->getKey(),
        'first_name' => 'Eben',
        'last_name' => 'Amponsah',
        'email' => 'eben@kadmay.test',
        'password' => 'password',
        'status' => UserStatus::Active->value,
    ], [RoleName::OperationsManager->value], $actor);

    expect($operationsManager->status)->toBe(UserStatus::Active)
        ->and($operationsManager->email_verified_at)->not->toBeNull()
        ->and($operationsManager->hasRole(RoleName::OperationsManager->value))->toBeTrue()
        ->and($operationsManager->hasRole(RoleName::CompanyAdministrator->value))->toBeFalse()
        ->and($operationsManager->companies()->pluck('companies.id')->all())->toBe([$company->getKey()]);

    $authorizedCompanies = app(AdministrationAccessService::class)
        ->authorizedCompanies($operationsManager->fresh())
        ->pluck('name')
        ->all();

    expect($authorizedCompanies)->toBe([$company->name]);

    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();

    $component = Volt::test('pages.auth.login')
        ->set('form.email', 'eben@kadmay.test')
        ->set('form.password', 'password');

    $component->call('login');

    $component
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticatedAs($operationsManager->fresh());

    expect(app(ActiveCompanyResolver::class)->resolveFor($operationsManager->fresh())?->getKey())
        ->toBe($company->getKey());

    $this->actingAs($operationsManager->fresh());

    $this->get('/admin')
        ->assertOk()
        ->assertSee($company->name)
        ->assertDontSee('Company Selection Required')
        ->assertDontSee('Choose an authorized company before viewing operational data.');

    expect(session('active_company_id'))->toBe($company->getKey());

    $this->get('/dashboard')->assertOk();

    expect($operationsManager->fresh()->can('users.create'))->toBeFalse()
        ->and($operationsManager->fresh()->can('users.update'))->toBeFalse()
        ->and($operationsManager->fresh()->can('jobs.create'))->toBeTrue();

    $this->post(route('atlas.company-context.switch'), [
        'company_id' => $otherCompany->getKey(),
    ])->assertForbidden();

    $otherClient = Client::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $otherCompany->getKey(),
    ]);

    expect($operationsManager->fresh()->can('view', $otherClient))->toBeFalse();
});

test('create user form displays the active company and does not expose an editable company field for company administrators', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    $this->get(UserResource::getUrl('create'))
        ->assertOk()
        ->assertSee('User will be created under')
        ->assertSee('Kadmay')
        ->assertDontSee('name="company_id"', false);
});

test('company administrator can not create user for another company', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $actor->companies()->sync([$companyA->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    expect(fn () => app(CreateUserAction::class)->execute([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $companyB->getKey(),
        'first_name' => 'Blocked',
        'last_name' => 'Marine',
        'email' => 'blocked-marine@example.test',
        'password' => 'password',
    ], [RoleName::OperationsManager->value], $actor))
        ->toThrow(BusinessException::class, 'You cannot create users for another company.');
});

test('company administrator user list only shows users in the active company', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $actor->companies()->sync([$companyA->getKey(), $companyB->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    $logisticsUser = User::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'first_name' => 'Logistics',
        'last_name' => 'User',
        'email' => 'logistics-user@example.test',
    ]);
    $logisticsUser->companies()->sync([$companyA->getKey()]);
    $logisticsUser->syncRoles([RoleName::OperationsManager->value]);

    $marineUser = User::factory()->create([
        'tenant_id' => $tenant->getKey(),
        'first_name' => 'Marine',
        'last_name' => 'User',
        'email' => 'marine-user@example.test',
    ]);
    $marineUser->companies()->sync([$companyB->getKey()]);
    $marineUser->syncRoles([RoleName::OperationsManager->value]);

    $this->get(UserResource::getUrl('index'))
        ->assertOk()
        ->assertSee('logistics-user@example.test')
        ->assertDontSee('marine-user@example.test');
});

test('platform administrator can still create a user for an explicitly selected company', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay Holdings']);
    $company = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $platformAdmin = $this->actingAsRole(RoleName::SuperAdministrator->value, $tenant);

    $user = app(CreateUserAction::class)->execute([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $company->getKey(),
        'first_name' => 'Platform',
        'last_name' => 'Created',
        'email' => 'platform-created@example.test',
        'password' => 'password',
    ], [RoleName::StandardUser->value], $platformAdmin);

    expect($user->tenant_id)->toBe($tenant->getKey())
        ->and($user->companies()->pluck('companies.id')->all())->toContain($company->getKey());
});
