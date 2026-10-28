<?php

declare(strict_types=1);

namespace App\Inventory\Models;

use App\Core\Shared\Concerns\BelongsToTenant;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Warehouse extends Model
{
    use BelongsToTenant;
    use HasPublicUuid;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = ['uuid', 'tenant_id', 'company_id', 'code', 'name', 'notes', 'is_active', 'created_by', 'updated_by'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
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

    /** @return HasMany<StorageLocation, $this> */
    public function storageLocations(): HasMany
    {
        return $this->hasMany(StorageLocation::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('warehouses')->logOnly(['tenant_id', 'company_id', 'code', 'name', 'notes', 'is_active'])->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->properties = collect($activity->properties?->toArray() ?? [])->merge(['tenant_id' => $this->tenant_id, 'company_id' => $this->company_id, 'warehouse_id' => $this->getKey()]);
    }
}
