<?php

declare(strict_types=1);

namespace App\Operations\Support;

use App\Core\Shared\Exceptions\BusinessException;
use Carbon\CarbonImmutable;

class JobCardHoursCalculator
{
    /**
     * @param  array<string, mixed>  $data
     * @return array{from_time: string, to_time: string, total_hours: float}|null
     */
    public function calculate(array $data, ?string $cardDate = null): ?array
    {
        $fromTime = trim((string) ($data['from_time'] ?? ''));
        $toTime = trim((string) ($data['to_time'] ?? ''));

        if ($fromTime === '' && $toTime === '') {
            return null;
        }

        if ($fromTime === '' || $toTime === '') {
            throw new BusinessException('Both from and to times are required to calculate total hours.', 422);
        }

        $entryDate = filled($cardDate)
            ? CarbonImmutable::parse($cardDate)->toDateString()
            : now()->toDateString();

        $start = CarbonImmutable::parse("{$entryDate} {$fromTime}");
        $end = CarbonImmutable::parse("{$entryDate} {$toTime}");

        if ($end->lessThanOrEqualTo($start)) {
            $end = $end->addDay();
        }

        return [
            'from_time' => $start->format('H:i:s'),
            'to_time' => CarbonImmutable::parse("{$entryDate} {$toTime}")->format('H:i:s'),
            'total_hours' => round(abs($start->diffInMinutes($end)) / 60, 2),
        ];
    }
}
