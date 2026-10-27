<?php

use App\Administration\Actions\Settings\UpdateSettingsAction;
use App\Administration\Enums\RoleName;
use App\Core\Administration\Filament\Pages\ManageSettings;
use App\Core\Settings\Actions\BackfillLegacyCompanySettingsAction;
use App\Core\Settings\DTOs\SettingData;
use App\Core\Settings\Models\Setting;
use App\Core\Settings\Services\SettingService;
use App\Core\Shared\Exceptions\BusinessException;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;

test('company preferences are independent within the same tenant', function () {
    $tenant = $this->tenant();
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $settings = app(SettingService::class);

    $settings->update(new SettingData(
        tenantId: $tenant->getKey(),
        group: 'company',
        key: 'preferences',
        value: ['date_format' => 'd/m/Y', 'time_format' => 'H:i', 'language' => 'en'],
        companyId: $companyA->getKey(),
    ));
    $settings->update(new SettingData(
        tenantId: $tenant->getKey(),
        group: 'company',
        key: 'preferences',
        value: ['date_format' => 'm-d-Y', 'time_format' => 'g:i A', 'language' => 'fr'],
        companyId: $companyB->getKey(),
    ));

    expect($settings->allForCompany($tenant->getKey(), $companyA->getKey())->first()?->value)
        ->toMatchArray(['date_format' => 'd/m/Y', 'time_format' => 'H:i', 'language' => 'en'])
        ->and($settings->allForCompany($tenant->getKey(), $companyB->getKey())->first()?->value)
        ->toMatchArray(['date_format' => 'm-d-Y', 'time_format' => 'g:i A', 'language' => 'fr']);
});

test('company settings page loads preferences for the active company only', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $companyA = $this->company($tenant, ['name' => 'Kadmay Logistics']);
    $companyB = $this->company($tenant, ['name' => 'Kadmay Marine']);
    $user = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $user->companies()->sync([$companyA->getKey(), $companyB->getKey()]);
    $settings = app(SettingService::class);

    $settings->update(new SettingData(
        tenantId: $tenant->getKey(),
        group: 'company',
        key: 'preferences',
        value: ['date_format' => 'd/m/Y', 'time_format' => 'H:i', 'language' => 'en'],
        companyId: $companyA->getKey(),
    ));
    $settings->update(new SettingData(
        tenantId: $tenant->getKey(),
        group: 'company',
        key: 'preferences',
        value: ['date_format' => 'm-d-Y', 'time_format' => 'g:i A', 'language' => 'fr'],
        companyId: $companyB->getKey(),
    ));

    $this->actingAs($user)
        ->withSession(['active_company_id' => $companyA->getKey()]);

    Livewire::test(ManageSettings::class)
        ->assertSet('general.date_format', 'd/m/Y')
        ->assertSet('general.time_format', 'H:i')
        ->assertSet('general.language', 'en')
        ->set('general.date_format', 'Y/m/d')
        ->call('save')
        ->assertHasNoErrors();

    expect($settings->allForCompany($tenant->getKey(), $companyA->getKey())
        ->firstWhere(fn ($setting) => $setting->group === 'company' && $setting->key === 'preferences')
        ?->value['date_format'])->toBe('Y/m/d');

    $this->actingAs($user)
        ->withSession(['active_company_id' => $companyB->getKey()]);

    Livewire::test(ManageSettings::class)
        ->assertSet('general.date_format', 'm-d-Y')
        ->assertSet('general.time_format', 'g:i A')
        ->assertSet('general.language', 'fr');
});

test('unauthorized users cannot update company settings', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $actor = $this->tenantUser($tenant, [], [RoleName::StandardUser->value]);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    expect(Gate::forUser($actor)->allows('manageSettings'))->toBeFalse()
        ->and(fn () => app(UpdateSettingsAction::class)->execute(new SettingData(
            tenantId: $tenant->getKey(),
            group: 'company',
            key: 'preferences',
            value: ['language' => 'en'],
            companyId: $company->getKey(),
        ), $actor))->toThrow(BusinessException::class);
});

test('company administrators cannot update settings outside their active company or tenant', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $otherTenant = $this->tenant();
    $otherCompany = $this->company($otherTenant);
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $actor->companies()->sync([$company->getKey()]);
    session(['active_company_id' => $company->getKey()]);

    expect(fn () => app(UpdateSettingsAction::class)->execute(new SettingData(
        tenantId: $otherTenant->getKey(),
        group: 'company',
        key: 'preferences',
        value: ['language' => 'en'],
        companyId: $otherCompany->getKey(),
    ), $actor))->toThrow(BusinessException::class);
});

test('a super administrator can update settings in a validated support workspace only', function () {
    $this->seedAccessControl();

    $homeTenant = $this->tenant(['name' => 'Atlas Platform']);
    $supportTenant = $this->tenant(['name' => 'Kadmay']);
    $supportCompany = $this->company($supportTenant);
    $actor = $this->tenantUser($homeTenant, [], [RoleName::SuperAdministrator->value]);

    $this->actingAs($actor)
        ->withSession([
            'support_tenant_id' => $supportTenant->getKey(),
            'active_company_id' => $supportCompany->getKey(),
        ]);

    app(UpdateSettingsAction::class)->execute(new SettingData(
        tenantId: $supportTenant->getKey(),
        group: 'notifications',
        key: 'channels',
        value: ['email_notifications_enabled' => false, 'database_notifications_enabled' => true],
        companyId: $supportCompany->getKey(),
    ), $actor);

    expect(Setting::withoutGlobalScopes()
        ->where('tenant_id', $supportTenant->getKey())
        ->where('company_id', $supportCompany->getKey())
        ->where('group', 'notifications')
        ->where('key', 'channels')
        ->exists())->toBeTrue();
});

