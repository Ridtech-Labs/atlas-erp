<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Core\Administration\Services\ExecutiveDashboardService;
use App\Models\User;
use Filament\Widgets\Widget;

class AttentionRequiredWidget extends Widget
{
    protected string $view = 'filament.widgets.attention-required-widget';

    protected int|string|array $columnSpan = [
        'default' => 12,
        'xl' => 4,
    ];

    protected static bool $isLazy = false;

    protected ?string $placeholderHeight = '22rem';

    protected function getViewData(): array
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return [
                'items' => [],
                'canViewJobs' => false,
                'canViewClients' => false,
            ];
        }

        $data = app(ExecutiveDashboardService::class)->forUser($user);

        return [
            'items' => $data['attention_items'],
            'canViewJobs' => $data['can']['view_jobs'],
            'canViewClients' => $data['can']['view_clients'],
        ];
    }
}
