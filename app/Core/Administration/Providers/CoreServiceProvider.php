<?php

declare(strict_types=1);

namespace App\Core\Administration\Providers;

use App\Administration\Policies\ActivityPolicy;
use App\Administration\Policies\RolePolicy;
use App\Administration\Policies\TenantPolicy;
use App\Administration\Policies\UserPolicy;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Settings\Repositories\EloquentSettingRepository;
use App\Core\Settings\Repositories\SettingRepositoryInterface;
use App\Core\Tenancy\Models\Tenant;
use App\CRM\Models\Client;
use App\CRM\Models\ClientContact;
use App\CRM\Models\ClientSite;
use App\CRM\Policies\ClientContactPolicy;
use App\CRM\Policies\ClientPolicy;
use App\CRM\Policies\ClientSitePolicy;
use App\Finance\Models\BillingBatch;
use App\Finance\Models\BillingRecord;
use App\Finance\Models\RateAgreement;
use App\Finance\Policies\BillingBatchPolicy;
use App\Finance\Policies\BillingRecordPolicy;
use App\Finance\Policies\RateAgreementPolicy;
use App\Models\User;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Policies\JobCardPolicy;
use App\Operations\Policies\JobPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
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
        Gate::policy(Client::class, ClientPolicy::class);
        Gate::policy(ClientContact::class, ClientContactPolicy::class);
        Gate::policy(ClientSite::class, ClientSitePolicy::class);
        Gate::policy(Job::class, JobPolicy::class);
        Gate::policy(JobCard::class, JobCardPolicy::class);
        Gate::policy(RateAgreement::class, RateAgreementPolicy::class);
        Gate::policy(BillingBatch::class, BillingBatchPolicy::class);
        Gate::policy(BillingRecord::class, BillingRecordPolicy::class);

        Gate::define('manageSettings', fn (User $user): bool => $this->hasPermission($user, 'settings.manage') || $this->hasPermission($user, 'settings.update'));
        Gate::define('viewHealth', fn (User $user): bool => $this->hasPermission($user, 'health.view'));
        Gate::define('manageRoles', fn (User $user): bool => $this->hasPermission($user, 'roles.manage') || $this->hasPermission($user, 'roles.view'));
    }

    private function hasPermission(User $user, string $permission): bool
    {
        try {
            return $user->hasPermissionTo($permission);
        } catch (PermissionDoesNotExist) {
            return false;
        }
    }
}
