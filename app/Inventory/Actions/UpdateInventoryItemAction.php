<?php

declare(strict_types=1);

namespace App\Inventory\Actions;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Inventory\Models\InventoryItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateInventoryItemAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /** @param array<string, mixed> $data */
    public function execute(InventoryItem $item, array $data, User $actor): InventoryItem
    {
        if (! $actor->hasPermissionTo(PermissionName::InventoryItemsManage->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $item->company_id, $item->tenant_id)) {
            throw new BusinessException('You are not allowed to update this Inventory item.', 403);
        }

        $attributes = InventoryItemData::normalize($data);

        return DB::transaction(function () use ($item, $attributes, $actor): InventoryItem {
            $this->ensureCodeIsAvailable($item, $attributes['code']);

            $item->fill([
                ...$attributes,
                'updated_by' => $actor->getKey(),
            ])->save();

            $this->logger->log('inventory_item.updated', 'Inventory item updated', $actor, $item, [
                'tenant_id' => $item->tenant_id,
                'company_id' => $item->company_id,
                'inventory_item_id' => $item->getKey(),
                'item_code' => $item->code,
            ]);

            return $item->refresh();
        });
    }

    private function ensureCodeIsAvailable(InventoryItem $item, string $code): void
    {
        if (InventoryItem::withTrashed()
            ->where('company_id', $item->company_id)
            ->where('code', $code)
            ->whereKeyNot($item->getKey())
            ->exists()) {
            throw new BusinessException('This Inventory item code already exists for the active company.', 422);
        }
    }
}
