<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Shared\Enums\UserStatus;
use App\Core\Tenancy\Models\Tenant;
use App\CRM\Models\Client;
use App\Operations\Models\Job;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasMedia, HasName, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasPublicUuid;
    use HasRoles;
    use InteractsWithMedia;
    use LogsActivity;
    use Notifiable;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'tenant_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'avatar_path',
        'status',
        'last_login_at',
        'last_login_ip',
        'email_verified_at',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
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
     * @return HasMany<Client, $this>
     */
    public function createdClients(): HasMany
    {
        return $this->hasMany(Client::class, 'created_by');
    }

    /**
     * @return HasMany<Job, $this>
     */
    public function createdJobs(): HasMany
    {
        return $this->hasMany(Job::class, 'created_by');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->tenant !== null
            && $this->tenant->isActive()
            && ! $this->isInactive()
            && ! $this->isSuspended();
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getFilamentName(): string
    {
        $name = $this->getFullNameAttribute();

        if ($name !== '') {
            return $name;
        }

        return (string) $this->email;
    }

    public function getAvatarAttribute(): ?string
    {
        return $this->avatar_path;
    }

    public function isActive(): bool
    {
        return $this->getRawOriginal('status') === UserStatus::Active->value;
    }

    public function isInactive(): bool
    {
        return $this->getRawOriginal('status') === UserStatus::Inactive->value;
    }

    public function isSuspended(): bool
    {
        return $this->getRawOriginal('status') === UserStatus::Suspended->value;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('users')
            ->logOnly(['tenant_id', 'first_name', 'last_name', 'email', 'phone', 'avatar_path', 'status', 'last_login_at', 'last_login_ip'])
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->properties = collect($activity->properties?->toArray() ?? [])->merge([
            'tenant_id' => $this->tenant_id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
