<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class SystemHealth extends Page
{
    protected static string|UnitEnum|null $navigationGroup = 'Configuration';

    protected static ?string $navigationLabel = 'System Health';

    protected static ?string $slug = 'system-health';

    protected string $view = 'filament.pages.system-health';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('health.view') ?? false;
    }

    /**
     * @return list<array{label: string, status: string, detail: string}>
     */
    public function checks(): array
    {
        return [
            $this->check('Application reachable', 'ok', 'Filament administration panel is responding.'),
            $this->databaseCheck(),
            $this->cacheCheck(),
            $this->queueCheck(),
            $this->storageCheck(),
            $this->diskCheck(),
            $this->environmentCheck(),
        ];
    }

    /**
     * @return array{label: string, status: string, detail: string}
     */
    private function check(string $label, string $status, string $detail): array
    {
        return compact('label', 'status', 'detail');
    }

    /**
     * @return array{label: string, status: string, detail: string}
     */
    private function databaseCheck(): array
    {
        try {
            DB::connection()->getPdo();

            return $this->check('Database connection', 'ok', 'Primary database connection is available.');
        } catch (\Throwable) {
            return $this->check('Database connection', 'failed', 'The configured database is not reachable.');
        }
    }

    /**
     * @return array{label: string, status: string, detail: string}
     */
    private function cacheCheck(): array
    {
        try {
            Cache::put('atlas-health-check', 'ok', 60);

            return $this->check('Cache availability', 'ok', 'Configured cache store is writable.');
        } catch (\Throwable) {
            return $this->check('Cache availability', 'warning', 'Cache store is unavailable. Local development may fall back to database or file cache.');
        }
    }

    /**
     * @return array{label: string, status: string, detail: string}
     */
    private function queueCheck(): array
    {
        $driver = (string) config('queue.default');

        return $this->check('Queue configuration', in_array($driver, ['redis', 'database', 'sync'], true) ? 'ok' : 'warning', "Queue driver: {$driver}");
    }

    /**
     * @return array{label: string, status: string, detail: string}
     */
    private function storageCheck(): array
    {
        try {
            Storage::disk(config('filesystems.default'))->put('.healthcheck', 'ok');
            Storage::disk(config('filesystems.default'))->delete('.healthcheck');

            return $this->check('Storage write access', 'ok', 'Default filesystem disk is writable.');
        } catch (\Throwable) {
            return $this->check('Storage write access', 'failed', 'Default filesystem disk is not writable.');
        }
    }

    /**
     * @return array{label: string, status: string, detail: string}
     */
    private function diskCheck(): array
    {
        $freeBytes = @disk_free_space(storage_path()) ?: 0;

        return $this->check('Disk usage', $freeBytes > 0 ? 'ok' : 'warning', sprintf('Free storage space: %.2f GB', $freeBytes / 1024 / 1024 / 1024));
    }

    /**
     * @return array{label: string, status: string, detail: string}
     */
    private function environmentCheck(): array
    {
        $environment = app()->environment();
        $debug = (bool) config('app.debug');

        if ($environment !== 'local' && $debug) {
            return $this->check('Environment configuration', 'warning', 'Debug mode is enabled outside the local environment.');
        }

        return $this->check('Environment configuration', 'ok', "Environment: {$environment}");
    }
}
