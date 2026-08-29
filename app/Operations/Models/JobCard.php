<?php

declare(strict_types=1);

namespace App\Operations\Models;

use App\Core\Shared\Concerns\BelongsToTenant;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use App\Finance\Models\BillingBatchLine;
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
        'client_card_reference',
        'card_date',
        'shift',
        'equipment_reference',
        'machine_number',
        'from_time',
        'to_time',
        'operator_id',
        'operated_by',
        'supervising_officer_name',
        'header_hours',
        'total_hours',
        'is_client_issued',
        'client_endorsed',
        'client_stamped',
        'officer_remarks',
        'approval_status',
        'approved_by',
        'approved_at',
        'submitted_by',
        'submitted_at',
        'returned_by',
        'returned_at',
        'return_reason',
        'verification_notes',
        'verified_by',
        'verified_at',
        'billing_ready_at',
        'billing_ready_by',
        'rate_currency',
        'hourly_rate',
        'exchange_rate',
        'converted_hourly_rate',
        'billable_amount',
        'rate_notes',
        'legacy_generated',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'card_date' => 'date',
            'header_hours' => 'decimal:2',
            'total_hours' => 'decimal:2',
            'is_client_issued' => 'boolean',
            'client_endorsed' => 'boolean',
            'client_stamped' => 'boolean',
            'approval_status' => JobCardApprovalStatus::class,
            'approved_at' => 'datetime',
            'submitted_at' => 'datetime',
            'returned_at' => 'datetime',
            'verified_at' => 'datetime',
            'billing_ready_at' => 'datetime',
            'hourly_rate' => 'decimal:2',
            'exchange_rate' => 'decimal:4',
            'converted_hourly_rate' => 'decimal:2',
            'billable_amount' => 'decimal:2',
            'legacy_generated' => 'boolean',
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
        if ($this->relationLoaded('operators') ? $this->operators->isNotEmpty() : $this->operators()->exists()) {
            return $this->operators
                ->map(fn (JobCardOperator $operator): ?string => $operator->displayName())
                ->filter()
                ->join(', ');
        }

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
    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
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

    /**
     * @return HasMany<JobCardOperator, $this>
     */
    public function operators(): HasMany
    {
        return $this->hasMany(JobCardOperator::class)->orderBy('sort_order');
    }

    /**
     * @return HasMany<BillingBatchLine, $this>
     */
    public function billingBatchLines(): HasMany
    {
        return $this->hasMany(BillingBatchLine::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function billingReadyBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'billing_ready_by');
    }

    public function usesVerificationWorkflow(): bool
    {
        return $this->job?->isHeavyMachinery() ?? true;
    }

    public function wasSubmittedBy(User $user): bool
    {
        return $this->submitted_by !== null && $this->submitted_by === $user->getKey();
    }

    public function evidenceIsLockedForEditing(): bool
    {
        return in_array((string) $this->getRawOriginal('approval_status'), [
            JobCardApprovalStatus::PendingVerification->value,
            JobCardApprovalStatus::Submitted->value,
            JobCardApprovalStatus::Verified->value,
            JobCardApprovalStatus::BillingReady->value,
            JobCardApprovalStatus::Approved->value,
        ], true);
    }

    public function workEntriesAreLocked(): bool
    {
        return $this->evidenceIsLockedForEditing();
    }

    public function hasAuthoritativeWorkEntries(): bool
    {
        return $this->relationLoaded('workEntries')
            ? $this->workEntries->isNotEmpty()
            : $this->workEntries()->exists();
    }

    public function displayTotalHours(): ?float
    {
        if ($this->hasAuthoritativeWorkEntries()) {
            $entries = $this->relationLoaded('workEntries')
                ? $this->workEntries
                : $this->workEntries()->get();

            return round((float) $entries->sum('total_hours'), 2);
        }

        return $this->total_hours === null ? null : round((float) $this->total_hours, 2);
    }

    public function displayStartTime(): ?string
    {
        if ($this->hasAuthoritativeWorkEntries()) {
            $entries = $this->relationLoaded('workEntries')
                ? $this->workEntries
                : $this->workEntries()->get();

            return $entries->sortBy('from_time')->first()?->from_time;
        }

        return $this->from_time;
    }

    public function displayEndTime(): ?string
    {
        if ($this->hasAuthoritativeWorkEntries()) {
            $entries = $this->relationLoaded('workEntries')
                ? $this->workEntries
                : $this->workEntries()->get();

            return $entries->sortBy('to_time')->last()?->to_time;
        }

        return $this->to_time;
    }

    public function calculateBillableAmount(): ?float
    {
        if ($this->total_hours === null || $this->hourly_rate === null) {
            return null;
        }

        $rate = $this->resolvedBillingRate();

        if ($rate === null) {
            return null;
        }

        $hours = number_format((float) $this->total_hours, 2, '.', '');
        $resolvedRate = number_format($rate, 2, '.', '');

        if (function_exists('bcmul')) {
            return (float) bcadd(bcmul($hours, $resolvedRate, 4), '0', 2);
        }

        return (float) number_format((float) $this->total_hours * $rate, 2, '.', '');
    }

    public function resolvedBillingRate(): ?float
    {
        if ($this->converted_hourly_rate !== null) {
            return (float) number_format((float) $this->converted_hourly_rate, 2, '.', '');
        }

        if ($this->hourly_rate === null) {
            return null;
        }

        if ($this->exchange_rate === null) {
            return (float) number_format((float) $this->hourly_rate, 2, '.', '');
        }

        $hourlyRate = number_format((float) $this->hourly_rate, 2, '.', '');
        $exchangeRate = number_format((float) $this->exchange_rate, 4, '.', '');

        if (function_exists('bcmul')) {
            return (float) bcadd(bcmul($hourlyRate, $exchangeRate, 6), '0', 2);
        }

        return (float) number_format((float) $this->hourly_rate * (float) $this->exchange_rate, 2, '.', '');
    }

    public function syncBillingFigures(): void
    {
        $resolvedRate = $this->resolvedBillingRate();

        if ($resolvedRate !== null && $this->converted_hourly_rate === null && $this->exchange_rate !== null) {
            $this->converted_hourly_rate = $resolvedRate;
        }

        if ($this->total_hours !== null && $resolvedRate !== null) {
            $this->billable_amount = $this->calculateBillableAmount();
        }
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
                'client_card_reference',
                'client_id',
                'client_site_id',
                'card_date',
                'shift',
                'equipment_reference',
                'machine_number',
                'operator_id',
                'operated_by',
                'total_hours',
                'client_endorsed',
                'client_stamped',
                'approval_status',
                'approved_at',
                'returned_at',
                'return_reason',
                'verified_at',
                'billing_ready_at',
                'hourly_rate',
                'rate_currency',
                'exchange_rate',
                'converted_hourly_rate',
                'billable_amount',
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
