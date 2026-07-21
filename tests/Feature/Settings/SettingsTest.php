<?php

use App\Administration\Actions\Settings\UpdateSettingsAction;
use App\Administration\Enums\RoleName;
use App\Core\Settings\DTOs\SettingData;
use App\Core\Settings\Models\Setting;
use App\Core\Settings\Services\SettingService;
use App\Core\Shared\Exceptions\BusinessException;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Gate;

test('settings are isolated by company', function () {
    $tenant = $this->tenant();
    $otherTenant = $this->tenant();

    Setting::withoutGlobalScopes()->create([
        'tenant_id' => $tenant->getKey(),
        'group' => 'company',
        'key' => 'profile',
        'value' => ['name' => 'Tenant A'],
        'is_encrypted' => false,
    ]);

    Setting::withoutGlobalScopes()->create([
        'tenant_id' => $otherTenant->getKey(),
        'group' => 'company',
        'key' => 'profile',
        'value' => ['name' => 'Tenant B'],
        'is_encrypted' => false,
    ]);

    $settings = app(SettingService::class)->allForTenant($tenant->getKey());

    expect($settings)->toHaveCount(1)
        ->and($settings->first()?->tenant_id)->toBe($tenant->getKey());
});

test('settings defaults are applied by the database seeder', function () {
    $this->seed(DatabaseSeeder::class);

    $setting = Setting::withoutGlobalScopes()
        ->where('group', 'company')
        ->where('key', 'profile')
        ->first();

    expect($setting)->not->toBeNull()
        ->and($setting?->value)->toMatchArray([
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i',
        ]);
});

test('unauthorized users can not update settings', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $actor = $this->tenantUser($tenant, [], [RoleName::StandardUser->value]);

    expect(Gate::forUser($actor)->allows('manageSettings'))->toBeFalse()
        ->and(fn () => app(UpdateSettingsAction::class)->execute(new SettingData(
            tenantId: $tenant->getKey(),
            group: 'company',
            key: 'profile',
            value: ['name' => 'Blocked'],
        ), $actor))->toThrow(BusinessException::class);
});

test('authorized users can update settings', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $actor = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);

    app(UpdateSettingsAction::class)->execute(new SettingData(
        tenantId: $tenant->getKey(),
        group: 'company',
        key: 'profile',
        value: ['name' => 'Updated Company'],
    ), $actor);

    expect(Setting::withoutGlobalScopes()
        ->where('tenant_id', $tenant->getKey())
        ->where('group', 'company')
        ->where('key', 'profile')
        ->exists())->toBeTrue();
});
