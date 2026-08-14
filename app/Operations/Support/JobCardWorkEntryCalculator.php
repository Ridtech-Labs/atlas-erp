<?php

declare(strict_types=1);

namespace App\Operations\Support;

use App\Core\Shared\Exceptions\BusinessException;
use App\Operations\Models\JobCard;
use Carbon\CarbonImmutable;

class JobCardWorkEntryCalculator
{
    /**
     * @param  array<string, mixed>  $data
     * @return array{from_time: string, to_time: string, normal_hours: float, overtime_hours: float, total_hours: float}
     */
    public function calculate(JobCard $jobCard, array $data): array
    {
        $fromTime = (string) ($data['from_time'] ?? '');
        $toTime = (string) ($data['to_time'] ?? '');

        if ($fromTime === '' || $toTime === '') {
            throw new BusinessException('Both from and to times are required for a job card work entry.', 422);
        }

        $cardDate = $jobCard->getAttribute('card_date');
        $entryDate = filled($cardDate)
            ? CarbonImmutable::parse((string) $cardDate)->toDateString()
            : now()->toDateString();
        $start = CarbonImmutable::parse("{$entryDate} {$fromTime}");
        $end = CarbonImmutable::parse("{$entryDate} {$toTime}");

        if ($end->lessThanOrEqualTo($start)) {
            $end = $end->addDay();
        }

        $durationHours = round(abs($start->diffInMinutes($end)) / 60, 2);
        $overtimeHours = round((float) ($data['overtime_hours'] ?? 0), 2);

        if ($overtimeHours > $durationHours) {
            throw new BusinessException('Overtime hours cannot exceed the total shift duration.', 422);
        }

        $normalHours = array_key_exists('normal_hours', $data)
            ? round((float) $data['normal_hours'], 2)
            : round($durationHours - $overtimeHours, 2);

        $totalHours = round($normalHours + $overtimeHours, 2);

        if (abs($durationHours - $totalHours) > 0.01) {
            throw new BusinessException('Normal hours plus overtime must equal the total shift duration.', 422);
        }

        return [
            'from_time' => $start->format('H:i:s'),
            'to_time' => CarbonImmutable::parse("{$entryDate} {$toTime}")->format('H:i:s'),
            'normal_hours' => $normalHours,
            'overtime_hours' => $overtimeHours,
            'total_hours' => $totalHours,
        ];
    }
}
