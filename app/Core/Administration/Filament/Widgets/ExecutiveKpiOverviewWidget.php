<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Core\Administration\Services\ExecutiveDashboardService;
use App\Models\User;
use Filament\Widgets\Widget;

class ExecutiveKpiOverviewWidget extends Widget
{
    protected string $view = 'filament.widgets.executive-kpi-overview-widget';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected ?string $placeholderHeight = '16rem';

    protected function getViewData(): array
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return [
                'kpis' => [],
            ];
        }

        return [
            'kpis' => app(ExecutiveDashboardService::class)->forUser($user)['kpis'],
        ];
    }
}
