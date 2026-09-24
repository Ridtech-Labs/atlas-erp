<?php

declare(strict_types=1);

namespace App\Operations\Models;

use App\Core\Shared\Concerns\BelongsToTenant;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $company_id
 * @property string $first_name
 * @property string|null $last_name
 * @property string $status
 */
class Personnel extends Model
{
    use BelongsToTenant;
    use HasPublicUuid;
    use SoftDeletes;

    protected $table = 'personnel';

    protected $fillable = ['uuid', 'tenant_id', 'company_id', 'first_name', 'last_name', 'phone', 'can_operate_equipment', 'can_drive', 'status', 'user_id', 'notes', 'created_by', 'updated_by'];

    protected function casts(): array
    {
        return ['can_operate_equipment' => 'boolean', 'can_drive' => 'boolean'];
    }

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /** @return BelongsTo<Company, $this> */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.($this->last_name ?? ''));
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
