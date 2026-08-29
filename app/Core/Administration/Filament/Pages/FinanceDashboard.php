<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Pages;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Support\TenantContext;
use App\Finance\Enums\BillingRecordStatus;
use App\Finance\Models\BillingRecord;
use App\Finance\Services\FinanceReportingService;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use UnitEnum;

class FinanceDashboard extends Page
{
    protected static string|UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Finance Dashboard';

    protected static ?int $navigationSort = 10;

    protected static ?string $title = 'Finance Dashboard';

    protected static ?string $slug = 'finance-dashboard';

    protected string $view = 'filament.pages.finance-dashboard';

    /** @var array{client_id: string, status: string, currency: string, from: string, until: string} */
    public array $filters = [
        'client_id' => '',
        'status' => '',
        'currency' => '',
        'from' => '',
        'until' => '',
    ];

    public function updatedFilters(): void
    {
        $this->dispatch('$refresh');
    }

    /** @return array<string, mixed> */
    public function report(): array
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return [];
        }

        $access = app(AdministrationAccessService::class);
        $companyId = $access->activeCompanyId($user);
        $tenantId = app(TenantContext::class)->id() ?? $user->tenant_id;

        if (! is_int($companyId)) {
            return [];
        }

        return app(FinanceReportingService::class)->dashboard($tenantId, $companyId, $this->filters);
    }

    /** @return array<int, string> */
    public function clients(): array
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return [];
        }

        $companyId = app(AdministrationAccessService::class)->activeCompanyId($user);

        if (! is_int($companyId)) {
            return [];
        }

        return $this->companyRecordsQuery($user, $companyId)
            ->with('client:id,legal_name')
            ->get()
            ->pluck('client.legal_name', 'client_id')
            ->filter()
            ->unique()
            ->sort()
            ->all();
    }

    /** @return list<string> */
    public function currencies(): array
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return [];
        }

        $companyId = app(AdministrationAccessService::class)->activeCompanyId($user);

        if (! is_int($companyId)) {
            return [];
        }

        return array_values($this->companyRecordsQuery($user, $companyId)
            ->distinct()
            ->orderBy('currency')
            ->pluck('currency')
            ->all());
    }

    /** @return list<BillingRecordStatus> */
    public function statuses(): array
    {
        return BillingRecordStatus::cases();
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        try {
            return $user->hasPermissionTo(PermissionName::BillingRecordsView->value)
                && app(AdministrationAccessService::class)->hasActiveCompanyContext($user);
        } catch (PermissionDoesNotExist) {
            return false;
        }
    }

    /** @return Builder<BillingRecord> */
    private function companyRecordsQuery(User $user, int $companyId): Builder
    {
        return BillingRecord::query()
            ->where('tenant_id', app(TenantContext::class)->id() ?? $user->tenant_id)
            ->where('company_id', $companyId);
    }
}
