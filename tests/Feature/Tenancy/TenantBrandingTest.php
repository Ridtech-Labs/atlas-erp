<?php

use App\Administration\Actions\Companies\UpdateCompanyAction;
use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Resources\Tenants\TenantResource;
use App\Core\Shared\Enums\TenantStatus;
use App\Core\Tenancy\Services\TenantBrandingService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

test('generic Atlas login remains unbranded', function () {
    $this->get('/admin/login')
        ->assertOk()
        ->assertSee('Atlas ERP')
        ->assertDontSee('Kadmay')
        ->assertDontSee('atlas-login-tenant-brand-logo', false);
});

test('an active tenant login entry resolves only public tenant branding', function () {
    Storage::fake('public');
    Storage::disk('public')->put('tenant-branding/kadmay/logos/kadmay.png', 'logo');
    Storage::disk('public')->put('tenant-branding/kadmay/hero/industrial.webp', 'hero');

    $tenant = $this->tenant([
        'name' => 'Kadmay Holdings',
        'slug' => 'kadmay',
        'brand_display_name' => 'Kadmay',
        'brand_primary_color' => '#6B21A8',
        'brand_logo_path' => 'tenant-branding/kadmay/logos/kadmay.png',
        'brand_login_hero_path' => 'tenant-branding/kadmay/hero/industrial.webp',
    ]);

    $this->get(route('atlas.tenant-login', $tenant))
        ->assertRedirect('/admin/login?tenant=kadmay');

    $this->get('/admin/login?tenant=kadmay')
        ->assertOk()
        ->assertSee('Welcome to Kadmay')
        ->assertSee('Powered by Atlas ERP')
        ->assertSee('tenant-branding/kadmay/logos/kadmay.png')
        ->assertSee('tenant-branding/kadmay/hero/industrial.webp')
        ->assertSee('--atlas-color-action-primary: #6B21A8')
        ->assertSee('--atlas-login-button-background: #6B21A8')
        ->assertSee('atlas-login-tenant-brand-logo', false);
});

test('unknown and inactive tenant login entries are rejected', function () {
    $inactive = $this->tenant([
        'slug' => 'inactive-kadmay',
        'status' => TenantStatus::Inactive,
    ]);

    $this->get('/login/unknown-tenant')->assertNotFound();
    $this->get(route('atlas.tenant-login', $inactive))->assertNotFound();
    $this->get('/admin/login?tenant=inactive-kadmay')->assertNotFound();
});

test('tenant branding applies only to the authenticated operational workspace', function () {
    $this->seedAccessControl();
    Storage::fake('public');
    Storage::disk('public')->put('tenant-branding/kadmay/logos/kadmay.png', 'logo');

    $tenantA = $this->tenant([
        'name' => 'Kadmay',
        'slug' => 'kadmay',
        'brand_display_name' => 'Kadmay Operations',
        'brand_primary_color' => '#6B21A8',
        'brand_logo_path' => 'tenant-branding/kadmay/logos/kadmay.png',
    ]);
    $companyA = $this->company($tenantA, ['name' => 'Kadmay', 'is_default' => true]);
    $tenantB = $this->tenant([
        'name' => 'Other Customer',
        'slug' => 'other-customer',
        'brand_display_name' => 'Other Customer',
        'brand_primary_color' => '#6B21A8',
    ]);
    $user = $this->tenantUser($tenantA, [], [RoleName::CompanyAdministrator->value]);
    $user->companies()->sync([$companyA->getKey()]);

    $this->actingAs($user)
        ->withSession(['active_company_id' => $companyA->getKey()])
        ->get('/admin?tenant='.$tenantB->slug)
        ->assertOk()
        ->assertSee('Kadmay Operations')
        ->assertSee('tenant-branding/kadmay/logos/kadmay.png')
        ->assertSee('atlas-sidebar-navigation-tenant-identity', false)
        ->assertDontSee('atlas-sidebar-tenant-identity', false)
        ->assertDontSee('atlas-sidebar-tenant-logo', false)
        ->assertSee('--atlas-color-action-primary: #6B21A8')
        ->assertDontSee('Other Customer');
});

