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
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageLocation extends Model
{
    use BelongsToTenant;
    use HasPublicUuid;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = ['uuid', 'tenant_id', 'company_id', 'warehouse_id', 'code', 'name', 'is_active', 'is_default', 'created_by', 'updated_by'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'is_default' => 'boolean'];
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

    /** @return BelongsTo<Warehouse, $this> */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('storage_locations')->logOnly(['tenant_id', 'company_id', 'warehouse_id', 'code', 'name', 'is_active', 'is_default'])->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->properties = collect($activity->properties?->toArray() ?? [])->merge(['tenant_id' => $this->tenant_id, 'company_id' => $this->company_id, 'warehouse_id' => $this->warehouse_id, 'storage_location_id' => $this->getKey()]);
    }
}
