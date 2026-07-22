<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Administration\Enums\RoleName;
use Filament\Widgets\Widget;
use Spatie\Activitylog\Models\Activity;

class RecentActivityWidget extends Widget
{
    protected string $view = 'filament.widgets.recent-activity-widget';

    protected int|string|array $columnSpan = [
        'default' => 12,
        'xl' => 8,
    ];

    protected function getViewData(): array
    {
        $user = auth()->user();
        $query = Activity::query()->latest();

        if ($user !== null && ! $user->hasRole(RoleName::SuperAdministrator->value)) {
            $query->where(function ($builder) use ($user): void {
                $builder->where('properties->tenant_id', $user->tenant_id)
                    ->orWhereNull('properties->tenant_id');
            });
        }

        return [
            'activities' => $query->limit(8)->get(),
        ];
    }
}
