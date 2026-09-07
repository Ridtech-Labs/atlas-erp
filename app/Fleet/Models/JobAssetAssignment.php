<?php

declare(strict_types=1);

namespace App\Fleet\Models;

use App\Core\Shared\Concerns\BelongsToTenant;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Fleet\Enums\JobAssetAssignmentStatus;
use App\Models\User;
use App\Operations\Models\Job;
use Database\Factories\JobAssetAssignmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class JobAssetAssignment extends Model
{
    use BelongsToTenant;

    /** @use HasFactory<JobAssetAssignmentFactory> */
    use HasFactory;

    use HasPublicUuid;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'tenant_id', 'company_id', 'job_id', 'fleet_asset_id', 'operator_user_id', 'operator_name',
        'planned_start_at', 'planned_end_at', 'assigned_at', 'assigned_by', 'status', 'released_at',
        'released_by', 'release_reason', 'cancelled_at', 'cancelled_by', 'cancellation_reason', 'notes',
        'dispatched_at', 'dispatched_by', 'dispatch_notes', 'returned_at', 'returned_by', 'return_notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => JobAssetAssignmentStatus::class,
            'planned_start_at' => 'datetime',
            'planned_end_at' => 'datetime',
            'assigned_at' => 'datetime',
            'released_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'dispatched_at' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    protected static function newFactory(): JobAssetAssignmentFactory
    {
        return JobAssetAssignmentFactory::new();
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

    /** @return BelongsTo<Job, $this> */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /** @return BelongsTo<FleetAsset, $this> */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(FleetAsset::class, 'fleet_asset_id');
    }

    /** @return BelongsTo<User, $this> */
    public function operatorUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_user_id');
    }

    /** @return BelongsTo<User, $this> */
    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function operatorDisplayName(): ?string
    {
        $operator = $this->operatorUser;

        return $operator instanceof User ? $operator->full_name : $this->operator_name;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('job_asset_assignments')
            ->logOnly(['tenant_id', 'company_id', 'job_id', 'fleet_asset_id', 'operator_user_id', 'operator_name', 'planned_start_at', 'planned_end_at', 'status', 'notes'])
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->properties = collect($activity->properties?->toArray() ?? [])->merge([
            'tenant_id' => $this->tenant_id, 'company_id' => $this->company_id, 'job_id' => $this->job_id, 'fleet_asset_id' => $this->fleet_asset_id,
        ]);
    }
}
