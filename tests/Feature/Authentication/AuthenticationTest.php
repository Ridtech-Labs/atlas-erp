<?php

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Pages\Auth\Login as AdminLogin;
use App\Core\Shared\Enums\TenantStatus;
use App\Core\Shared\Enums\UserStatus;
use App\Models\User;
use Filament\PanelRegistry;
use Livewire\Livewire;
use Livewire\Volt\Volt;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response
        ->assertOk()
        ->assertSeeVolt('pages.auth.login');
});

test('admin login presents Atlas identity without pre-auth tenant context', function () {
    $this->get('/admin/login')
        ->assertOk()
        ->assertSee('Atlas ERP')
        ->assertSee('Operations &amp; Commercial Management', false)
        ->assertSee('Welcome back')
        ->assertSee('Sign in to continue to your workspace.')
        ->assertSee('Run your operations')
        ->assertSee('from one place.')
        ->assertSee('Jobs, fleet, personnel and commercial workflows connected in one workspace.')
        ->assertSee('images/auth/atlas-hero.png')
        ->assertSee('fi-ac-btn-action')
        ->assertSee('Secure access for authorized users only.')
        ->assertDontSee('Kadmay')
        ->assertDontSee('Tenant workspace')
        ->assertDontSee('Select a company');
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password');

    $component->call('login');

    $component
        ->assertHasNoErrors()
        ->assertRedirect('/admin');

    $this->assertAuthenticated();
    expect($user->refresh()->last_login_at)->not->toBeNull()
        ->and($user->last_login_ip)->toBe('127.0.0.1');
});

test('users can authenticate through the Atlas admin login page', function () {
    $user = User::factory()->create();

    Livewire::test(AdminLogin::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'password',
        ])
        ->call('authenticate')
        ->assertHasNoFormErrors()
        ->assertRedirect('/admin');

    $this->assertAuthenticatedAs($user);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'wrong-password');

    $component->call('login');

    $component
        ->assertHasErrors()
        ->assertNoRedirect();

    $this->assertGuest();
});

test('inactive users can not authenticate', function () {
    $user = User::factory()->create([
        'status' => UserStatus::Inactive,
    ]);

    Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password')
        ->call('login')
        ->assertHasErrors(['form.email'])
        ->assertNoRedirect();

    $this->assertGuest();
    expect($user->refresh()->last_login_at)->toBeNull()
        ->and($user->last_login_ip)->toBeNull();
});

test('suspended users can not authenticate', function () {
    $user = User::factory()->create([
        'status' => UserStatus::Suspended,
    ]);

    Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password')
        ->call('login')
        ->assertHasErrors(['form.email'])
        ->assertNoRedirect();

    $this->assertGuest();
});

test('users belonging to suspended companies can not authenticate', function () {
    $tenant = $this->tenant([
        'status' => TenantStatus::Suspended,
    ]);

    $user = $this->tenantUser($tenant);

    Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password')
        ->call('login')
        ->assertHasErrors(['form.email'])
        ->assertNoRedirect();

    $this->assertGuest();
});

test('inactive users can not access the Filament admin panel', function () {
    $user = User::factory()->create([
        'status' => UserStatus::Inactive,
    ]);

    $panel = app(PanelRegistry::class)->get('admin');

    expect($user->canAccessPanel($panel))->toBeFalse();
});

test('legacy dashboard URL redirects to the Filament workspace', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get('/dashboard');

    $response->assertRedirect('/admin');
});

test('users can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Volt::test('layout.navigation');

    $component->call('logout');

    $component
        ->assertHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
});

test('tenant users logout to their verified tenant login entry', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['slug' => 'kadmay']);
    $company = $this->company($tenant, ['is_default' => true]);
    $user = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $user->companies()->sync([$company->getKey()]);

    $this->actingAs($user)
        ->withSession(['active_company_id' => $company->getKey()])
        ->post(route('filament.admin.auth.logout'))
        ->assertRedirect(route('atlas.tenant-login', $tenant));

    $this->assertGuest();
});

test('platform super administrators logout to the generic Atlas login', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['slug' => 'kadmay']);
    $user = $this->tenantUser($tenant, [], [RoleName::SuperAdministrator->value]);

    $this->actingAs($user)
        ->post(route('filament.admin.auth.logout'))
        ->assertRedirect('/admin/login');

    $this->assertGuest();
});
