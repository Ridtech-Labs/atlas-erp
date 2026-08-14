<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Core\Administration\Services\ExecutiveDashboardService;
use App\Models\User;
use Filament\Widgets\Widget;

class SystemAlertsWidget extends Widget
{
    protected string $view = 'filament.widgets.system-alerts-widget';

    protected int|string|array $columnSpan = [
        'default' => 12,
        'xl' => 4,
    ];

    protected static bool $isLazy = false;

    protected ?string $placeholderHeight = '24rem';

    protected function getViewData(): array
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return [
                'alerts' => [],
            ];
        }

        return [
            'alerts' => app(ExecutiveDashboardService::class)->forUser($user)['system_alerts'],
        ];
    }
}
