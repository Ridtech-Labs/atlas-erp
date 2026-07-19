<?php

declare(strict_types=1);

namespace App\Core\Administration\Providers;

use App\Administration\Policies\ActivityPolicy;
use App\Administration\Policies\HealthPolicy;
use App\Administration\Policies\RolePolicy;
use App\Administration\Policies\SettingPolicy;
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
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

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
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Activity::class, ActivityPolicy::class);
        Gate::define('settings.view', [SettingPolicy::class, 'viewAny']);
        Gate::define('settings.update', [SettingPolicy::class, 'update']);
        Gate::define('health.view', [HealthPolicy::class, 'viewAny']);
    }
}