test('legacy preferences are backfilled once to the default company without changing company identity or tenant branding', function () {
    $tenant = $this->tenant([
        'brand_display_name' => 'Kadmay',
        'brand_primary_color' => '#6B21A8',
    ]);
    $defaultCompany = $this->company($tenant, [
        'name' => 'Kadmay Logistics',
        'timezone' => 'Africa/Accra',
        'currency' => 'GHS',
        'is_default' => true,
    ]);
    $otherCompany = $this->company($tenant, ['name' => 'Kadmay Marine']);

    Setting::withoutGlobalScopes()->create([
        'tenant_id' => $tenant->getKey(),
        'group' => 'company',
        'key' => 'profile',
        'value' => [
            'name' => 'Legacy tenant name',
            'timezone' => 'Europe/London',
            'currency' => 'USD',
            'date_format' => 'd/m/Y',
            'time_format' => 'H:i',
            'language' => 'en',
        ],
    ]);
    Setting::withoutGlobalScopes()->create([
        'tenant_id' => $tenant->getKey(),
        'group' => 'notifications',
        'key' => 'channels',
        'value' => ['email_notifications_enabled' => false, 'database_notifications_enabled' => true],
    ]);
    Setting::withoutGlobalScopes()->create([
        'tenant_id' => $tenant->getKey(),
        'company_id' => $defaultCompany->getKey(),
        'group' => 'notifications',
        'key' => 'channels',
        'value' => ['email_notifications_enabled' => true, 'database_notifications_enabled' => false],
    ]);

    app(BackfillLegacyCompanySettingsAction::class)->execute();
    app(BackfillLegacyCompanySettingsAction::class)->execute();

    $preferences = Setting::withoutGlobalScopes()
        ->where('tenant_id', $tenant->getKey())
        ->where('company_id', $defaultCompany->getKey())
        ->where('group', 'company')
        ->where('key', 'preferences')
        ->first();

    expect($preferences?->value)->toBe([
        'date_format' => 'd/m/Y',
        'time_format' => 'H:i',
        'language' => 'en',
    ])
        ->and(Setting::withoutGlobalScopes()
            ->where('company_id', $otherCompany->getKey())
            ->where('group', 'company')
            ->where('key', 'preferences')
            ->exists())->toBeFalse()
        ->and($defaultCompany->fresh(['tenant'])?->name)->toBe('Kadmay Logistics')
        ->and($defaultCompany->fresh()?->timezone)->toBe('Africa/Accra')
        ->and($defaultCompany->fresh()?->currency)->toBe('GHS')
        ->and($tenant->fresh()?->brand_display_name)->toBe('Kadmay')
        ->and($tenant->fresh()?->brand_primary_color)->toBe('#6B21A8')
        ->and(Setting::withoutGlobalScopes()
            ->where('tenant_id', $tenant->getKey())
            ->whereNull('company_id')
            ->where('group', 'company')
            ->where('key', 'profile')
            ->exists())->toBeTrue();

    $notifications = Setting::withoutGlobalScopes()
        ->where('tenant_id', $tenant->getKey())
        ->where('company_id', $defaultCompany->getKey())
        ->where('group', 'notifications')
        ->where('key', 'channels')
        ->first();

    expect($notifications?->value)->toBe([
        'email_notifications_enabled' => true,
        'database_notifications_enabled' => false,
    ]);
});

test('legacy settings are left untouched when a tenant has no default company', function () {
    $tenant = $this->tenant();

    Setting::withoutGlobalScopes()->create([
        'tenant_id' => $tenant->getKey(),
        'group' => 'company',
        'key' => 'profile',
        'value' => ['date_format' => 'd/m/Y'],
    ]);

    app(BackfillLegacyCompanySettingsAction::class)->execute();

    expect(Setting::withoutGlobalScopes()
        ->where('tenant_id', $tenant->getKey())
        ->whereNull('company_id')
        ->where('group', 'company')
        ->where('key', 'profile')
        ->exists())->toBeTrue()
        ->and(Setting::withoutGlobalScopes()
            ->where('tenant_id', $tenant->getKey())
            ->whereNotNull('company_id')
            ->exists())->toBeFalse();
});

test('settings access fails safely without an authorized active company', function () {
    $this->seedAccessControl();

    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $this->company($tenant);
    $user = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $user->companies()->detach();

    $this->actingAs($user);

    $this->get(ManageSettings::getUrl())->assertForbidden();

    expect(fn () => app(UpdateSettingsAction::class)->execute(new SettingData(
        tenantId: $tenant->getKey(),
        group: 'company',
        key: 'preferences',
        value: ['language' => 'en'],
        companyId: $company->getKey(),
    ), $user))->toThrow(BusinessException::class);
});

test('tenant branding stays outside the company settings interface', function () {
    $source = file_get_contents(resource_path('views/filament/pages/manage-settings.blade.php'));

    expect($source)->toBeString()
        ->not->toContain('primary_brand_preference')
        ->not->toContain('wire:model="branding.');
});
