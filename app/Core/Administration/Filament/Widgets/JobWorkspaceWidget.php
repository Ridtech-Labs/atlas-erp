<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Operations\Models\Job;
use Filament\Widgets\Widget;

class JobWorkspaceWidget extends Widget
{
    protected string $view = 'filament.widgets.job-workspace-widget';

    protected int|string|array $columnSpan = 'full';

    public ?Job $record = null;

    protected function getViewData(): array
    {
        return [
            'job' => $this->record?->loadMissing(['client', 'site']),
        ];
    }
}
