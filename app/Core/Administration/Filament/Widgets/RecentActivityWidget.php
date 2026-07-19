<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use Filament\Widgets\Widget;
use Spatie\Activitylog\Models\Activity;

class RecentActivityWidget extends Widget
{
    protected string $view = 'filament.widgets.recent-activity-widget';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'activities' => Activity::query()->latest()->limit(10)->get(),
        ];
    }
}
