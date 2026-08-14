<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Pages;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Widgets\ApprovalQueueWidget;
use App\Core\Administration\Filament\Widgets\ExecutiveKpiOverviewWidget;
use App\Core\Administration\Filament\Widgets\QuickNavigationWidget;
use App\Core\Administration\Filament\Widgets\RecentActivityWidget;
use App\Core\Administration\Filament\Widgets\SystemAlertsWidget;
use App\Core\Administration\Filament\Widgets\UpcomingWorkWidget;
use App\Core\Administration\Filament\Widgets\WelcomeHeroWidget;
use App\Models\User;
use Filament\Pages\Dashboard as BaseDashboard;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use UnitEnum;

class Dashboard extends BaseDashboard
{
    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?string $navigationLabel = 'Dashboard';

    public function getWidgets(): array
    {
        $access = app(AdministrationAccessService::class);
        $isPlatformSession = $access->isPlatformSession(auth()->user());

        if ($isPlatformSession) {
            return [
                WelcomeHeroWidget::class,
                ExecutiveKpiOverviewWidget::class,
                RecentActivityWidget::class,
                QuickNavigationWidget::class,
                SystemAlertsWidget::class,
            ];
        }

        return [
            WelcomeHeroWidget::class,
            ExecutiveKpiOverviewWidget::class,
            UpcomingWorkWidget::class,
            ApprovalQueueWidget::class,
            RecentActivityWidget::class,
            QuickNavigationWidget::class,
            SystemAlertsWidget::class,
        ];
    }

    public function getHeading(): string
    {
        return '';
    }

    public function getSubheading(): ?string
    {
        return null;
    }

    public function getColumns(): int|array
    {
        return [
            'md' => 12,
            'xl' => 12,
        ];
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        try {
            return $user->hasPermissionTo(PermissionName::DashboardView->value);
        } catch (PermissionDoesNotExist) {
            return false;
        }
    }
}
