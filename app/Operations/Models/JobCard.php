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
use App\Operations\Enums\JobCardApprovalStatus;
use Database\Factories\JobCardFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class JobCard extends Model implements HasMedia
{
    use BelongsToTenant;

    /** @use HasFactory<JobCardFactory> */
    use HasFactory;

    use HasPublicUuid;
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'card_number',
        'tenant_id',
        'company_id',
        'job_id',
        'client_id',
        'client_site_id',
        'card_date',
        'shift',
        'equipment_reference',
        'operator_id',
        'operated_by',
        'supervising_officer_name',
        'header_hours',
        'officer_remarks',
        'approval_status',
        'approved_by',
        'approved_at',
        'returned_by',
        'returned_at',
        'return_reason',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'card_date' => 'date',
            'header_hours' => 'decimal:2',
            'approval_status' => JobCardApprovalStatus::class,
            'approved_at' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    protected static function newFactory(): JobCardFactory
    {
        return JobCardFactory::new();
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
     * @return BelongsTo<Job, $this>
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
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
    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function operatorDisplayName(): ?string
    {
        return $this->operator->full_name ?? $this->operated_by;
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
    public function returnedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    /**
     * @return HasMany<JobCardWorkEntry, $this>
     */
    public function workEntries(): HasMany
    {
        return $this->hasMany(JobCardWorkEntry::class)->orderBy('from_time');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('job_cards')
            ->logOnly([
                'tenant_id',
                'company_id',
                'job_id',
                'card_number',
                'client_id',
                'client_site_id',
                'card_date',
                'shift',
                'equipment_reference',
                'operator_id',
                'approval_status',
                'approved_at',
                'returned_at',
                'return_reason',
            ])
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->properties = collect($activity->properties?->toArray() ?? [])->merge([
            'tenant_id' => $this->tenant_id,
            'company_id' => $this->company_id,
            'job_id' => $this->job_id,
        ]);
    }
}
