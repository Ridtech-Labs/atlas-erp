<?php

use App\Core\Administration\Providers\CoreServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\VoltServiceProvider;

return [
    AppServiceProvider::class,
    CoreServiceProvider::class,
    AdminPanelProvider::class,
    VoltServiceProvider::class,
];
