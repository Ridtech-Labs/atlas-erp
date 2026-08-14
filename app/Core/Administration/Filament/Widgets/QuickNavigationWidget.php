<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Core\Administration\Services\ExecutiveDashboardService;
use App\Models\User;
use Filament\Widgets\Widget;

class QuickNavigationWidget extends Widget
{
    protected string $view = 'filament.widgets.quick-navigation-widget';

    protected int|string|array $columnSpan = [
        'default' => 12,
        'xl' => 4,
    ];

    protected static bool $isLazy = false;

    protected ?string $placeholderHeight = '18rem';

    protected function getViewData(): array
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return [
                'actionCards' => [],
            ];
        }

        $data = app(ExecutiveDashboardService::class)->forUser($user);

        return [
            'actionCards' => $data['quick_actions'],
        ];
    }
}
