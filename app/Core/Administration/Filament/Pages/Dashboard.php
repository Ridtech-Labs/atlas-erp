<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Pages;

use App\Core\Administration\Filament\Widgets\CurrentContextWidget;
use App\Core\Administration\Filament\Widgets\QuickNavigationWidget;
use App\Core\Administration\Filament\Widgets\RecentActivityWidget;
use App\Core\Administration\Filament\Widgets\SystemStatusWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            CurrentContextWidget::class,
            SystemStatusWidget::class,
            QuickNavigationWidget::class,
            RecentActivityWidget::class,
        ];
    }
}
