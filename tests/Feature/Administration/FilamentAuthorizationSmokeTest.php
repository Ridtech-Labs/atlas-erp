<?php

use App\Administration\Enums\RoleName;

test('filament administration pages render without authorization recursion', function () {
    $this->seedAccessControl();
    $this->actingAsRole(RoleName::SuperAdministrator->value);

    $this->get('/admin')->assertOk();
    $this->get('/admin/settings')->assertOk();
    $this->get('/admin/roles')->assertOk();
    $this->get('/admin/system-health')->assertOk();
});
