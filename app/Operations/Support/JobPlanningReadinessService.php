<?php

declare(strict_types=1);

namespace App\Operations\Support;

use App\Operations\Models\Job;

class JobPlanningReadinessService
{
    /**
     * @return list<array{key:string,label:string,complete:bool}>
     */
    public function checklist(Job $job): array
    {
        return [
            [
                'key' => 'client',
                'label' => 'Client',
                'complete' => filled($job->client_id),
            ],
            [
                'key' => 'description',
                'label' => 'Operational description',
                'complete' => filled($job->title),
            ],
            [
                'key' => 'equipment',
                'label' => $job->isTrucking() ? 'Truck or asset requirement' : 'Equipment',
                'complete' => filled($job->equipment_requirement),
            ],
            [
                'key' => 'operator',
                'label' => $job->isTrucking() ? 'Planned driver or operator' : 'Planned operator',
                'complete' => $job->plannedOperatorName() !== null,
            ],
            [
                'key' => 'planned_start',
                'label' => 'Planned start date',
                'complete' => $job->planned_start_date !== null,
            ],
            [
                'key' => 'shift',
                'label' => 'Shift',
                'complete' => $job->shift !== null,
            ],
        ];
    }

    /**
     * @return list<string>
     */
    public function missingLabels(Job $job): array
    {
        return array_values(array_map(
            static fn (array $item): string => $item['label'],
            array_filter($this->checklist($job), static fn (array $item): bool => ! $item['complete']),
        ));
    }

    public function isReadyToSchedule(Job $job): bool
    {
        return $this->missingLabels($job) === [];
    }
}
