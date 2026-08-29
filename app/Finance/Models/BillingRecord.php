<?php

declare(strict_types=1);

namespace App\Finance\Models;

use App\Core\Shared\Concerns\BelongsToTenant;
use App\Core\Shared\Concerns\HasPublicUuid;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\CRM\Models\Client;
use App\Finance\Enums\BillingRecordStatus;
use App\Models\User;
use Database\Factories\BillingRecordFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BillingRecord extends Model implements HasMedia
{
    use BelongsToTenant;

    /** @use HasFactory<BillingRecordFactory> */
    use HasFactory;

    use HasPublicUuid;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'company_id',
        'client_id',
        'billing_batch_id',
        'record_number',
        'status',
        'batch_amount',
        'receipt_amount',
        'currency',
        'external_receipt_reference',
        'issued_at',
        'paid_at',
        'payment_reference',
        'notes',
        'created_by',
        'updated_by',
        'issued_by',
        'paid_by',
        'closed_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => BillingRecordStatus::class,
            'batch_amount' => 'decimal:2',
            'receipt_amount' => 'decimal:2',
            'issued_at' => 'date',
            'paid_at' => 'date',
        ];
    }

    protected static function newFactory(): BillingRecordFactory
    {
        return BillingRecordFactory::new();
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

    /** @return BelongsTo<Client, $this> */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /** @return BelongsTo<BillingBatch, $this> */
    public function billingBatch(): BelongsTo
    {
        return $this->belongsTo(BillingBatch::class);
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** @return BelongsTo<User, $this> */
    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /** @return BelongsTo<User, $this> */
    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    /** @return BelongsTo<User, $this> */
    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function receiptAmountMatchesBatch(): bool
    {
        return $this->receipt_amount !== null
            && (string) $this->receipt_amount === (string) $this->batch_amount;
    }
}
