<?php

declare(strict_types=1);

namespace App\Inventory\Services;

use App\Inventory\Enums\InventoryItemStatus;
use App\Inventory\Models\InventoryItem;
use App\Inventory\Models\StockMovement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class InventoryOverviewService
{
    /**
     * @return array{
     *     summary: array{active_items: int, in_stock_items: int, low_stock_items: int, out_of_stock_items: int},
     *     attention_items: Collection<int, InventoryItem>,
     *     recent_movements: Collection<int, StockMovement>
     * }
     */
    public function forCompany(int $tenantId, int $companyId): array
    {
        $activeItems = $this->activeItemsQuery($tenantId, $companyId);

        return [
            'summary' => [
                'active_items' => (clone $activeItems)->count(),
                'in_stock_items' => (clone $activeItems)->inStock()->count(),
                'low_stock_items' => (clone $activeItems)->lowStock()->count(),
                'out_of_stock_items' => (clone $activeItems)->outOfStock()->count(),
            ],
            'attention_items' => (clone $activeItems)
                ->requiringStockAttention()
                ->orderByRaw('CASE WHEN quantity_on_hand <= 0 THEN 0 ELSE 1 END')
                ->orderBy('name')
                ->get(),
            'recent_movements' => StockMovement::query()
                ->where('tenant_id', $tenantId)
                ->where('company_id', $companyId)
                ->with(['item:id,code,name,unit_of_measure', 'performer:id,first_name,last_name'])
                ->latest('occurred_at')
                ->latest('id')
                ->limit(10)
                ->get(),
        ];
    }

    /** @return Builder<InventoryItem> */
    private function activeItemsQuery(int $tenantId, int $companyId): Builder
    {
        return InventoryItem::query()
            ->where('tenant_id', $tenantId)
            ->where('company_id', $companyId)
            ->where('status', InventoryItemStatus::Active->value);
    }
}
