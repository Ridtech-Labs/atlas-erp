<?php

declare(strict_types=1);

namespace App\Operations\Support;

use App\Operations\Models\JobCard;

class JobCardAggregateService
{
    public function syncTotals(JobCard $jobCard): JobCard
    {
        $totalHours = $jobCard->workEntries()->exists()
            ? round((float) $jobCard->workEntries()->sum('total_hours'), 2)
            : null;

        $jobCard->forceFill([
            'total_hours' => $totalHours,
        ])->saveQuietly();

        return $jobCard->refresh();
    }
}
