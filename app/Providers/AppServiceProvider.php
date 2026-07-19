<?php

namespace App\Providers;

use App\Core\Settings\Models\Setting;
use App\Core\Tenancy\Models\Tenant;
use App\Core\Tenancy\Support\TenantContext;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(TenantContext::class, fn (): TenantContext => new TenantContext);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'tenant' => Tenant::class,
            'user' => User::class,
            'setting' => Setting::class,
        ]);
    }
}
