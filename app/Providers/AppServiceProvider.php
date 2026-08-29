<?php

namespace App\Providers;

use App\Core\Settings\Models\Setting;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Core\Tenancy\Support\CompanyContext;
use App\Core\Tenancy\Support\TenantContext;
use App\CRM\Models\Client;
use App\CRM\Models\ClientContact;
use App\CRM\Models\ClientSite;
use App\Finance\Models\BillingBatch;
use App\Finance\Models\BillingBatchLine;
use App\Finance\Models\BillingRecord;
use App\Finance\Models\RateAgreement;
use App\Finance\Models\RateAgreementLine;
use App\Models\User;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Models\JobCardWorkEntry;
use App\Operations\Models\Waybill;
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
        $this->app->scoped(CompanyContext::class, fn (): CompanyContext => new CompanyContext);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'tenant' => Tenant::class,
            'company' => Company::class,
            'user' => User::class,
            'setting' => Setting::class,
            'client' => Client::class,
            'client_contact' => ClientContact::class,
            'client_site' => ClientSite::class,
            'job' => Job::class,
            'job_card' => JobCard::class,
            'job_card_work_entry' => JobCardWorkEntry::class,
            'waybill' => Waybill::class,
            'rate_agreement' => RateAgreement::class,
            'rate_agreement_line' => RateAgreementLine::class,
            'billing_batch' => BillingBatch::class,
            'billing_batch_line' => BillingBatchLine::class,
            'billing_record' => BillingRecord::class,
        ]);
    }
}
