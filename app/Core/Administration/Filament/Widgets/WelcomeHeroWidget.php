<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Core\Administration\Services\ExecutiveDashboardService;
use App\Models\User;
use Filament\Widgets\Widget;

class WelcomeHeroWidget extends Widget
{
    protected string $view = 'filament.widgets.welcome-hero-widget';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected ?string $placeholderHeight = '18rem';

    protected function getViewData(): array
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return [
                'greeting' => 'Welcome',
                'userName' => 'there',
                'contextLabel' => 'Atlas ERP',
                'todayLabel' => now()->format('l, j F Y'),
                'operationalStatus' => [
                    'tone' => 'info',
                    'text' => 'Workspace loading',
                    'detail' => 'Operational status will appear shortly.',
                ],
                'summary' => 'Your workspace is loading.',
                'primaryAction' => null,
                'showExport' => false,
            ];
        }

        $data = app(ExecutiveDashboardService::class)->forUser($user);

        return [
            'greeting' => $data['greeting'],
            'userName' => $user->first_name ?: $user->getFilamentName(),
            'contextLabel' => $data['context_label'] ?? ($data['tenant']->name ?? 'Atlas ERP'),
            'todayLabel' => $data['today_label'],
            'operationalStatus' => $data['operational_status'],
            'summary' => $data['summary'],
            'primaryAction' => $data['primary_action'],
            'showExport' => $data['show_export'] ?? false,
        ];
    }
}
