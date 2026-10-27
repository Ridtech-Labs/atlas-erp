<?php

use App\Administration\Actions\Users\CreateUserAction;
use App\Administration\Enums\RoleName;
use App\Core\Settings\Actions\UpdateSettingAction;
use App\Core\Settings\DTOs\SettingData;
use App\Core\Settings\Models\Setting;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;

test('company administrators can not create users for another company', function () {
    $this->seed(RoleAndPermissionSeeder::class);

    $tenantAdmin = User::factory()->create();
    $tenantAdmin->assignRole(RoleName::CompanyAdministrator->value);

    $otherTenantUser = User::factory()->create();

    expect(fn () => app(CreateUserAction::class)->execute([
        'tenant_id' => $otherTenantUser->tenant_id,
        'first_name' => 'Cross',
        'last_name' => 'Tenant',
        'email' => 'cross-tenant@example.com',
        'password' => 'secret-password',
        'status' => 'active',
    ], [RoleName::StandardUser->value], $tenantAdmin))
        ->toThrow(BusinessException::class);
});

test('activity logs respect tenant boundaries for company administrators', function () {
    $this->seed(RoleAndPermissionSeeder::class);

    $tenantAdmin = User::factory()->create();
    $tenantAdmin->assignRole(RoleName::CompanyAdministrator->value);

    $otherUser = User::factory()->create();

    $sameTenantActivity = Activity::query()->create([
        'log_name' => 'administration',
        'description' => 'Tenant activity',
        'subject_type' => User::class,
        'subject_id' => $tenantAdmin->getKey(),
        'causer_type' => User::class,
        'causer_id' => $tenantAdmin->getKey(),
        'event' => 'user.updated',
        'properties' => ['tenant_id' => $tenantAdmin->tenant_id],
    ]);

    $otherTenantActivity = Activity::query()->create([
        'log_name' => 'administration',
        'description' => 'Other tenant activity',
        'subject_type' => User::class,
        'subject_id' => $otherUser->getKey(),
        'causer_type' => User::class,
        'causer_id' => $otherUser->getKey(),
        'event' => 'user.updated',
        'properties' => ['tenant_id' => $otherUser->tenant_id],
    ]);

    expect(Gate::forUser($tenantAdmin)->allows('view', $sameTenantActivity))->toBeTrue()
        ->and(Gate::forUser($tenantAdmin)->allows('view', $otherTenantActivity))->toBeFalse();
});

test('settings updates are stored for the authenticated users company only', function () {
    $tenant = $this->tenant();
    $company = $this->company($tenant);
    $user = $this->tenantUser($tenant);
    $otherTenant = $this->tenant();
    $otherCompany = $this->company($otherTenant);

    $this->actingAs($user);

    app(UpdateSettingAction::class)->execute(new SettingData(
        tenantId: $tenant->getKey(),
        group: 'notifications',
        key: 'channels',
        value: ['email_notifications_enabled' => false, 'database_notifications_enabled' => true],
        companyId: $company->getKey(),
    ));

    expect(Setting::query()
        ->where('tenant_id', $tenant->getKey())
        ->where('company_id', $company->getKey())
        ->where('group', 'notifications')
        ->where('key', 'channels')
        ->exists())->toBeTrue()
        ->and(Setting::query()
            ->where('tenant_id', $otherTenant->getKey())
            ->where('company_id', $otherCompany->getKey())
            ->where('group', 'notifications')
            ->where('key', 'channels')
            ->exists())->toBeFalse();
});
