<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Core\Tenancy\Support\TenantContext;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CurrentContextWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $tenant = app(TenantContext::class)->tenant();

        if ($user === null) {
            return [
                Stat::make('Current User', 'Guest'),
                Stat::make('Current Tenant', $tenant ? $tenant->name : 'Platform'),
                Stat::make('System Version', config('app.version', 'Sprint 0')),
            ];
        }

        return [
            Stat::make('Current User', $user->full_name),
            Stat::make('Current Tenant', $tenant ? $tenant->name : 'Platform'),
            Stat::make('System Version', config('app.version', 'Sprint 0')),
        ];
    }
}
