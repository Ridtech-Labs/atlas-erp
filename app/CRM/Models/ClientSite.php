<?php

declare(strict_types=1);

namespace App\CRM\Models;

use App\Core\Shared\Concerns\BelongsToTenant;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Tenancy\Models\Tenant;
use App\Operations\Models\Job;
use Database\Factories\ClientSiteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientSite extends Model
{
    use BelongsToTenant;

    /** @use HasFactory<ClientSiteFactory> */
    use HasFactory;

    use HasPublicUuid;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'client_id',
        'site_code',
        'name',
        'address',
        'city',
        'region',
        'country',
        'latitude',
        'longitude',
        'contact_name',
        'contact_phone',
        'access_instructions',
        'operational_notes',
        'is_primary',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
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
}
