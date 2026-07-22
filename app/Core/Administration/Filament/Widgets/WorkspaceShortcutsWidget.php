<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Core\Administration\Filament\Pages\ManageSettings;
use App\Core\Administration\Filament\Pages\SystemHealth;
use App\Core\Administration\Filament\Resources\Clients\ClientResource;
use App\Core\Administration\Filament\Resources\Jobs\JobResource;
use Filament\Widgets\Widget;

class WorkspaceShortcutsWidget extends Widget
{
    protected string $view = 'filament.widgets.workspace-shortcuts-widget';

    protected int|string|array $columnSpan = [
        'default' => 12,
        'xl' => 4,
    ];

    protected function getViewData(): array
    {
        return [
            'shortcuts' => [
                ['label' => 'Client Workspace', 'url' => ClientResource::getUrl('index')],
                ['label' => 'Job Workspace', 'url' => JobResource::getUrl('index')],
                ['label' => 'Settings', 'url' => ManageSettings::getUrl()],
                ['label' => 'System Health', 'url' => SystemHealth::getUrl()],
            ],
        ];
    }
}
