<?php

declare(strict_types=1);

namespace App\Core\Administration\Providers;

use App\Core\Settings\Repositories\EloquentSettingRepository;
use App\Core\Settings\Repositories\SettingRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SettingRepositoryInterface::class, EloquentSettingRepository::class);
    }
}
