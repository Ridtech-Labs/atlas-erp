<?php

declare(strict_types=1);

namespace App\Inventory\Models;

use App\Core\Shared\Concerns\BelongsToTenant;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Inventory\Enums\InventoryItemStatus;
use App\Inventory\Enums\InventoryStockStatus;
use App\Inventory\Enums\UnitOfMeasure;
use App\Models\User;
use Database\Factories\InventoryItemFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use LogicException;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class InventoryItem extends Model
{
    use BelongsToTenant;

    /** @use HasFactory<InventoryItemFactory> */
    use HasFactory;

    use HasPublicUuid;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'company_id',
        'code',
        'name',
        'category',
        'unit_of_measure',
        'description',
        'minimum_stock_level',
        'status',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'unit_of_measure' => UnitOfMeasure::class,
            'status' => InventoryItemStatus::class,
            'minimum_stock_level' => 'decimal:3',
            'quantity_on_hand' => 'decimal:3',
        ];
    }

    protected static function newFactory(): InventoryItemFactory
    {
        return InventoryItemFactory::new();
    }

    protected static function booted(): void
    {
        static::updating(static function (self $item): void {
            if ($item->isDirty('quantity_on_hand')) {
                throw new LogicException('Quantity on hand is maintained only through the stock movement ledger.');
            }
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

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** @return BelongsTo<User, $this> */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /** @return HasMany<StockMovement, $this> */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function stockStatus(): InventoryStockStatus
    {
        $quantity = (float) $this->quantity_on_hand;
        $minimum = (float) $this->minimum_stock_level;

        if ($quantity <= 0.0) {
            return InventoryStockStatus::OutOfStock;
        }

        return $quantity <= $minimum
            ? InventoryStockStatus::LowStock
            : InventoryStockStatus::InStock;
    }

    /**
     * @param  Builder<InventoryItem>  $query
     * @return Builder<InventoryItem>
     */
    public function scopeOutOfStock(Builder $query): Builder
    {
        return self::applyStockStatus($query, InventoryStockStatus::OutOfStock);
    }

    /**
     * @param  Builder<InventoryItem>  $query
     * @return Builder<InventoryItem>
     */
    public function scopeLowStock(Builder $query): Builder
    {
        return self::applyStockStatus($query, InventoryStockStatus::LowStock);
    }

    /**
     * @param  Builder<InventoryItem>  $query
     * @return Builder<InventoryItem>
     */
    public function scopeInStock(Builder $query): Builder
    {
        return self::applyStockStatus($query, InventoryStockStatus::InStock);
    }

    /**
     * @param  Builder<InventoryItem>  $query
     * @return Builder<InventoryItem>
     */
    public function scopeRequiringStockAttention(Builder $query): Builder
    {
        return $query->where(static function (Builder $query): void {
            $query
                ->where('quantity_on_hand', '<=', 0)
                ->orWhere(static function (Builder $query): void {
                    $query
                        ->where('quantity_on_hand', '>', 0)
                        ->whereColumn('quantity_on_hand', '<=', 'minimum_stock_level');
                });
        });
    }

    /**
     * @param  Builder<InventoryItem>  $query
     * @return Builder<InventoryItem>
     */
    public static function applyStockStatus(Builder $query, InventoryStockStatus $status): Builder
    {
        return match ($status) {
            InventoryStockStatus::OutOfStock => $query->where('quantity_on_hand', '<=', 0),
            InventoryStockStatus::LowStock => $query
                ->where('quantity_on_hand', '>', 0)
                ->whereColumn('quantity_on_hand', '<=', 'minimum_stock_level'),
            InventoryStockStatus::InStock => $query->whereColumn('quantity_on_hand', '>', 'minimum_stock_level'),
        };
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('inventory_items')
            ->logOnly([
                'tenant_id', 'company_id', 'code', 'name', 'category', 'unit_of_measure',
                'description', 'minimum_stock_level', 'quantity_on_hand', 'status',
            ])
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->properties = collect($activity->properties?->toArray())->merge([
            'tenant_id' => $this->tenant_id,
            'company_id' => $this->company_id,
            'inventory_item_id' => $this->getKey(),
            'item_code' => $this->code,
        ]);
    }
}
