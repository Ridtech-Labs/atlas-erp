<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Models;

use App\Core\Shared\Concerns\HasPublicUuid;
use App\CRM\Models\Client;
use App\Finance\Models\BillingBatch;
use App\Finance\Models\RateAgreement;
use App\Models\User;
use App\Operations\Models\Job;
use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory;

    use HasPublicUuid;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'name',
        'legal_name',
        'email',
        'phone',
        'logo_path',
        'address',
        'city',
        'timezone',
        'code',
        'status',
        'currency',
        'country',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    protected static function newFactory(): CompanyFactory
    {
        return CompanyFactory::new();
    }

    /**
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    /**
     * @return HasMany<Client, $this>
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
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
}
