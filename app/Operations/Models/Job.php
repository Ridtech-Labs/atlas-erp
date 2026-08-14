<?php

declare(strict_types=1);

namespace App\Operations\Models;

use App\Core\Shared\Concerns\BelongsToTenant;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use App\Models\User;
use App\Operations\Enums\JobPriority;
use App\Operations\Enums\JobShift;
use App\Operations\Enums\JobStatus;
use Database\Factories\JobFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'company_id',
        'job_number',
        'job_reference',
        'client_id',
        'client_site_id',
        'title',
        'description',
        'job_type',
        'status',
        'priority',
        'currency',
        'customer_reference',
        'purchase_order_number',
        'requested_start_date',
        'scheduled_start_date',
        'planned_start_date',
        'planned_start_time',
        'scheduled_end_date',
        'planned_end_date',
        'planned_end_time',
        'vessel',
        'work_area',
        'equipment_requirement',
        'assigned_to',
        'assigned_operator_id',
        'assigned_operator_name',
        'shift',
        'actual_start_date',
        'actual_end_date',
        'client_reference',
        'internal_reference',
        'estimated_amount',
        'estimated_value',
        'notes',
        'approved_at',
        'approved_by',
        'cancelled_at',
        'cancelled_by',
        'cancellation_reason',
        'completed_at',
        'completed_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => JobStatus::class,
            'priority' => JobPriority::class,
            'shift' => JobShift::class,
            'requested_start_date' => 'date',
            'scheduled_start_date' => 'date',
            'planned_start_date' => 'date',
            'scheduled_end_date' => 'date',
            'planned_end_date' => 'date',
            'actual_start_date' => 'date',
            'actual_end_date' => 'date',
            'approved_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'completed_at' => 'datetime',
            'estimated_amount' => 'decimal:2',
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
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
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
    public function assignedOperator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_operator_id');
    }

    public function plannedOperatorName(): ?string
    {
        return $this->assignedOperator->full_name ?? $this->assigned_operator_name;
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

    /**
     * @return HasMany<JobCard, $this>
     */
    public function jobCards(): HasMany
    {
        return $this->hasMany(JobCard::class)->latest('card_date');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('jobs')
            ->logOnly([
                'tenant_id',
                'company_id',
                'job_number',
                'job_reference',
                'client_id',
                'client_site_id',
                'title',
                'status',
                'priority',
                'currency',
                'customer_reference',
                'purchase_order_number',
                'scheduled_start_date',
                'planned_start_date',
                'planned_start_time',
                'scheduled_end_date',
                'planned_end_date',
                'planned_end_time',
                'vessel',
                'work_area',
                'equipment_requirement',
                'assigned_to',
                'assigned_operator_id',
                'assigned_operator_name',
                'shift',
                'actual_start_date',
                'actual_end_date',
                'notes',
                'estimated_amount',
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
            'company_id' => $this->company_id,
        ]);
    }
}
