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

class CreateWarehouseAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /** @param array<string, mixed> $data */
    public function execute(array $data, User $actor): Warehouse
    {
        $company = $this->activeCompany($actor);
        $attributes = $this->attributes($data);

        return DB::transaction(function () use ($company, $attributes, $actor): Warehouse {
            $this->ensureCodeIsAvailable($company, $attributes['code']);

            $warehouse = Warehouse::query()->create([
                ...$attributes,
                'tenant_id' => $company->tenant_id,
                'company_id' => $company->getKey(),
                'created_by' => $actor->getKey(),
                'updated_by' => $actor->getKey(),
            ]);

            $default = StorageLocation::query()->create([
                'tenant_id' => $warehouse->tenant_id,
                'company_id' => $warehouse->company_id,
                'warehouse_id' => $warehouse->getKey(),
                'code' => 'DEFAULT',
                'name' => 'Default storage location',
                'is_active' => true,
                'is_default' => true,
                'created_by' => $actor->getKey(),
                'updated_by' => $actor->getKey(),
            ]);

            $this->logger->log('warehouse.created', 'Warehouse created', $actor, $warehouse, [
                'tenant_id' => $warehouse->tenant_id,
                'company_id' => $warehouse->company_id,
                'warehouse_id' => $warehouse->getKey(),
                'default_storage_location_id' => $default->getKey(),
            ]);

            return $warehouse->refresh();
        });
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{code:string,name:string,notes:?string,is_active:bool}
     */
    private function attributes(array $data): array
    {
        return [
            'code' => $this->code($data['code'] ?? null),
            'name' => $this->requiredText($data['name'] ?? null, 'Enter a Warehouse name.'),
            'notes' => $this->optionalText($data['notes'] ?? null),
            'is_active' => (bool) ($data['is_active'] ?? true),
        ];
    }

    private function activeCompany(User $actor): Company
    {
        if (! $actor->hasPermissionTo(PermissionName::WarehousesManage->value)) {
            throw new BusinessException('You are not allowed to manage Warehouses.', 403);
        }

        $company = $this->access->activeCompany($actor);

        if (! $company instanceof Company || ! $this->access->canAccessActiveOperationalCompany($actor, $company->getKey(), $company->tenant_id)) {
            throw new BusinessException('Select an authorized company before managing Warehouses.', 403);
        }

        return $company;
    }

    private function ensureCodeIsAvailable(Company $company, string $code): void
    {
        if (Warehouse::withTrashed()->where('company_id', $company->getKey())->where('code', $code)->exists()) {
            throw new BusinessException('A Warehouse with this code already exists for the active company.', 422);
        }
    }

    private function code(mixed $value): string
    {
        return $this->requiredText($value, 'Enter a Warehouse code.', 100, true);
    }

    private function requiredText(mixed $value, string $message, int $limit = 180, bool $upper = false): string
    {
        $value = is_string($value) ? Str::squish($value) : '';
        $value = $upper ? Str::upper($value) : $value;
        if ($value === '' || mb_strlen($value) > $limit) {
            throw new BusinessException($message, 422);
        }

        return $value;
    }

    private function optionalText(mixed $value): ?string
    {
        $value = is_string($value) ? trim($value) : '';

        return $value === '' ? null : mb_substr($value, 0, 65535);
    }
}
