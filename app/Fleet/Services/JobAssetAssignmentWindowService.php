<?php

declare(strict_types=1);

namespace App\Fleet\Services;

use App\Core\Shared\Exceptions\BusinessException;
use App\Operations\Models\Job;
use Carbon\CarbonImmutable;

class JobAssetAssignmentWindowService
{
    /**
     * @param  array<string, mixed>  $data
     * @return array{start: CarbonImmutable, end: CarbonImmutable}
     */
    public function resolve(Job $job, array $data): array
    {
        $start = $data['planned_start_at'] ?? null;
        $end = $data['planned_end_at'] ?? null;

        if (blank($start) && blank($end)) {
            return $this->defaultForJob($job);
        }

        if (blank($start) || blank($end)) {
            throw new BusinessException('Both planned assignment start and end are required.', 422);
        }

        $window = [
            'start' => CarbonImmutable::parse((string) $start),
            'end' => CarbonImmutable::parse((string) $end),
        ];

        if ($window['end']->lessThanOrEqualTo($window['start'])) {
            throw new BusinessException('The planned assignment end must be after its start.', 422);
        }

        return $window;
    }

    /** @return array{start: CarbonImmutable, end: CarbonImmutable} */
    public function defaultForJob(Job $job): array
    {
        if ($job->planned_start_date === null) {
            throw new BusinessException('Set the Job planned start date before assigning an asset.', 422);
        }

        $startDate = CarbonImmutable::parse((string) $job->getRawOriginal('planned_start_date'));
        $endDate = $job->getRawOriginal('planned_end_date') === null
            ? $startDate
            : CarbonImmutable::parse((string) $job->getRawOriginal('planned_end_date'));

        if ($endDate->lessThan($startDate)) {
            throw new BusinessException('The Job planned end date cannot precede its planned start date.', 422);
        }

        return [
            'start' => $startDate->startOfDay(),
            // Date-only jobs reserve inclusive dates as [start, day-after-end).
            'end' => $endDate->addDay()->startOfDay(),
        ];
    }
}
