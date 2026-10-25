<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Pages;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Widgets\ApprovalQueueWidget;
use App\Core\Administration\Filament\Widgets\AttentionRequiredWidget;
use App\Core\Administration\Filament\Widgets\ExecutiveKpiOverviewWidget;
use App\Core\Administration\Filament\Widgets\OperationsControlWidget;
use App\Core\Administration\Filament\Widgets\QuickNavigationWidget;
use App\Core\Administration\Filament\Widgets\RecentActivityWidget;
use App\Core\Administration\Filament\Widgets\SystemAlertsWidget;
use App\Core\Administration\Filament\Widgets\UpcomingWorkWidget;
use App\Core\Administration\Filament\Widgets\WelcomeHeroWidget;
use App\Core\Administration\Services\ExecutiveDashboardService;
use App\Models\User;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use UnitEnum;

class Dashboard extends BaseDashboard
{
    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?string $navigationLabel = 'Dashboard';

    public function getWidgets(): array
    {
        $access = app(AdministrationAccessService::class);
        $user = auth()->user();
        $isPlatformSession = $access->isPlatformSession($user);

        if ($isPlatformSession) {
            return [
                WelcomeHeroWidget::class,
                ExecutiveKpiOverviewWidget::class,
                RecentActivityWidget::class,
                QuickNavigationWidget::class,
                SystemAlertsWidget::class,
            ];
        }

        if (! $user instanceof User) {
            return [
                WelcomeHeroWidget::class,
                ExecutiveKpiOverviewWidget::class,
                UpcomingWorkWidget::class,
                QuickNavigationWidget::class,
                SystemAlertsWidget::class,
            ];
        }

        $dashboard = app(ExecutiveDashboardService::class)->forUser($user);
        $profile = $dashboard['dashboard_profile'] ?? 'restricted';

        return match ($profile) {
            'finance' => [
                WelcomeHeroWidget::class,
                ExecutiveKpiOverviewWidget::class,
                QuickNavigationWidget::class,
                SystemAlertsWidget::class,
            ],
            'data_entry' => [
                WelcomeHeroWidget::class,
                ExecutiveKpiOverviewWidget::class,
                UpcomingWorkWidget::class,
                AttentionRequiredWidget::class,
                QuickNavigationWidget::class,
                SystemAlertsWidget::class,
            ],
            'operations' => [
                WelcomeHeroWidget::class,
                ExecutiveKpiOverviewWidget::class,
                OperationsControlWidget::class,
                UpcomingWorkWidget::class,
                AttentionRequiredWidget::class,
                RecentActivityWidget::class,
                QuickNavigationWidget::class,
                SystemAlertsWidget::class,
            ],
            'company_admin' => [
                WelcomeHeroWidget::class,
                ExecutiveKpiOverviewWidget::class,
                OperationsControlWidget::class,
                UpcomingWorkWidget::class,
                ApprovalQueueWidget::class,
                RecentActivityWidget::class,
                QuickNavigationWidget::class,
                SystemAlertsWidget::class,
            ],
            default => [
                WelcomeHeroWidget::class,
                ExecutiveKpiOverviewWidget::class,
                OperationsControlWidget::class,
                UpcomingWorkWidget::class,
                QuickNavigationWidget::class,
                SystemAlertsWidget::class,
            ],
        };
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
            'default' => 12,
            'md' => 12,
            'xl' => 12,
        ];
    }

    public function getWidgetsContentComponent(): Component
    {
        $widgets = $this->getWidgets();
        $topWidgets = $this->widgetsIn($widgets, [
            WelcomeHeroWidget::class,
            ExecutiveKpiOverviewWidget::class,
            OperationsControlWidget::class,
        ]);
        $primaryWidgets = $this->widgetsIn($widgets, [
            UpcomingWorkWidget::class,
            RecentActivityWidget::class,
            QuickNavigationWidget::class,
            SystemAlertsWidget::class,
        ]);
        $supportingWidgets = $this->widgetsIn($widgets, [
            AttentionRequiredWidget::class,
            ApprovalQueueWidget::class,
        ]);
        $remainingWidgets = $this->widgetsExcept($widgets, [
            WelcomeHeroWidget::class,
            ExecutiveKpiOverviewWidget::class,
            OperationsControlWidget::class,
            UpcomingWorkWidget::class,
            RecentActivityWidget::class,
            AttentionRequiredWidget::class,
            ApprovalQueueWidget::class,
            QuickNavigationWidget::class,
            SystemAlertsWidget::class,
        ]);

        $components = [];

        if ($topWidgets !== []) {
            $components[] = Grid::make(1)
                ->columnSpanFull()
                ->schema(fn (): array => $this->getWidgetsSchemaComponents($topWidgets));
        }

        if (($primaryWidgets !== []) && ($supportingWidgets !== [])) {
            $components[] = Grid::make([
                'default' => 1,
                'xl' => 3,
            ])
                ->columnSpanFull()
                ->schema([
                    Grid::make(1)
                        ->columnSpan([
                            'default' => 1,
                            'xl' => 2,
                        ])
                        ->schema(fn (): array => $this->getWidgetsSchemaComponents($primaryWidgets)),
                    Grid::make(1)
                        ->columnSpan([
                            'default' => 1,
                            'xl' => 1,
                        ])
                        ->schema(fn (): array => $this->getWidgetsSchemaComponents($supportingWidgets)),
                ]);
        } elseif ($primaryWidgets !== []) {
            $components[] = Grid::make(1)
                ->columnSpanFull()
                ->schema(fn (): array => $this->getWidgetsSchemaComponents($primaryWidgets));
        } elseif ($supportingWidgets !== []) {
            $components[] = Grid::make(1)
                ->columnSpanFull()
                ->schema(fn (): array => $this->getWidgetsSchemaComponents($supportingWidgets));
        }

        if ($remainingWidgets !== []) {
            $components[] = Grid::make(1)
                ->columnSpanFull()
                ->schema(fn (): array => $this->getWidgetsSchemaComponents($remainingWidgets));
        }

        return Grid::make($this->getColumns())
            ->schema($components);
    }

    /**
     * @param  array<class-string<Widget> | WidgetConfiguration>  $widgets
     * @param  list<class-string>  $classes
     * @return list<class-string<Widget> | WidgetConfiguration>
     */
    private function widgetsIn(array $widgets, array $classes): array
    {
        return array_values(array_filter(
            $widgets,
            fn (string|WidgetConfiguration $widget): bool => in_array($this->normalizeWidgetClass($widget), $classes, true),
        ));
    }

    /**
     * @param  array<class-string<Widget> | WidgetConfiguration>  $widgets
     * @param  list<class-string>  $classes
     * @return list<class-string<Widget> | WidgetConfiguration>
     */
    private function widgetsExcept(array $widgets, array $classes): array
    {
        return array_values(array_filter(
            $widgets,
            fn (string|WidgetConfiguration $widget): bool => ! in_array($this->normalizeWidgetClass($widget), $classes, true),
        ));
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
