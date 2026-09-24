<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Administration\Enums\RoleName;
use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\BillingBatches\BillingBatchResource;
use App\Core\Administration\Filament\Resources\JobCards\JobCardResource;
use App\Core\Administration\Filament\Resources\Jobs\JobResource;
use App\Core\Administration\Filament\Resources\Waybills\WaybillResource;
use App\Core\Tenancy\Support\TenantContext;
use App\Models\User;
use App\Operations\Services\OperationsControlService;
use Filament\Widgets\Widget;

class OperationsControlWidget extends Widget
{
    protected string $view = 'filament.widgets.operations-control-widget';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    protected function getViewData(): array
    {
        $user = auth()->user();
        $access = app(AdministrationAccessService::class);
        $companyId = $user instanceof User ? $access->activeCompanyId($user) : null;
        $allowed = $user instanceof User && is_int($companyId) && ($user->hasRole(RoleName::CompanyAdministrator->value) || $user->hasRole(RoleName::OperationsManager->value) || $access->isSuperAdministrator($user));

        return ['visible' => $allowed, 'control' => $allowed ? app(OperationsControlService::class)->forCompany(app(TenantContext::class)->id() ?? $user->tenant_id, $companyId) : [], 'drillDowns' => $allowed ? $this->drillDowns() : []];
    }

    /** @return array<string, string> */
    private function drillDowns(): array
    {
        $jobs = static fn (?string $status = null, ?string $type = null): string => JobResource::getUrl('index', array_filter(['filters' => array_filter(['status' => $status === null ? null : ['value' => $status], 'job_type' => $type === null ? null : ['value' => $type]])]));

        return [
            'total' => $jobs(), 'draft' => $jobs('draft'), 'pending_approval' => $jobs('pending_approval'), 'in_progress' => $jobs('in_progress'), 'completed' => $jobs('completed'),
            'heavy_machinery' => $jobs(null, 'heavy_machinery'), 'trucking' => $jobs(null, 'trucking'),
            'jobs_pending_approval' => $jobs('pending_approval'),
            'job_cards_pending_review' => JobCardResource::getUrl('index', ['filters' => ['approval_status' => ['value' => 'pending_verification']]]),
            'waybills_pending_verification' => WaybillResource::getUrl('index', ['filters' => ['status' => ['value' => 'pending_verification']]]),
            'draft_billing_batches' => BillingBatchResource::getUrl('index', ['filters' => ['status' => ['value' => 'draft']]]),
        ];
    }
}
