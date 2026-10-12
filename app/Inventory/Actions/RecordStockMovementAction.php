<?php

declare(strict_types=1);

namespace App\Inventory\Actions;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Fleet\Models\FleetAsset;
use App\Inventory\Enums\InventoryItemStatus;
use App\Inventory\Enums\StockMovementType;
use App\Inventory\Models\InventoryItem;
use App\Inventory\Models\StockMovement;
use App\Models\User;
use App\Operations\Models\Job;
use App\Operations\Models\Personnel;
use Carbon\CarbonImmutable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RecordStockMovementAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /** @param array<string, mixed> $data */
    public function execute(InventoryItem $item, StockMovementType $type, array $data, User $actor): StockMovement
    {
        $this->authorize($item, $type, $actor);
        $attributes = $this->attributes($data, $type);
        $logger = $this->logger;

        return DB::transaction(function () use ($item, $type, $attributes, $actor, $logger): StockMovement {
            $item = InventoryItem::query()->whereKey($item->getKey())->lockForUpdate()->firstOrFail();

            if ((string) $item->getRawOriginal('status') !== InventoryItemStatus::Active->value) {
                throw new BusinessException('Inactive Inventory items cannot receive stock movements.', 422);
            }

            $this->validateReferences($item, $attributes);

            $before = $this->decimal((string) $item->getRawOriginal('quantity_on_hand'));
            $this->validateOpeningBalance($item, $type, $before);
            $delta = $type->increasesStock()
                ? $attributes['quantity']
                : bcsub('0', $attributes['quantity'], 3);
            $after = bcadd($before, $delta, 3);

            if (bccomp($after, '0', 3) < 0) {
                throw new BusinessException('This stock movement would reduce the available quantity below zero.', 422);
            }

            $movement = new StockMovement;
            $movement->forceFill([
                'tenant_id' => $item->tenant_id,
                'company_id' => $item->company_id,
                'inventory_item_id' => $item->getKey(),
                'movement_type' => $type,
                'quantity' => $attributes['quantity'],
                'signed_quantity_delta' => $delta,
                'quantity_before' => $before,
                'quantity_after' => $after,
                'reference' => $attributes['reference'],
                'notes' => $attributes['notes'],
                'fleet_asset_id' => $attributes['fleet_asset_id'],
                'job_id' => $attributes['job_id'],
                'personnel_id' => $attributes['personnel_id'],
                'performed_by' => $actor->getKey(),
                'occurred_at' => $attributes['occurred_at'],
            ])->save();

            DB::table('inventory_items')
                ->where('id', $item->getKey())
                ->update([
                    'quantity_on_hand' => $after,
                    'updated_by' => $actor->getKey(),
                    'updated_at' => now(),
                ]);

            $logger->log('stock_movement.recorded', 'Inventory stock movement recorded', $actor, $movement, [
                'tenant_id' => $item->tenant_id,
                'company_id' => $item->company_id,
                'inventory_item_id' => $item->getKey(),
                'stock_movement_id' => $movement->getKey(),
                'movement_type' => $type->value,
                'quantity' => $attributes['quantity'],
                'signed_quantity_delta' => $delta,
                'quantity_before' => $before,
                'quantity_after' => $after,
            ]);

            return $movement->refresh();
        });
    }

    private function authorize(InventoryItem $item, StockMovementType $type, User $actor): void
    {
        $permission = $type->requiresAdjustmentPermission()
            ? PermissionName::StockMovementsAdjust
            : PermissionName::StockMovementsManage;

        if (! $actor->hasPermissionTo($permission->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $item->company_id, $item->tenant_id)) {
            throw new BusinessException('You are not allowed to record this stock movement.', 403);
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{quantity:numeric-string,reference:?string,notes:?string,fleet_asset_id:?int,job_id:?int,personnel_id:?int,occurred_at:CarbonImmutable}
     */
    private function attributes(array $data, StockMovementType $type): array
    {
        $quantity = $this->quantity($data['quantity'] ?? null);
        $notes = filled($data['notes'] ?? null) ? trim((string) $data['notes']) : null;

        if ($type->requiresAdjustmentPermission() && (! is_string($notes) || mb_strlen($notes) < 3)) {
            throw new BusinessException('A meaningful reason is required for a stock adjustment.', 422);
        }

        return [
            'quantity' => $quantity,
            'reference' => filled($data['reference'] ?? null) ? mb_substr(trim((string) $data['reference']), 0, 255) : null,
            'notes' => $notes,
            'fleet_asset_id' => $this->nullableId($data['fleet_asset_id'] ?? null),
            'job_id' => $this->nullableId($data['job_id'] ?? null),
            'personnel_id' => $this->nullableId($data['personnel_id'] ?? null),
            'occurred_at' => $this->occurredAt($data['occurred_at'] ?? null),
        ];
    }

    /** @return numeric-string */
    private function quantity(mixed $value): string
    {
        $quantity = is_scalar($value) ? trim((string) $value) : '';

        if (! is_numeric($quantity) || ! preg_match('/^\d+(?:\.\d{1,3})?$/', $quantity) || bccomp($quantity, '0', 3) <= 0) {
            throw new BusinessException('Quantity must be greater than zero and may have at most three decimal places.', 422);
        }

        return bcadd($quantity, '0', 3);
    }

    /** @return numeric-string */
    private function decimal(string $value): string
    {
        if (! is_numeric($value) || ! preg_match('/^-?\d+(?:\.\d{1,3})?$/', $value)) {
            throw new \LogicException('Inventory quantity projection contains an invalid decimal value.');
        }

        return bcadd($value, '0', 3);
    }

    private function nullableId(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
            throw new BusinessException('An operational context reference is invalid.', 422);
        }

        return (int) $value;
    }

    private function occurredAt(mixed $value): CarbonImmutable
    {
        if ($value instanceof DateTimeInterface) {
            return CarbonImmutable::instance($value);
        }

        try {
            return filled($value) ? CarbonImmutable::parse((string) $value) : CarbonImmutable::now();
        } catch (\Throwable) {
            throw new BusinessException('The stock movement date and time is invalid.', 422);
        }
    }

    /** @param array{fleet_asset_id:?int,job_id:?int,personnel_id:?int} $attributes */
    private function validateReferences(InventoryItem $item, array $attributes): void
    {
        $this->validateReference(FleetAsset::class, $attributes['fleet_asset_id'], $item, 'Fleet asset');
        $this->validateReference(Job::class, $attributes['job_id'], $item, 'Job');
        $this->validateReference(Personnel::class, $attributes['personnel_id'], $item, 'Personnel');
    }

    /** @param class-string<Model> $model */
    private function validateReference(string $model, ?int $id, InventoryItem $item, string $label): void
    {
        if ($id === null) {
            return;
        }

        $reference = $model::query()->find($id);

        if ($reference === null
            || $reference->getAttribute('tenant_id') !== $item->tenant_id
            || $reference->getAttribute('company_id') !== $item->company_id) {
            throw new BusinessException("{$label} must belong to the active company.", 422);
        }
    }

    /** @param numeric-string $before */
    private function validateOpeningBalance(InventoryItem $item, StockMovementType $type, string $before): void
    {
        if ($type !== StockMovementType::OpeningBalance) {
            return;
        }

        if (bccomp($before, '0', 3) !== 0 || StockMovement::query()->where('inventory_item_id', $item->getKey())->exists()) {
            throw new BusinessException('Opening balance can be recorded only once before any other stock movement.', 422);
        }
    }
}
