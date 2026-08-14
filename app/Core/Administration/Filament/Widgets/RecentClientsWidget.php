<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Core\Administration\Services\ExecutiveDashboardService;
use App\Models\User;
use Filament\Widgets\Widget;

class RecentClientsWidget extends Widget
{
    protected string $view = 'filament.widgets.recent-clients-widget';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected ?string $placeholderHeight = '20rem';

    protected function getViewData(): array
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return [
                'clients' => collect(),
                'canViewClients' => false,
            ];
        }

        $data = app(ExecutiveDashboardService::class)->forUser($user);

        return [
            'clients' => $data['recent_clients'],
            'canViewClients' => $data['can']['view_clients'],
        ];
    }
}
