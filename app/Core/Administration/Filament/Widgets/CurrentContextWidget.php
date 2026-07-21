<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Administration\Enums\RoleName;
use App\Core\Tenancy\Support\TenantContext;
use App\Models\User;
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
                Stat::make('Current Company', $tenant ? $tenant->name : 'Platform'),
                Stat::make('Active Users', '0'),
                Stat::make('System Version', config('app.version', 'Sprint 0')),
            ];
        }

        $stats = [
            Stat::make('Current User', $user->full_name),
            Stat::make('Current Company', $tenant ? $tenant->name : 'Platform'),
            Stat::make('Active Users', (string) User::query()
                ->where('tenant_id', $user->tenant_id)
                ->where('status', 'active')
                ->count()),
            Stat::make('System Version', config('app.version', 'Sprint 0')),
        ];

        if ($user->hasRole(RoleName::SuperAdministrator->value)) {
            $stats[] = Stat::make('Environment', strtoupper((string) app()->environment()));
        }

        return $stats;
    }
}
