<?php

declare(strict_types=1);

namespace App\Operations\Models;

use App\Core\Shared\Concerns\BelongsToTenant;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\CRM\Models\Client;
use App\Finance\Models\BillingBatchLine;
use App\Models\User;
use App\Operations\Enums\WaybillStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Waybill extends Model implements HasMedia
{
    use BelongsToTenant;
    use HasPublicUuid;
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'company_id',
        'job_id',
        'client_id',
        'waybill_number',
        'client_reference',
        'waybill_date',
        'driver_name',
        'truck_number',
        'number_of_trips',
        'amount_paid',
        'pickup_point',
        'destination',
        'amount_paid_to_driver',
        'signature_name',
        'status',
        'verified_by',
        'verified_at',
        'return_reason',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'waybill_date' => 'date',
            'number_of_trips' => 'integer',
            'amount_paid' => 'decimal:2',
            'amount_paid_to_driver' => 'decimal:2',
            'verified_at' => 'datetime',
            'status' => WaybillStatus::class,
        ];
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
     * @return BelongsTo<User, $this>
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /** @return HasOne<BillingBatchLine, $this> */
    public function billingBatchLine(): HasOne
    {
        return $this->hasOne(BillingBatchLine::class, 'source_id')->where('source_type', 'trucking_waybill');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('waybills')
            ->logOnly([
                'company_id',
                'job_id',
                'client_id',
                'waybill_number',
                'client_reference',
                'waybill_date',
                'driver_name',
                'truck_number',
                'number_of_trips',
                'amount_paid',
                'pickup_point',
                'destination',
                'amount_paid_to_driver',
                'status',
                'verified_at',
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
