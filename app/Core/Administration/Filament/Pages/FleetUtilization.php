<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Pages;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Support\TenantContext;
use App\Fleet\Services\FleetUtilizationService;
use App\Models\User;
use Carbon\CarbonImmutable;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class FleetUtilization extends Page
{
    protected static ?string $navigationLabel = 'Fleet Utilization';

    protected static ?string $title = 'Fleet Utilization';

    protected static ?string $slug = 'fleet-utilization';

    protected string $view = 'filament.pages.fleet-utilization';

    public string $from;

    public string $until;

    public function mount(): void
    {
        $this->from = now()->startOfMonth()->toDateString();
        $this->until = now()->endOfMonth()->toDateString();
    }

    /** @return Collection<int, mixed> */
    public function rows(): Collection
    {
        $user = auth()->user();
        $companyId = $user instanceof User ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;
        if (! $user instanceof User || ! is_int($companyId)) {
            return collect();
        }

        return app(FleetUtilizationService::class)->forCompany(app(TenantContext::class)->id() ?? $user->tenant_id, $companyId, CarbonImmutable::parse($this->from)->startOfDay(), CarbonImmutable::parse($this->until)->endOfDay());
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->hasPermissionTo(PermissionName::FleetAssetsView->value) && app(AdministrationAccessService::class)->hasActiveCompanyContext($user);
    }
}
