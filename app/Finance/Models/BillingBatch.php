<?php

declare(strict_types=1);

namespace App\Finance\Models;

use App\Core\Shared\Concerns\BelongsToTenant;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Tenancy\Models\Company;
use App\CRM\Models\Client;
use App\Finance\Enums\BillingBatchStatus;
use App\Models\User;
use Database\Factories\BillingBatchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingBatch extends Model
{
    use BelongsToTenant;

    /** @use HasFactory<BillingBatchFactory> */
    use HasFactory;

    use HasPublicUuid;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'company_id',
        'client_id',
        'batch_number',
        'status',
        'period_start',
        'period_end',
        'notes',
        'prepared_at',
        'prepared_by',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => BillingBatchStatus::class,
            'period_start' => 'date',
            'period_end' => 'date',
            'prepared_at' => 'datetime',
        ];
    }

    protected static function newFactory(): BillingBatchFactory
    {
        return BillingBatchFactory::new();
    }

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return HasMany<BillingBatchLine, $this>
     */
    public function lines(): HasMany
    {
        return $this->hasMany(BillingBatchLine::class);
    }

    /** @return HasOne<BillingRecord, $this> */
    public function billingRecord(): HasOne
    {
        return $this->hasOne(BillingRecord::class);
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
     * @return BelongsTo<User, $this>
     */
    public function preparer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function subtotalHours(): float
    {
        return round((float) $this->lines()->sum('hours'), 2);
    }

    public function totalWorkEntries(): int
    {
        return (int) $this->lines()->count();
    }

    public function subtotalAmount(): float
    {
        return round((float) $this->lines()->sum('line_amount'), 2);
    }

    /**
     * @return array<string, float>
     */
    public function subtotalAmountsByCurrency(): array
    {
        return $this->lines()
            ->selectRaw('currency, SUM(line_amount) as subtotal')
            ->groupBy('currency')
            ->pluck('subtotal', 'currency')
            ->map(fn (mixed $subtotal): float => round((float) $subtotal, 2))
            ->all();
    }

    public function hasMixedCurrencies(): bool
    {
        return count($this->subtotalAmountsByCurrency()) > 1;
    }
}
