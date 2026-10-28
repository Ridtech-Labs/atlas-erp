<?php

declare(strict_types=1);

namespace App\Inventory\Actions;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Tenancy\Models\Company;
use App\Inventory\Models\StorageLocation;
use App\Inventory\Models\Warehouse;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateStorageLocationAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /** @param array<string, mixed> $data */
    public function execute(array $data, User $actor): StorageLocation
    {
        $company = $this->activeCompany($actor);
        $warehouse = $this->warehouse($data['warehouse_id'] ?? null, $company);
        $code = $this->code($data['code'] ?? null);
        $name = $this->name($data['name'] ?? null);

        return DB::transaction(function () use ($company, $warehouse, $code, $name, $data, $actor): StorageLocation {
            if (StorageLocation::withTrashed()->where('warehouse_id', $warehouse->getKey())->where('code', $code)->exists()) {
                throw new BusinessException('A Storage Location with this code already exists for this Warehouse.', 422);
            }

            $location = StorageLocation::query()->create([
                'tenant_id' => $company->tenant_id,
                'company_id' => $company->getKey(),
                'warehouse_id' => $warehouse->getKey(),
                'code' => $code,
                'name' => $name,
                'is_active' => (bool) ($data['is_active'] ?? true),
                'is_default' => false,
                'created_by' => $actor->getKey(),
                'updated_by' => $actor->getKey(),
            ]);

            $this->logger->log('storage_location.created', 'Storage Location created', $actor, $location, ['tenant_id' => $location->tenant_id, 'company_id' => $location->company_id, 'warehouse_id' => $location->warehouse_id, 'storage_location_id' => $location->getKey()]);

            return $location->refresh();
        });
    }

    private function activeCompany(User $actor): Company
    {
        if (! $actor->hasPermissionTo(PermissionName::StorageLocationsManage->value)) {
            throw new BusinessException('You are not allowed to manage Storage Locations.', 403);
        }
        $company = $this->access->activeCompany($actor);
        if (! $company instanceof Company || ! $this->access->canAccessActiveOperationalCompany($actor, $company->getKey(), $company->tenant_id)) {
            throw new BusinessException('Select an authorized company before managing Storage Locations.', 403);
        }

        return $company;
    }

    private function warehouse(mixed $id, Company $company): Warehouse
    {
        $warehouse = filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) !== false ? Warehouse::query()->find((int) $id) : null;
        if (! $warehouse instanceof Warehouse || $warehouse->tenant_id !== $company->tenant_id || $warehouse->company_id !== $company->getKey()) {
            throw new BusinessException('Select a Warehouse in the active company.', 422);
        }
        if (! $warehouse->is_active) {
            throw new BusinessException('Storage Locations can be added only to an active Warehouse.', 422);
        }

        return $warehouse;
    }

    private function code(mixed $value): string
    {
        return $this->text($value, 'Enter a Storage Location code.', 100, true);
    }

    private function name(mixed $value): string
    {
        return $this->text($value, 'Enter a Storage Location name.');
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
