<?php

declare(strict_types=1);

namespace App\CRM\Models;

use App\Core\Shared\Concerns\BelongsToTenant;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\CRM\Enums\ClientStatus;
use App\CRM\Enums\ClientType;
use App\Finance\Models\BillingBatch;
use App\Finance\Models\BillingRecord;
use App\Finance\Models\RateAgreement;
use App\Models\User;
use App\Operations\Models\Job;
use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Client extends Model
{
    use BelongsToTenant;

    /** @use HasFactory<ClientFactory> */
    use HasFactory;

    use HasPublicUuid;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'company_id',
        'client_code',
        'legal_name',
        'trading_name',
        'client_type',
        'status',
        'tax_identification_number',
        'registration_number',
        'email',
        'phone',
        'alternate_phone',
        'website',
        'billing_address',
        'physical_address',
        'city',
        'region',
        'country',
        'credit_limit',
        'payment_terms_days',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'client_type' => ClientType::class,
            'status' => ClientStatus::class,
            'credit_limit' => 'decimal:2',
            'payment_terms_days' => 'integer',
        ];
    }

    public function getAlternatePhoneAttribute(?string $value): ?string
    {
        return $value ?? $this->attributes['alternative_phone'] ?? null;
    }

    protected static function newFactory(): ClientFactory
    {
        return ClientFactory::new();
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
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * @return HasMany<ClientContact, $this>
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(ClientContact::class);
    }

    /**
     * @return HasMany<ClientSite, $this>
     */
    public function sites(): HasMany
    {
        return $this->hasMany(ClientSite::class);
    }

    /**
     * @return HasMany<Job, $this>
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    /**
     * @return HasMany<RateAgreement, $this>
     */
    public function rateAgreements(): HasMany
    {
        return $this->hasMany(RateAgreement::class);
    }

    /**
     * @return HasMany<BillingBatch, $this>
     */
    public function billingBatches(): HasMany
    {
        return $this->hasMany(BillingBatch::class);
    }

    /**
     * @return HasMany<BillingRecord, $this>
     */
    public function billingRecords(): HasMany
    {
        return $this->hasMany(BillingRecord::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->trading_name ?: $this->legal_name;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('clients')
            ->logOnly([
                'tenant_id',
                'company_id',
                'client_code',
                'legal_name',
                'trading_name',
                'client_type',
                'status',
                'email',
                'phone',
                'country',
                'credit_limit',
                'payment_terms_days',
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
