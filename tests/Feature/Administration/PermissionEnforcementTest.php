<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

test('permissions can be granted through roles', function () {
    $user = User::factory()->create();
    $permission = Permission::query()->create([
        'name' => 'dashboard.view',
        'guard_name' => 'web',
    ]);

    $role = Role::query()->create([
        'name' => 'Company Administrator',
        'guard_name' => 'web',
    ]);

    $role->givePermissionTo($permission);
    $user->assignRole($role);

    expect($user->can('dashboard.view'))->toBeTrue();
});
