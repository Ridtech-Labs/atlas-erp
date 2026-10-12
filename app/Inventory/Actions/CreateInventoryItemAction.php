<?php

declare(strict_types=1);

namespace App\Inventory\Actions;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Tenancy\Models\Company;
use App\Inventory\Enums\InventoryItemStatus;
use App\Inventory\Enums\UnitOfMeasure;
use App\Inventory\Models\InventoryItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateInventoryItemAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /** @param array<string, mixed> $data */
    public function execute(array $data, User $actor): InventoryItem
    {
        $company = $this->activeCompany($actor);
        $attributes = $this->attributes($data);

        return DB::transaction(function () use ($company, $attributes, $actor): InventoryItem {
            $this->ensureCodeIsAvailable($company, $attributes['code']);

            $item = InventoryItem::query()->create([
                ...$attributes,
                'tenant_id' => $company->tenant_id,
                'company_id' => $company->getKey(),
                'quantity_on_hand' => '0.000',
                'created_by' => $actor->getKey(),
                'updated_by' => $actor->getKey(),
            ]);

            $this->logger->log('inventory_item.created', 'Inventory item created', $actor, $item, [
                'tenant_id' => $item->tenant_id,
                'company_id' => $item->company_id,
                'inventory_item_id' => $item->getKey(),
                'item_code' => $item->code,
            ]);

            return $item->refresh();
        });
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{code:string,name:string,category:string,unit_of_measure:UnitOfMeasure,description:?string,minimum_stock_level:string,status:InventoryItemStatus}
     */
    private function attributes(array $data): array
    {
        return InventoryItemData::normalize($data);
    }

    private function activeCompany(User $actor): Company
    {
        if (! $actor->hasPermissionTo(PermissionName::InventoryItemsManage->value)) {
            throw new BusinessException('You are not allowed to manage Inventory items.', 403);
        }

        $company = $this->access->activeCompany($actor);

        if (! $company instanceof Company || ! $this->access->canAccessActiveOperationalCompany($actor, $company->getKey(), $company->tenant_id)) {
            throw new BusinessException('Select an authorized company before managing Inventory items.', 403);
        }

        return $company;
    }

    private function ensureCodeIsAvailable(Company $company, string $code): void
    {
        if (InventoryItem::withTrashed()
            ->where('company_id', $company->getKey())
            ->where('code', $code)
            ->exists()) {
            throw new BusinessException('This Inventory item code already exists for the active company.', 422);
        }
    }
}
