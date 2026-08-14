<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Core\Administration\Services\ExecutiveDashboardService;
use App\Models\User;
use Filament\Widgets\Widget;

class RecentActivityWidget extends Widget
{
    protected string $view = 'filament.widgets.recent-activity-widget';

    protected int|string|array $columnSpan = [
        'default' => 12,
        'xl' => 8,
    ];

    protected static bool $isLazy = false;

    protected ?string $placeholderHeight = '24rem';

    protected function getViewData(): array
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return [
                'activities' => collect(),
                'restricted' => true,
            ];
        }

        $data = app(ExecutiveDashboardService::class)->forUser($user);

        return [
            'activities' => $data['recent_activity']['items'],
            'restricted' => $data['recent_activity']['restricted'],
        ];
    }
}
