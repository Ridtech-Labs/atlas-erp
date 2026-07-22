<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use Filament\Widgets\Widget;

class PipelineWidget extends Widget
{
    protected string $view = 'filament.widgets.pipeline-widget';

    protected int|string|array $columnSpan = [
        'default' => 12,
        'xl' => 8,
    ];

    protected function getViewData(): array
    {
        return [
            'stages' => [
                ['label' => 'Draft', 'count' => Job::query()->where('status', JobStatus::Draft->value)->count()],
                ['label' => 'Pending Approval', 'count' => Job::query()->where('status', JobStatus::PendingApproval->value)->count()],
                ['label' => 'Scheduled', 'count' => Job::query()->where('status', JobStatus::Scheduled->value)->count()],
                ['label' => 'In Progress', 'count' => Job::query()->where('status', JobStatus::InProgress->value)->count()],
                ['label' => 'Completed', 'count' => Job::query()->where('status', JobStatus::Completed->value)->count()],
            ],
        ];
    }
}
