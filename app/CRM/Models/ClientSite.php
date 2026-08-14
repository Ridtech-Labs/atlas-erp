<?php

declare(strict_types=1);

namespace App\CRM\Models;

use App\Core\Shared\Concerns\BelongsToTenant;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\CRM\Enums\ClientSiteStatus;
use App\Operations\Models\Job;
use Database\Factories\ClientSiteFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientSite extends Model
{
    use BelongsToTenant;

    /** @use HasFactory<ClientSiteFactory> */
    use HasFactory;

    use HasPublicUuid;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'company_id',
        'client_id',
        'site_code',
        'name',
        'address_line_1',
        'address_line_2',
        'city',
        'region',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'contact_name',
        'contact_phone',
        'directions',
        'notes',
        'is_primary',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'is_primary' => 'boolean',
            'status' => ClientSiteStatus::class,
        ];
    }

    protected static function newFactory(): ClientSiteFactory
    {
        return ClientSiteFactory::new();
    }

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * @return HasMany<Job, $this>
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'client_site_id');
    }

    /**
     * @param  Builder<ClientSite>  $query
     * @return Builder<ClientSite>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', ClientSiteStatus::Active->value);
    }

    public function isActive(): bool
    {
        return $this->getRawOriginal('status') === ClientSiteStatus::Active->value;
    }
}
