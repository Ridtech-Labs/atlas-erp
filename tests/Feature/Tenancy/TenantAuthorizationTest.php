<?php

use App\Administration\Enums\RoleName;
use App\Core\Settings\Models\Setting;
use Illuminate\Support\Facades\Gate;

test('tenant user can view and update their own company only', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $otherTenant = $this->tenant();
    $user = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);

    expect(Gate::forUser($user)->allows('view', $tenant))->toBeTrue()
        ->and(Gate::forUser($user)->allows('update', $tenant))->toBeTrue()
        ->and(Gate::forUser($user)->allows('view', $otherTenant))->toBeFalse()
        ->and(Gate::forUser($user)->allows('update', $otherTenant))->toBeFalse();
});

test('tenant user can not access another company users', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $otherTenant = $this->tenant();
    $user = $this->tenantUser($tenant, [], [RoleName::CompanyAdministrator->value]);
    $otherUser = $this->tenantUser($otherTenant, [], [RoleName::StandardUser->value]);

    expect(Gate::forUser($user)->allows('view', $otherUser))->toBeFalse()
        ->and(Gate::forUser($user)->allows('update', $otherUser))->toBeFalse();
});

test('new tenant owned settings receive the current company id', function () {
    $tenant = $this->tenant();
    $this->setTenantContext($tenant);

    $setting = Setting::query()->create([
        'group' => 'notifications',
        'key' => 'channels',
        'value' => ['email' => true],
        'is_encrypted' => false,
    ]);

    expect($setting->tenant_id)->toBe($tenant->getKey());
});

test('super administrators can access multiple companies', function () {
    $this->seedAccessControl();
    $tenantA = $this->tenant();
    $tenantB = $this->tenant();
    $user = $this->tenantUser($tenantA, [], [RoleName::SuperAdministrator->value]);

    expect(Gate::forUser($user)->allows('view', $tenantA))->toBeTrue()
        ->and(Gate::forUser($user)->allows('view', $tenantB))->toBeTrue();
});