test('the platform sidebar does not render tenant identity branding', function () {
    $this->seedAccessControl();
    Storage::fake('public');
    Storage::disk('public')->put('tenant-branding/kadmay/logos/kadmay.png', 'logo');

    $tenant = $this->tenant([
        'name' => 'Kadmay',
        'slug' => 'kadmay',
        'brand_display_name' => 'Kadmay Global Limited',
        'brand_logo_path' => 'tenant-branding/kadmay/logos/kadmay.png',
    ]);
    $platformUser = $this->tenantUser($tenant, [], [RoleName::SuperAdministrator->value]);

    $this->actingAs($platformUser)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Platform Administration')
        ->assertDontSee('atlas-sidebar-navigation-tenant-identity', false)
        ->assertDontSee('Kadmay Global Limited');
});

test('missing tenant branding assets fall back without generating a broken public URL', function () {
    $tenant = $this->tenant([
        'name' => 'Kadmay',
        'slug' => 'kadmay',
        'brand_logo_path' => 'tenant-branding/kadmay/logos/missing.png',
    ]);

    $this->get('/admin/login?tenant=kadmay')
        ->assertOk()
        ->assertSee('images/auth/atlas-hero.png')
        ->assertDontSee('tenant-branding/kadmay/logos/missing.png');
});

test('tenant branding assets use the public disk configured application URL', function () {
    Storage::fake('public', ['url' => 'http://atlas-erp.test/storage']);

    $path = 'tenant-branding/kadmay/logos/kadmay.webp';
    Storage::disk('public')->put($path, 'logo');
    $tenant = $this->tenant([
        'name' => 'Kadmay',
        'slug' => 'kadmay',
        'brand_logo_path' => $path,
    ]);

    expect(app(TenantBrandingService::class)->forTenant($tenant)?->logoUrl)
        ->toBe('http://atlas-erp.test/storage/'.$path);
});

test('missing or invalid tenant branding falls back safely and server-side configuration rejects unsafe asset paths', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay', 'slug' => 'kadmay']);
    $actor = $this->tenantUser($tenant, [], [RoleName::SuperAdministrator->value]);

    $this->get('/admin/login?tenant=kadmay')
        ->assertOk()
        ->assertSee('Atlas ERP')
        ->assertSee('Welcome to Kadmay')
        ->assertSee('--atlas-color-action-primary: #2B4EFF');

    app(UpdateCompanyAction::class)->execute($tenant, [
        'name' => 'Kadmay',
        'brand_primary_color' => '#not-a-color',
    ], $actor);
})->throws(ValidationException::class);

test('valid tenant branding configuration persists through the authorized action', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay', 'slug' => 'kadmay']);
    $actor = $this->tenantUser($tenant, [], [RoleName::SuperAdministrator->value]);

    app(UpdateCompanyAction::class)->execute($tenant, [
        'name' => 'Kadmay',
        'brand_display_name' => 'Kadmay Global Limited',
        'brand_primary_color' => '#6B21A8',
        'brand_logo_path' => 'tenant-branding/kadmay/logos/kadmay.png',
        'brand_login_hero_path' => 'tenant-branding/kadmay/hero/industrial.webp',
    ], $actor);

    $this->assertDatabaseHas('tenants', [
        'id' => $tenant->getKey(),
        'brand_display_name' => 'Kadmay Global Limited',
        'brand_primary_color' => '#6B21A8',
        'brand_logo_path' => 'tenant-branding/kadmay/logos/kadmay.png',
        'brand_login_hero_path' => 'tenant-branding/kadmay/hero/industrial.webp',
    ]);
});

test('tenant edit form uses independent desktop columns and the required mobile section order', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant(['name' => 'Kadmay', 'slug' => 'kadmay']);
    $actor = $this->tenantUser($tenant, [], [RoleName::SuperAdministrator->value]);

    $this->actingAs($actor)
        ->get(TenantResource::getUrl('edit', ['record' => $tenant]))
        ->assertOk()
        ->assertSee('Tenant account')
        ->assertSee('Default company profile')
        ->assertSee('Tenant branding')
        ->assertSee('contents lg:block lg:space-y-6', false)
        ->assertSee('order-1', false)
        ->assertSee('order-2 lg:order-none', false)
        ->assertSee('order-3', false);
});
