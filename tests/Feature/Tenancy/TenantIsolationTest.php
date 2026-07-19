<?php

use App\Core\Settings\Models\Setting;
use App\Core\Tenancy\Support\TenantContext;
use App\Models\User;

test('settings queries are tenant scoped when tenant context is resolved', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $tenant = $user->tenant;

    expect($tenant)->not->toBeNull();

    app(TenantContext::class)->set($tenant);

    Setting::withoutGlobalScopes()->create([
        'tenant_id' => $user->tenant_id,
        'group' => 'company',
        'key' => 'primary',
        'value' => ['name' => 'Tenant A'],
    ]);

    Setting::withoutGlobalScopes()->create([
        'tenant_id' => $otherUser->tenant_id,
        'group' => 'company',
        'key' => 'primary',
        'value' => ['name' => 'Tenant B'],
    ]);

    expect(Setting::query()->count())->toBe(1);
});
