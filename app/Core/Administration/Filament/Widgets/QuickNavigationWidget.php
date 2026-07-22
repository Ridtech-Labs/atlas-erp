<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Core\Administration\Filament\Resources\Clients\ClientResource;
use App\Core\Administration\Filament\Resources\Jobs\JobResource;
use App\CRM\Models\Client;
use App\Operations\Models\Job;
use Filament\Widgets\Widget;

class QuickNavigationWidget extends Widget
{
    protected string $view = 'filament.widgets.quick-navigation-widget';

    protected int|string|array $columnSpan = [
        'default' => 12,
        'xl' => 4,
    ];

    protected function getViewData(): array
    {
        return [
            'actionCards' => [
                [
                    'title' => 'Create client',
                    'description' => 'Open a new customer workspace.',
                    'url' => ClientResource::getUrl('create'),
                ],
                [
                    'title' => 'Create job',
                    'description' => 'Capture new operational work.',
                    'url' => JobResource::getUrl('create'),
                ],
                [
                    'title' => 'Add contact',
                    'description' => 'Jump into a client workspace to add key people.',
                    'url' => ClientResource::getUrl('index'),
                ],
                [
                    'title' => 'Add site',
                    'description' => 'Open a client account to register a location.',
                    'url' => ClientResource::getUrl('index'),
                ],
                [
                    'title' => 'Review pipeline',
                    'description' => 'See work by status, site, and timing.',
                    'url' => JobResource::getUrl('index'),
                ],
            ],
            'upcomingJobs' => Job::query()->limit(0)->get(),
            'recentClients' => Client::query()->limit(0)->get(),
        ];
    }
}
