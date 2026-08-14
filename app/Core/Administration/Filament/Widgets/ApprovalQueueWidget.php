<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Core\Administration\Services\ExecutiveDashboardService;
use App\Models\User;
use Filament\Widgets\Widget;

class ApprovalQueueWidget extends Widget
{
    protected string $view = 'filament.widgets.approval-queue-widget';

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
                'items' => collect(),
                'canViewJobs' => false,
            ];
        }

        $data = app(ExecutiveDashboardService::class)->forUser($user);

        return [
            'items' => $data['approval_queue'],
            'canViewJobs' => $data['can']['view_jobs'],
        ];
    }
}
