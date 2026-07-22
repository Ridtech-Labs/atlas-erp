<?php

declare(strict_types=1);

namespace App\Operations\Models;

use App\Core\Shared\Concerns\BelongsToTenant;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Tenancy\Models\Tenant;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use App\Models\User;
use App\Operations\Enums\JobPriority;
use App\Operations\Enums\JobStatus;
use Database\Factories\JobFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Job extends Model
{
    use BelongsToTenant;

    /** @use HasFactory<JobFactory> */
    use HasFactory;

    use HasPublicUuid;
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'client_jobs';

    protected $fillable = [
        'uuid',
        'tenant_id',
        'job_number',
        'client_id',
        'client_site_id',
        'title',
        'description',
        'status',
        'priority',
        'requested_start_date',
        'planned_start_date',
        'planned_end_date',
        'actual_start_date',
        'actual_end_date',
        'client_reference',
        'internal_reference',
        'estimated_value',
        'approved_at',
        'approved_by',
        'cancelled_at',
        'cancelled_by',
        'cancellation_reason',
        'completed_at',
        'completed_by',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => JobStatus::class,
            'priority' => JobPriority::class,
            'requested_start_date' => 'date',
            'planned_start_date' => 'date',
            'planned_end_date' => 'date',
            'actual_start_date' => 'datetime',
            'actual_end_date' => 'datetime',
            'approved_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'completed_at' => 'datetime',
            'estimated_value' => 'decimal:2',
        ];
    }

    protected static function newFactory(): JobFactory
    {
        return JobFactory::new();
    }

    /**
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return BelongsTo<ClientSite, $this>
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(ClientSite::class, 'client_site_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function canceller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function completer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('jobs')
            ->logOnly([
                'tenant_id',
                'job_number',
                'client_id',
                'client_site_id',
                'title',
                'status',
                'priority',
                'planned_start_date',
                'planned_end_date',
                'actual_start_date',
                'actual_end_date',
                'estimated_value',
                'approved_at',
                'cancelled_at',
                'completed_at',
            ])
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->properties = collect($activity->properties?->toArray() ?? [])->merge([
            'tenant_id' => $this->tenant_id,
        ]);
    }
}
