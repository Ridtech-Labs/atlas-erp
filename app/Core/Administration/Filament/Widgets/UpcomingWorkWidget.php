<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Operations\Models\Job;
use Filament\Widgets\Widget;

class UpcomingWorkWidget extends Widget
{
    protected string $view = 'filament.widgets.upcoming-work-widget';

    protected int|string|array $columnSpan = [
        'default' => 12,
        'xl' => 8,
    ];

    protected function getViewData(): array
    {
        return [
            'jobs' => Job::query()
                ->with(['client', 'site'])
                ->whereNotNull('planned_start_date')
                ->orderBy('planned_start_date')
                ->limit(6)
                ->get(),
        ];
    }
}
