<?php

declare(strict_types=1);

namespace App\Finance\Services;

use App\Core\Tenancy\Models\Company;
use App\Finance\Enums\BillingRecordStatus;
use App\Finance\Models\BillingRecord;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class FinanceReportingService
{
    /**
     * @param  array<string, scalar|null>  $filters
     * @return array<string, mixed>
     */
    public function dashboard(int $tenantId, int $companyId, array $filters = []): array
    {
        $today = CarbonImmutable::today();
        $monthStart = $today->startOfMonth();
        $yearStart = $today->startOfYear();
        $currencies = $this->reportingCurrencies($tenantId, $companyId);

        $issuedUnpaid = $this->applyCommonFilters(
            $this->baseQuery($tenantId, $companyId)
                ->where('status', BillingRecordStatus::Issued->value),
            $filters,
        );

        $this->applyDateRange($issuedUnpaid, $filters, 'issued_at');

        return [
            'revenue_mtd' => $this->moneyTotals(
                $this->baseQuery($tenantId, $companyId)
                    ->whereIn('status', $this->realizedStatuses())
                    ->whereBetween('paid_at', [$monthStart, $today]),
            ),
            'revenue_ytd' => $this->moneyTotals(
                $this->baseQuery($tenantId, $companyId)
                    ->whereIn('status', $this->realizedStatuses())
                    ->whereBetween('paid_at', [$yearStart, $today]),
            ),
            'issued_unpaid' => $this->withZeroBuckets($this->moneyTotals($issuedUnpaid), $currencies),
            'paid_this_month' => $this->baseQuery($tenantId, $companyId)
                ->whereIn('status', $this->realizedStatuses())
                ->whereBetween('paid_at', [$monthStart, $today])
                ->count(),
            'status_counts' => $this->statusCounts($tenantId, $companyId, $filters),
            'recent_records' => $this->recentRecords($tenantId, $companyId, $filters),
            'top_clients' => $this->topClients($tenantId, $companyId, $filters, $yearStart, $today),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function revenueMtd(int $tenantId, int $companyId): array
    {
        $today = CarbonImmutable::today();

        return $this->moneyTotals(
            $this->baseQuery($tenantId, $companyId)
                ->whereIn('status', $this->realizedStatuses())
                ->whereBetween('paid_at', [$today->startOfMonth(), $today]),
        );
    }

    /** @return Builder<BillingRecord> */
    private function baseQuery(int $tenantId, int $companyId): Builder
    {
        return BillingRecord::query()
            ->where('billing_records.tenant_id', $tenantId)
            ->where('billing_records.company_id', $companyId);
    }

    /**
     * @param  Builder<BillingRecord>  $query
     * @return array<string, string>
     */
    private function moneyTotals(Builder $query): array
    {
        /** @var Collection<int, object{currency: string, total: string}> $rows */
        $rows = $query
            ->selectRaw('currency, SUM(receipt_amount) as total')
            ->whereNotNull('receipt_amount')
            ->groupBy('currency')
            ->orderBy('currency')
            ->get();

        return $rows->mapWithKeys(
            fn (object $row): array => [$row->currency => number_format((float) $row->total, 2)],
        )->all();
    }

    /**
     * @return array<string, int>
     */
    /**
     * @param  array<string, scalar|null>  $filters
     * @return array<string, int>
     */
    private function statusCounts(int $tenantId, int $companyId, array $filters): array
    {
        $query = $this->applyCommonFilters($this->baseQuery($tenantId, $companyId), $filters);

        $this->applyLifecycleDateRange($query, $filters);

        $counts = $query
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return collect(BillingRecordStatus::cases())
            ->mapWithKeys(fn (BillingRecordStatus $status): array => [$status->value => (int) ($counts[$status->value] ?? 0)])
            ->all();
    }

    /**
     * @param  array<string, scalar|null>  $filters
     * @return EloquentCollection<int, BillingRecord>
     */
    private function recentRecords(int $tenantId, int $companyId, array $filters): EloquentCollection
    {
        $query = $this->applyCommonFilters($this->baseQuery($tenantId, $companyId)->with('client'), $filters);
        $this->applyLifecycleDateRange($query, $filters);

        return $query
            ->orderByRaw('COALESCE(paid_at, issued_at, created_at) desc')
            ->limit(8)
            ->get();
    }

    /**
     * @param  array<string, scalar|null>  $filters
     * @return Collection<int, array{client_name: string, currency: string, total: float}>
     */
    private function topClients(int $tenantId, int $companyId, array $filters, CarbonImmutable $yearStart, CarbonImmutable $today): Collection
    {
        $query = $this->baseQuery($tenantId, $companyId)
            ->whereIn('billing_records.status', $this->realizedStatuses())
            ->whereBetween('billing_records.paid_at', [$yearStart, $today])
            ->join('clients', 'clients.id', '=', 'billing_records.client_id')
            ->selectRaw('COALESCE(clients.trading_name, clients.legal_name) as client_name, billing_records.currency, SUM(billing_records.receipt_amount) as total')
            ->groupByRaw('COALESCE(clients.trading_name, clients.legal_name), billing_records.currency')
            ->orderByDesc('total')
            ->limit(5);

        $query = $this->applyCommonFilters($query, $filters, false);
        $this->applyDateRange($query, $filters, 'billing_records.paid_at');

        return $query
            ->get()
            ->map(fn (BillingRecord $record): array => [
                'client_name' => (string) $record->getAttribute('client_name'),
                'currency' => (string) $record->getAttribute('currency'),
                'total' => (float) $record->getAttribute('total'),
            ]);
    }

    /**
     * @param  Builder<BillingRecord>  $query
     * @param  array<string, scalar|null>  $filters
     * @return Builder<BillingRecord>
     */
    private function applyCommonFilters(Builder $query, array $filters, bool $applyStatus = true, string $table = 'billing_records'): Builder
    {
        if (filled($filters['client_id'] ?? null)) {
            $query->where("{$table}.client_id", $filters['client_id']);
        }

        if ($applyStatus && filled($filters['status'] ?? null)) {
            $query->where("{$table}.status", $filters['status']);
        }

        if (filled($filters['currency'] ?? null)) {
            $query->where("{$table}.currency", $filters['currency']);
        }

        return $query;
    }

    /**
     * @param  Builder<BillingRecord>  $query
     * @param  array<string, scalar|null>  $filters
     */
    private function applyDateRange(Builder $query, array $filters, string $dateColumn): void
    {
        $from = $filters['from'] ?? null;

        if (is_string($from) && filled($from)) {
            $query->whereDate($dateColumn, '>=', $from);
        }

        $until = $filters['until'] ?? null;

        if (is_string($until) && filled($until)) {
            $query->whereDate($dateColumn, '<=', $until);
        }
    }

    /**
     * Date-filtered status counts use the financial lifecycle date: paid_at for
     * realized records and issued_at for outstanding records. Drafts have no
     * financial date and are intentionally excluded from a dated report.
     *
     * @param  Builder<BillingRecord>  $query
     * @param  array<string, scalar|null>  $filters
     */
    private function applyLifecycleDateRange(Builder $query, array $filters): void
    {
        if (! $this->hasDateRange($filters)) {
            return;
        }

        $query->where(function (Builder $lifecycle) use ($filters): void {
            $lifecycle->where(function (Builder $realized) use ($filters): void {
                $realized->whereIn('status', $this->realizedStatuses());
                $this->applyDateRange($realized, $filters, 'paid_at');
            })->orWhere(function (Builder $issued) use ($filters): void {
                $issued->where('status', BillingRecordStatus::Issued->value);
                $this->applyDateRange($issued, $filters, 'issued_at');
            });
        });
    }

    /** @param array<string, scalar|null> $filters */
    private function hasDateRange(array $filters): bool
    {
        return filled($filters['from'] ?? null) || filled($filters['until'] ?? null);
    }

    /** @return list<string> */
    private function reportingCurrencies(int $tenantId, int $companyId): array
    {
        $currencies = $this->baseQuery($tenantId, $companyId)
            ->distinct()
            ->pluck('currency');
        $companyCurrency = Company::query()
            ->whereKey($companyId)
            ->where('tenant_id', $tenantId)
            ->value('currency');

        return array_values($currencies
            ->push($companyCurrency)
            ->filter(fn (mixed $currency): bool => is_string($currency) && $currency !== '')
            ->unique()
            ->sort()
            ->all());
    }

    /**
     * @param  array<string, string>  $totals
     * @param  list<string>  $currencies
     * @return array<string, string>
     */
    private function withZeroBuckets(array $totals, array $currencies): array
    {
        if ($currencies === []) {
            return $totals;
        }

        return collect($currencies)
            ->mapWithKeys(fn (string $currency): array => [$currency => $totals[$currency] ?? '0.00'])
            ->all();
    }

    /** @return list<string> */
    private function realizedStatuses(): array
    {
        return [BillingRecordStatus::Paid->value, BillingRecordStatus::Closed->value];
    }
}
