<?php

declare(strict_types=1);

namespace App\Core\Administration\Providers;

use App\Administration\Policies\TenantPolicy;
use App\Administration\Policies\UserPolicy;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Settings\Repositories\EloquentSettingRepository;
use App\Core\Settings\Repositories\SettingRepositoryInterface;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SettingRepositoryInterface::class, EloquentSettingRepository::class);
        $this->app->singleton(AdministrationAccessService::class);
        $this->app->singleton(AdministrationActivityLogger::class);
    }

    public function boot(): void
    {
        Gate::policy(Tenant::class, TenantPolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        Gate::define('viewHealth', fn (User $user): bool => $user->can('health.view'));
        Gate::define('manageSettings', fn (User $user): bool => $user->can('settings.manage'));
    }
}
