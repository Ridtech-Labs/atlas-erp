<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use Filament\Widgets\Widget;

class SystemAlertsWidget extends Widget
{
    protected string $view = 'filament.widgets.system-alerts-widget';

    protected int|string|array $columnSpan = [
        'default' => 12,
        'xl' => 4,
    ];

    protected function getViewData(): array
    {
        $today = today();

        return [
            'alerts' => [
                [
                    'label' => 'Overdue jobs',
                    'value' => Job::query()
                        ->whereIn('status', [
                            JobStatus::Approved->value,
                            JobStatus::Scheduled->value,
                            JobStatus::InProgress->value,
                            JobStatus::OnHold->value,
                        ])
                        ->whereDate('planned_end_date', '<', $today)
                        ->count(),
                ],
                [
                    'label' => 'Pending approvals',
                    'value' => Job::query()->where('status', JobStatus::PendingApproval->value)->count(),
                ],
                [
                    'label' => 'Starts today',
                    'value' => Job::query()->whereDate('planned_start_date', $today)->count(),
                ],
            ],
        ];
    }
}
