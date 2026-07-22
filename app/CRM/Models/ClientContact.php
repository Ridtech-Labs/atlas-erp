<?php

declare(strict_types=1);

namespace App\CRM\Models;

use App\Core\Shared\Concerns\BelongsToTenant;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Tenancy\Models\Tenant;
use Database\Factories\ClientContactFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientContact extends Model
{
    use BelongsToTenant;

    /** @use HasFactory<ClientContactFactory> */
    use HasFactory;

    use HasPublicUuid;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'client_id',
        'first_name',
        'last_name',
        'job_title',
        'department',
        'email',
        'phone',
        'alternate_phone',
        'is_primary',
        'receives_invoices',
        'receives_operational_updates',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'receives_invoices' => 'boolean',
            'receives_operational_updates' => 'boolean',
        ];
    }

    protected static function newFactory(): ClientContactFactory
    {
        return ClientContactFactory::new();
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

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
