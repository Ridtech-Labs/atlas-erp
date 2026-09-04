<?php

declare(strict_types=1);

namespace App\Fleet\Models;

use App\Core\Shared\Concerns\BelongsToTenant;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Fleet\Enums\FleetAssetOperationalStatus;
use Database\Factories\FleetAssetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class FleetAsset extends Model
{
    use BelongsToTenant;

    /** @use HasFactory<FleetAssetFactory> */
    use HasFactory;

    use HasPublicUuid;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'company_id',
        'fleet_asset_type_id',
        'asset_number',
        'registration_number',
        'make',
        'model',
        'serial_number',
        'operational_status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'operational_status' => FleetAssetOperationalStatus::class,
        ];
    }

    protected static function newFactory(): FleetAssetFactory
    {
        return FleetAssetFactory::new();
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

    /** @return BelongsTo<FleetAssetType, $this> */
    public function type(): BelongsTo
    {
        return $this->belongsTo(FleetAssetType::class, 'fleet_asset_type_id');
    }

    /** @return HasMany<JobAssetAssignment, $this> */
    public function jobAssignments(): HasMany
    {
        return $this->hasMany(JobAssetAssignment::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('fleet_assets')
            ->logOnly([
                'tenant_id', 'company_id', 'fleet_asset_type_id', 'asset_number',
                'registration_number', 'make', 'model', 'serial_number', 'operational_status', 'notes',
            ])
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->properties = collect($activity->properties?->toArray() ?? [])->merge([
            'tenant_id' => $this->tenant_id,
            'company_id' => $this->company_id,
            'asset_number' => $this->asset_number,
        ]);
    }
}
