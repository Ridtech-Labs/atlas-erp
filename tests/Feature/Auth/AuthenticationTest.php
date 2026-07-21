<?php

use App\Core\Shared\Enums\TenantStatus;
use App\Core\Shared\Enums\UserStatus;
use App\Models\User;
use Livewire\Volt\Volt;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response
        ->assertOk()
        ->assertSeeVolt('pages.auth.login');
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password');

    $component->call('login');

    $component
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
    expect($user->refresh()->last_login_at)->not->toBeNull()
        ->and($user->last_login_ip)->toBe('127.0.0.1');
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

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password');

    $component->call('login');

    $component
        ->assertHasErrors(['form.email'])
        ->assertNoRedirect();

    $this->assertGuest();
    expect($user->refresh()->last_login_at)->toBeNull();
});

test('suspended users can not authenticate', function () {
    $user = User::factory()->create([
        'status' => UserStatus::Suspended,
    ]);

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password');

    $component->call('login');

    $component
        ->assertHasErrors(['form.email'])
        ->assertNoRedirect();

    $this->assertGuest();
    expect($user->refresh()->last_login_at)->toBeNull();
});

test('users can not authenticate when their company is suspended', function () {
    $user = User::factory()->create();
    $user->tenant->update([
        'status' => TenantStatus::Suspended,
    ]);

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password');

    $component->call('login');

    $component
        ->assertHasErrors(['form.email'])
        ->assertNoRedirect();

    $this->assertGuest();
});

test('navigation menu can be rendered', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get('/dashboard');

    $response
        ->assertOk()
        ->assertSeeVolt('layout.navigation');
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
