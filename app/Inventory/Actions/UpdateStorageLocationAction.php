<?php

declare(strict_types=1);

namespace App\Inventory\Actions;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Inventory\Models\StorageLocation;
use App\Inventory\Models\Warehouse;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateStorageLocationAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /** @param array<string, mixed> $data */
    public function execute(StorageLocation $location, array $data, User $actor): StorageLocation
    {
        if (! $actor->hasPermissionTo(PermissionName::StorageLocationsManage->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $location->company_id, $location->tenant_id)) {
            throw new BusinessException('You are not allowed to update this Storage Location.', 403);
        }

        $warehouse = $this->warehouse($data['warehouse_id'] ?? null, $location);
        $code = $this->text($data['code'] ?? null, 'Enter a Storage Location code.', 100, true);
        $name = $this->text($data['name'] ?? null, 'Enter a Storage Location name.');
        $active = (bool) ($data['is_active'] ?? false);

        if ($location->is_default && ! $active) {
            throw new BusinessException('The default Storage Location must remain active.', 422);
        }

        return DB::transaction(function () use ($location, $warehouse, $code, $name, $active, $actor): StorageLocation {
            if (StorageLocation::withTrashed()->where('warehouse_id', $warehouse->getKey())->where('code', $code)->whereKeyNot($location->getKey())->exists()) {
                throw new BusinessException('A Storage Location with this code already exists for this Warehouse.', 422);
            }
            $location->fill(['warehouse_id' => $warehouse->getKey(), 'code' => $code, 'name' => $name, 'is_active' => $active, 'updated_by' => $actor->getKey()])->save();
            $this->logger->log('storage_location.updated', 'Storage Location updated', $actor, $location, ['tenant_id' => $location->tenant_id, 'company_id' => $location->company_id, 'warehouse_id' => $location->warehouse_id, 'storage_location_id' => $location->getKey()]);

            return $location->refresh();
        });
    }

    private function warehouse(mixed $id, StorageLocation $location): Warehouse
    {
        $warehouse = filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) !== false ? Warehouse::query()->find((int) $id) : null;
        if (! $warehouse instanceof Warehouse || $warehouse->tenant_id !== $location->tenant_id || $warehouse->company_id !== $location->company_id) {
            throw new BusinessException('Select a Warehouse in the active company.', 422);
        }
        if (! $warehouse->is_active) {
            throw new BusinessException('A Storage Location cannot be active in an inactive Warehouse.', 422);
        }

        return $warehouse;
    }

    private function text(mixed $value, string $message, int $limit = 180, bool $upper = false): string
    {
        $value = is_string($value) ? Str::squish($value) : '';
        $value = $upper ? Str::upper($value) : $value;
        if ($value === '' || mb_strlen($value) > $limit) {
            throw new BusinessException($message, 422);
        }

        return $value;
    }
}
