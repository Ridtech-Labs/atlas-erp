<?php

use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Pages\Dashboard;
use App\Core\Administration\Filament\Resources\Tenants\Pages\ListTenants;
use App\Core\Administration\Filament\Resources\Tenants\TenantResource;
use App\Core\Shared\Enums\TenantStatus;
use App\Core\Tenancy\Services\SupportTenantWorkspaceService;
use Livewire\Livewire;

test('a platform super administrator can enter an active tenant workspace', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay']);
    $company = $this->company($tenant, ['name' => 'Kadmay', 'is_default' => true]);
    $user = $this->tenantUser($tenant, [], [RoleName::SuperAdministrator->value]);

    $this->actingAs($user)
        ->withSession(['active_company_id' => 999])
        ->post(route('atlas.support-workspace.enter', $tenant))
        ->assertRedirect('/admin')
        ->assertSessionHas('support_tenant_id', $tenant->getKey())
        ->assertSessionMissing('active_company_id');

    $this->actingAs($user)
        ->withSession(['support_tenant_id' => $tenant->getKey()])
        ->get('/admin')
        ->assertOk()
        ->assertSee('Kadmay')
        ->assertDontSee('Platform Administration')
        ->assertSee('Exit Workspace');

    expect(app(SupportTenantWorkspaceService::class)->canEnter($user, $tenant))->toBeFalse()
        ->and($company->tenant_id)->toBe($tenant->getKey());
});

test('workspace entry rejects inactive tenants and non-super administrators', function () {
    $this->seedAccessControl();

    $inactiveTenant = $this->tenant(['status' => TenantStatus::Inactive->value]);
    $activeTenant = $this->tenant(['name' => 'Kadmay']);
    $superAdministrator = $this->tenantUser($activeTenant, [], [RoleName::SuperAdministrator->value]);
    $companyAdministrator = $this->tenantUser($activeTenant, [], [RoleName::CompanyAdministrator->value]);

    $this->actingAs($superAdministrator)
        ->post(route('atlas.support-workspace.enter', $inactiveTenant))
        ->assertForbidden();

    $this->actingAs($companyAdministrator)
        ->post(route('atlas.support-workspace.enter', $activeTenant))
        ->assertForbidden();

    $this->actingAs($superAdministrator)
        ->post('/admin/tenants/999999/workspace')
        ->assertNotFound();
});

test('exiting a support workspace clears tenant and company context and restores the platform dashboard', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay']);
    $company = $this->company($tenant, ['name' => 'Kadmay']);
    $user = $this->tenantUser($tenant, [], [RoleName::SuperAdministrator->value]);

    $this->actingAs($user)
        ->withSession([
            'support_tenant_id' => $tenant->getKey(),
            'active_company_id' => $company->getKey(),
        ])
        ->post(route('atlas.support-workspace.exit'))
        ->assertRedirect('/admin')
        ->assertSessionMissing('support_tenant_id')
        ->assertSessionMissing('active_company_id');

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Platform Administration');
});

test('a super administrator cannot switch support tenants through a direct entry request', function () {
    $this->seedAccessControl();

    $tenantA = $this->tenant(['name' => 'Kadmay']);
    $tenantB = $this->tenant(['name' => 'Other Tenant']);
    $user = $this->tenantUser($tenantA, [], [RoleName::SuperAdministrator->value]);

    $this->actingAs($user)
        ->withSession(['support_tenant_id' => $tenantA->getKey()])
        ->post(route('atlas.support-workspace.enter', $tenantB))
        ->assertForbidden();
});

test('tenant users remain scoped to their own tenant and cannot enter or exit support workspaces', function () {
    $this->seedAccessControl();

    $tenantA = $this->tenant(['name' => 'Kadmay']);
    $tenantB = $this->tenant(['name' => 'Other Tenant']);
    $companyA = $this->company($tenantA, ['name' => 'Kadmay']);
    $user = $this->tenantUser($tenantA, [], [RoleName::CompanyAdministrator->value]);
    $user->companies()->sync([$companyA->getKey()]);

    $this->actingAs($user)
        ->withSession(['active_company_id' => $companyA->getKey()])
        ->post(route('atlas.support-workspace.enter', $tenantB))
        ->assertForbidden();

    $this->actingAs($user)
        ->withSession(['active_company_id' => $companyA->getKey()])
        ->post(route('atlas.support-workspace.exit'))
        ->assertForbidden();

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Kadmay')
        ->assertDontSee('Other Tenant');
});

test('an authenticated tenant user can enter only their own canonical workspace URL', function () {
    $this->seedAccessControl();

    $tenantA = $this->tenant(['name' => 'Kadmay', 'slug' => 'kadmay']);
    $tenantB = $this->tenant(['name' => 'Other Tenant', 'slug' => 'other-tenant']);
    $company = $this->company($tenantA, ['name' => 'Kadmay']);
    $user = $this->tenantUser($tenantA, [], [RoleName::CompanyAdministrator->value]);
    $user->companies()->sync([$company->getKey()]);

    $this->actingAs($user)
        ->withSession(['active_company_id' => $company->getKey()])
        ->get(route('atlas.tenant-workspace', $tenantA))
        ->assertRedirect('/admin');

    $this->actingAs($user)
        ->withSession(['active_company_id' => $company->getKey()])
        ->get(route('atlas.tenant-workspace', $tenantB))
        ->assertForbidden();
});

test('canonical workspace URLs reject inactive tenants and support administrators without an entered workspace', function () {
    $this->seedAccessControl();

    $inactive = $this->tenant(['slug' => 'inactive', 'status' => TenantStatus::Inactive->value]);
    $active = $this->tenant(['slug' => 'kadmay']);
    $tenantUser = $this->tenantUser($inactive, [], [RoleName::CompanyAdministrator->value]);
    $superAdministrator = $this->tenantUser($active, [], [RoleName::SuperAdministrator->value]);

    $this->actingAs($tenantUser)
        ->get(route('atlas.tenant-workspace', $inactive))
        ->assertNotFound();

    $this->actingAs($superAdministrator)
        ->get(route('atlas.tenant-workspace', $active))
        ->assertForbidden();
});

test('the tenant resource exposes workspace entry only to platform super administrators', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay']);
    $superAdministrator = $this->tenantUser($tenant, [], [RoleName::SuperAdministrator->value]);
    $companyAdministrator = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);

    $this->actingAs($superAdministrator)
        ->get(TenantResource::getUrl('index'))
        ->assertOk()
        ->assertSee('Enter Workspace');

    $this->actingAs($companyAdministrator)
        ->get(TenantResource::getUrl('index'))
        ->assertForbidden();
});

test('the tenant table entry action establishes support context and redirects to the workspace', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay']);
    $superAdministrator = $this->tenantUser($tenant, [], [RoleName::SuperAdministrator->value]);

    $this->actingAs($superAdministrator);

    Livewire::test(ListTenants::class)
        ->assertTableActionVisible('enterWorkspace', $tenant)
        ->callTableAction('enterWorkspace', $tenant)
        ->assertRedirect(Dashboard::getUrl());

    $this->assertSame($tenant->getKey(), session('support_tenant_id'));
    $this->assertNull(session('active_company_id'));
});
