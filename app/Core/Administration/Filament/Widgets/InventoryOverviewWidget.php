<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Widgets;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Tenancy\Support\TenantContext;
use App\Inventory\Services\InventoryOverviewService;
use App\Models\User;
use Filament\Widgets\Widget;

class InventoryOverviewWidget extends Widget
{
    protected string $view = 'filament.widgets.inventory-overview-widget';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    /** @return array<string, mixed> */
    protected function getViewData(): array
    {
        $user = auth()->user();
        $companyId = $user instanceof User ? app(AdministrationAccessService::class)->activeCompanyId($user) : null;

        if (! $user instanceof User || ! is_int($companyId)) {
            return ['overview' => null];
        }

        return [
            'overview' => app(InventoryOverviewService::class)->forCompany(
                app(TenantContext::class)->id() ?? $user->tenant_id,
                $companyId,
            ),
        ];
    }
}
