<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\CRM\Models\Client;
use App\Operations\Models\Job;
use Filament\Widgets\Widget;

class ClientWorkspaceWidget extends Widget
{
    protected string $view = 'filament.widgets.client-workspace-widget';

    protected int|string|array $columnSpan = 'full';

    public ?Client $record = null;

    protected function getViewData(): array
    {
        $client = $this->record?->loadMissing(['jobs', 'contacts', 'sites']);
        $jobs = $client?->jobs;

        return [
            'client' => $client,
            'stats' => [
                'jobs' => $jobs?->count() ?? 0,
                'contacts' => $client?->contacts->count() ?? 0,
                'sites' => $client?->sites->count() ?? 0,
                'open_work' => $jobs?->filter(function (Job $job): bool {
                    $status = $job->getRawOriginal('status');

                    return ! in_array($status, ['completed', 'cancelled'], true);
                })->count() ?? 0,
            ],
        ];
    }
}
