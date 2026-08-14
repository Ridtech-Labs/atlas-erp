<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Core\Administration\Services\ExecutiveDashboardService;
use App\Models\User;
use Filament\Widgets\Widget;

class UpcomingWorkWidget extends Widget
{
    protected string $view = 'filament.widgets.upcoming-work-widget';

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
                'heading' => 'Upcoming Work',
                'description' => 'Your operational queue is loading.',
                'jobs' => collect(),
                'mode' => 'restricted',
                'canViewJobs' => false,
            ];
        }

        $data = app(ExecutiveDashboardService::class)->forUser($user);

        return [
            'heading' => $data['work_items']['heading'],
            'description' => $data['work_items']['description'],
            'jobs' => $data['work_items']['items'],
            'mode' => $data['work_items']['mode'],
            'canViewJobs' => $data['can']['view_jobs'],
        ];
    }
}
