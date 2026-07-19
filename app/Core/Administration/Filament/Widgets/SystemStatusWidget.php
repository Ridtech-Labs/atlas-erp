<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class SystemStatusWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Database Status', $this->databaseStatus()),
            Stat::make('Redis Status', $this->redisStatus()),
            Stat::make('Queue Driver', (string) config('queue.default')),
        ];
    }

    private function databaseStatus(): string
    {
        try {
            DB::connection()->getPdo();

            return 'Connected';
        } catch (\Throwable) {
            return 'Unavailable';
        }
    }

    private function redisStatus(): string
    {
        if (! class_exists(Redis::class)) {
            return 'Not Configured';
        }

        try {
            Redis::connection()->ping();

            return 'Connected';
        } catch (\Throwable) {
            return 'Unavailable';
        }
    }
}
