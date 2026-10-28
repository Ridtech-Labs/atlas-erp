<?php

declare(strict_types=1);

namespace App\Inventory\Actions;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Inventory\Models\Warehouse;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateWarehouseAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /** @param array<string, mixed> $data */
    public function execute(Warehouse $warehouse, array $data, User $actor): Warehouse
    {
        if (! $actor->hasPermissionTo(PermissionName::WarehousesManage->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $warehouse->company_id, $warehouse->tenant_id)) {
            throw new BusinessException('You are not allowed to update this Warehouse.', 403);
        }

        $code = $this->code($data['code'] ?? null);
        $name = $this->requiredText($data['name'] ?? null, 'Enter a Warehouse name.');

        return DB::transaction(function () use ($warehouse, $data, $actor, $code, $name): Warehouse {
            if (Warehouse::withTrashed()->where('company_id', $warehouse->company_id)->where('code', $code)->whereKeyNot($warehouse->getKey())->exists()) {
                throw new BusinessException('A Warehouse with this code already exists for the active company.', 422);
            }

            $warehouse->fill([
                'code' => $code,
                'name' => $name,
                'notes' => $this->optionalText($data['notes'] ?? null),
                'is_active' => (bool) ($data['is_active'] ?? false),
                'updated_by' => $actor->getKey(),
            ])->save();

            $this->logger->log('warehouse.updated', 'Warehouse updated', $actor, $warehouse, ['tenant_id' => $warehouse->tenant_id, 'company_id' => $warehouse->company_id, 'warehouse_id' => $warehouse->getKey()]);

            return $warehouse->refresh();
        });
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
