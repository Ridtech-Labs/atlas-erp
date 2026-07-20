<?php

use App\Administration\Actions\Companies\CreateCompanyAction;
use App\Administration\Actions\Companies\UpdateCompanyAction;
use App\Administration\Actions\Users\CreateUserAction;
use App\Administration\Actions\Users\UpdateUserAction;
use App\Administration\Actions\Users\UpdateUserStatusAction;
use App\Administration\Enums\RoleName;
use App\Core\Shared\Enums\UserStatus;
use App\Core\Shared\Exceptions\BusinessException;
use Spatie\Activitylog\Models\Activity;

test('company creation is logged', function () {
    $this->seedAccessControl();
    $actor = $this->actingAsRole(RoleName::SuperAdministrator->value);

    $tenant = app(CreateCompanyAction::class)->execute([
        'name' => 'New Company',
        'slug' => 'new-company',
        'timezone' => 'Africa/Accra',
        'currency' => 'GHS',
    ], $actor);

    expect($tenant->name)->toBe('New Company')
        ->and(Activity::query()->where('event', 'company.created')->exists())->toBeTrue();
});

test('company update is logged', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $actor = $this->actingAsRole(RoleName::SuperAdministrator->value, $tenant);

    $updated = app(UpdateCompanyAction::class)->execute($tenant, [
        'name' => 'Updated Company',
    ], $actor);

    expect($updated->name)->toBe('Updated Company')
        ->and(Activity::query()->where('event', 'company.updated')->exists())->toBeTrue();
});

test('company administrator can manage users in their company', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $actor = $this->actingAsCompanyAdministrator($tenant);

    $user = app(CreateUserAction::class)->execute([
        'tenant_id' => $tenant->getKey(),
        'first_name' => 'Grace',
        'last_name' => 'Admin',
        'email' => 'grace@example.test',
        'password' => 'password',
    ], [RoleName::StandardUser->value], $actor);

    $updated = app(UpdateUserAction::class)->execute($user, [
        'first_name' => 'Grace Updated',
    ], [RoleName::StandardUser->value], $actor);

    expect($updated->first_name)->toBe('Grace Updated');
});

test('company administrator can not assign super administrator role', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $actor = $this->actingAsCompanyAdministrator($tenant);

    expect(fn () => app(CreateUserAction::class)->execute([
        'tenant_id' => $tenant->getKey(),
        'first_name' => 'Blocked',
        'last_name' => 'User',
        'email' => 'blocked@example.test',
        'password' => 'password',
    ], [RoleName::SuperAdministrator->value], $actor))
        ->toThrow(BusinessException::class);
});

test('standard users can not manage users', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $actor = $this->actingAsRole(RoleName::StandardUser->value, $tenant);

    expect(fn () => app(CreateUserAction::class)->execute([
        'tenant_id' => $tenant->getKey(),
        'first_name' => 'Blocked',
        'last_name' => 'User',
        'email' => 'standard-blocked@example.test',
        'password' => 'password',
    ], [RoleName::StandardUser->value], $actor))
        ->toThrow(BusinessException::class);
});

test('user status changes are logged', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $actor = $this->actingAsCompanyAdministrator($tenant);
    $user = $this->tenantUser($tenant, [], [RoleName::StandardUser->value]);

    $updated = app(UpdateUserStatusAction::class)->execute($user, UserStatus::Suspended, $actor);

    expect($updated->status)->toBe(UserStatus::Suspended)
        ->and(Activity::query()->where('event', 'user.status_changed')->exists())->toBeTrue();
});

test('role assignment is applied during user creation', function () {
    $this->seedAccessControl();
    $tenant = $this->tenant();
    $actor = $this->actingAsCompanyAdministrator($tenant);

    $user = app(CreateUserAction::class)->execute([
        'tenant_id' => $tenant->getKey(),
        'first_name' => 'Role',
        'last_name' => 'Assigned',
        'email' => 'role-assigned@example.test',
        'password' => 'password',
    ], [RoleName::StandardUser->value], $actor);

    expect($user->hasRole(RoleName::StandardUser->value))->toBeTrue();
});
