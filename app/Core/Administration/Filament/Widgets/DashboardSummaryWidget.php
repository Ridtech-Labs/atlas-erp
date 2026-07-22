<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use Filament\Widgets\Widget;

class DashboardSummaryWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-summary-widget';

    protected int|string|array $columnSpan = 'full';
}
