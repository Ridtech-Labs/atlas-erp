<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Models;

use App\Core\Settings\Models\Setting;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Shared\Enums\TenantStatus;
use App\Models\User;
use Database\Factories\TenantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Tenant extends Model implements HasMedia
{
    /** @use HasFactory<TenantFactory> */
    use HasFactory;

    use HasPublicUuid;
    use InteractsWithMedia;
    use LogsActivity;

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'timezone',
        'currency',
        'status',
        'logo_path',
    ];

    protected function casts(): array
    {
        return [
            'status' => TenantStatus::class,
        ];
    }

    protected static function newFactory(): TenantFactory
    {
        return TenantFactory::new();
    }

    public function isActive(): bool
    {
        return $this->getRawOriginal('status') === TenantStatus::Active->value;
    }

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return HasMany<Setting, $this>
     */
    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('tenants')
            ->logOnly(['name', 'slug', 'timezone', 'currency', 'status'])
            ->logOnlyDirty();
    }
}
