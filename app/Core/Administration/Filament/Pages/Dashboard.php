<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Pages;

use App\Core\Administration\Filament\Widgets\BusinessOverviewWidget;
use App\Core\Administration\Filament\Widgets\DashboardSummaryWidget;
use App\Core\Administration\Filament\Widgets\PipelineWidget;
use App\Core\Administration\Filament\Widgets\QuickNavigationWidget;
use App\Core\Administration\Filament\Widgets\RecentActivityWidget;
use App\Core\Administration\Filament\Widgets\SystemAlertsWidget;
use App\Core\Administration\Filament\Widgets\UpcomingWorkWidget;
use App\Core\Administration\Filament\Widgets\WorkspaceShortcutsWidget;
use App\Core\Tenancy\Models\Tenant;
use Filament\Pages\Dashboard as BaseDashboard;
use UnitEnum;

class Dashboard extends BaseDashboard
{
    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?string $navigationLabel = 'Dashboard';

    public function getWidgets(): array
    {
        return [
            DashboardSummaryWidget::class,
            BusinessOverviewWidget::class,
            RecentActivityWidget::class,
            QuickNavigationWidget::class,
            UpcomingWorkWidget::class,
            SystemAlertsWidget::class,
            PipelineWidget::class,
            WorkspaceShortcutsWidget::class,
        ];
    }

    public function getHeading(): string
    {
        $user = auth()->user();
        $name = $user?->first_name ?: 'there';

        return sprintf('%s %s', $this->greeting(), $name);
    }

    public function getSubheading(): ?string
    {
        $tenant = auth()->user()?->tenant;
        $tenantName = $tenant instanceof Tenant ? $tenant->name : 'Atlas ERP workspace';

        return $tenantName;
    }

    public function getColumns(): int|array
    {
        return [
            'md' => 12,
            'xl' => 12,
        ];
    }

    private function greeting(): string
    {
        $hour = now()->hour;

        return match (true) {
            $hour < 12 => 'Good morning',
            $hour < 18 => 'Good afternoon',
            default => 'Good evening',
        };
    }
}
