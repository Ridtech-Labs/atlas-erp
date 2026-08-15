<?php

declare(strict_types=1);

use App\Administration\Enums\RoleName;

test('admin shell renders native filament user menu and brand area', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay']);
    $user = $this->tenantUser($tenant, [], [RoleName::SuperAdministrator->value]);

    $response = $this->actingAs($user)->get('/admin');

    $response
        ->assertOk()
        ->assertSee('Atlas ERP', escape: false)
        ->assertSee('fi-user-menu-trigger', escape: false)
        ->assertSee('Profile', escape: false)
        ->assertSee(route('filament.admin.auth.logout'), escape: false);
});

test('platform super administrator lands in platform context instead of tenant context', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay']);
    $user = $this->tenantUser($tenant, ['first_name' => 'Ridwan'], [RoleName::SuperAdministrator->value]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Platform Administration')
        ->assertDontSee('Kadmay ·')
        ->assertDontSee('Approval Queue');
});

test('filament admin logout invalidates the session and redirects to login', function () {
    $this->seedAccessControl();

    $user = $this->tenantUser($this->tenant(), [], [RoleName::SuperAdministrator->value]);

    $this->actingAs($user)
        ->post(route('filament.admin.auth.logout'))
        ->assertRedirect(route('filament.admin.auth.login'));

    $this->assertGuest();
});

test('create client page shows the authenticated users tenant context', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay']);
    $user = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);

    $this->actingAs($user)
        ->get('/admin/clients/create')
        ->assertOk()
        ->assertSee('Kadmay')
        ->assertDontSee('Atlas Demo Company');
});

test('multi company users receive a usable company selector', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay']);
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $user = $this->tenantUser($tenant, [], [RoleName::OperationsManager->value]);
    $user->companies()->sync([$companyA->getKey(), $companyB->getKey()]);
    session(['active_company_id' => $companyA->getKey()]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk()
        ->assertSee('name="company_id"', false)
        ->assertSee('Kadmay Logistics')
        ->assertSee('Kadmay Marine');
});

test('platform super administrator can not open tenant client creation without support tenant access', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay']);
    $user = $this->tenantUser($tenant, [], [RoleName::SuperAdministrator->value]);

    $this->actingAs($user)
        ->get('/admin/clients/create')
        ->assertForbidden();
});
