<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Core\Tenancy\Models\Tenant;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use Filament\Widgets\Widget;

class WelcomeHeroWidget extends Widget
{
    protected string $view = 'filament.widgets.welcome-hero-widget';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $user = auth()->user();
        $tenant = $user?->tenant;
        $today = today();

        $pendingApprovals = Job::query()
            ->where('status', JobStatus::PendingApproval->value)
            ->count();

        $jobsStartingToday = Job::query()
            ->whereDate('planned_start_date', $today)
            ->count();

        $overdueJobs = Job::query()
            ->whereIn('status', [
                JobStatus::Approved->value,
                JobStatus::Scheduled->value,
                JobStatus::InProgress->value,
                JobStatus::OnHold->value,
            ])
            ->whereDate('planned_end_date', '<', $today)
            ->count();

        return [
            'user' => $user,
            'tenantName' => $tenant instanceof Tenant ? $tenant->name : 'Atlas ERP',
            'greeting' => $this->greeting(),
            'operationalSummary' => [
                ['label' => 'Approvals pending', 'value' => $pendingApprovals],
                ['label' => 'Jobs start today', 'value' => $jobsStartingToday],
                ['label' => 'Overdue jobs', 'value' => $overdueJobs],
            ],
        ];
    }

    private function greeting(): string
    {
        $hour = now()->hour;

        return match (true) {
            $hour < 12 => 'Good morning',
            $hour < 18 => 'Good afternoon',
            default => 'Good evening',
        };
    }
}
