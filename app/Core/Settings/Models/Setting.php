<?php

declare(strict_types=1);

namespace App\Core\Settings\Models;

use App\Core\Shared\Concerns\BelongsToTenant;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    use BelongsToTenant;
    use HasPublicUuid;

    protected $fillable = [
        'tenant_id',
        'uuid',
        'group',
        'key',
        'value',
        'is_encrypted',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'array',
            'is_encrypted' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
