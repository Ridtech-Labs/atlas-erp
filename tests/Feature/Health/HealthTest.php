<?php

use App\Administration\Enums\RoleName;

test('authorized administrators can access the health endpoint', function () {
    $this->seedAccessControl();
    $user = $this->actingAsCompanyAdministrator();

    $response = $this->get(route('health'));

    $response
        ->assertOk()
        ->assertJsonPath('status', 'ok')
        ->assertJsonPath('tenant.id', $user->tenant_id);
});

test('unauthorized users can not access the health endpoint', function () {
    $this->seedAccessControl();
    $this->actingAsRole(RoleName::StandardUser->value);

    $this->get(route('health'))->assertForbidden();
});
