<?php

declare(strict_types=1);

namespace App\Inventory\Models;

use App\Core\Shared\Concerns\BelongsToTenant;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Fleet\Models\FleetAsset;
use App\Inventory\Enums\StockMovementType;
use App\Models\User;
use App\Operations\Models\Job;
use App\Operations\Models\Personnel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

class StockMovement extends Model
{
    use BelongsToTenant;
    use HasPublicUuid;

    /**
     * Movements are created exclusively by RecordStockMovementAction.
     * Normal mass-assignment paths cannot fabricate ledger rows.
     *
     * @var array<int, string>
     */
    protected $guarded = ['*'];

    protected function casts(): array
    {
        return [
            'movement_type' => StockMovementType::class,
            'quantity' => 'decimal:3',
            'signed_quantity_delta' => 'decimal:3',
            'quantity_before' => 'decimal:3',
            'quantity_after' => 'decimal:3',
            'occurred_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::updating(static function (): never {
            throw new LogicException('Stock movements are immutable. Record an adjustment instead.');
        });

        static::deleting(static function (): never {
            throw new LogicException('Stock movements are immutable and cannot be deleted.');
        });
    }

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /** @return BelongsTo<Company, $this> */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /** @return BelongsTo<InventoryItem, $this> */
    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    /** @return BelongsTo<FleetAsset, $this> */
    public function fleetAsset(): BelongsTo
    {
        return $this->belongsTo(FleetAsset::class);
    }

    /** @return BelongsTo<Job, $this> */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /** @return BelongsTo<Personnel, $this> */
    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }

    /** @return BelongsTo<User, $this> */
    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
