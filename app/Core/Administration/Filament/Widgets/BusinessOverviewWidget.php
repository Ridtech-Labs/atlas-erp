<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\CRM\Models\Client;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BusinessOverviewWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $today = today();
        $monthStart = now()->startOfMonth();

        return [
            Stat::make('Clients', (string) Client::query()->count())
                ->description('Customer accounts in motion')
                ->icon(Heroicon::OutlinedBuildingOffice2)
                ->chart([3, 5, 5, 6, 8, 9, 12])
                ->color('primary'),
            Stat::make('Jobs Today', (string) Job::query()
                ->whereDate('planned_start_date', $today)
                ->count())
                ->description('Work scheduled to begin today')
                ->icon(Heroicon::OutlinedCalendarDays)
                ->chart([2, 3, 2, 4, 3, 5, 2])
                ->color('gray'),
            Stat::make('Pending Approvals', (string) Job::query()
                ->where('status', JobStatus::PendingApproval->value)
                ->count())
                ->description('Waiting for go-ahead')
                ->icon(Heroicon::OutlinedShieldCheck)
                ->chart([1, 1, 2, 3, 2, 4, 4])
                ->color('warning'),
            Stat::make('Active Jobs', (string) Job::query()
                ->whereIn('status', [
                    JobStatus::Scheduled->value,
                    JobStatus::InProgress->value,
                    JobStatus::OnHold->value,
                ])
                ->count())
                ->description('Scheduled, live, or paused work')
                ->icon(Heroicon::OutlinedClipboardDocumentList)
                ->chart([4, 5, 6, 7, 6, 8, 9])
                ->color('success'),
            Stat::make('Completed This Month', (string) Job::query()
                ->where('status', JobStatus::Completed->value)
                ->where('completed_at', '>=', $monthStart)
                ->count())
                ->description('Closed successfully this month')
                ->icon(Heroicon::OutlinedCheckBadge)
                ->chart([1, 2, 3, 4, 4, 5, 6])
                ->color('info'),
            Stat::make('Overdue Jobs', (string) Job::query()
                ->whereIn('status', [
                    JobStatus::Approved->value,
                    JobStatus::Scheduled->value,
                    JobStatus::InProgress->value,
                    JobStatus::OnHold->value,
                ])
                ->whereDate('planned_end_date', '<', $today)
                ->count())
                ->description('Past planned finish date')
                ->icon(Heroicon::OutlinedExclamationTriangle)
                ->chart([0, 1, 1, 2, 1, 2, 1])
                ->color('danger'),
            Stat::make('Future Revenue', 'Coming soon')
                ->description('Finance intelligence lands in a later sprint')
                ->icon(Heroicon::OutlinedBanknotes)
                ->chart([0, 0, 0, 0, 0, 0, 0])
                ->color('primary'),
        ];
    }
}
