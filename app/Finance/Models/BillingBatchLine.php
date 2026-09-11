<?php

declare(strict_types=1);

namespace App\Finance\Models;

use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Models\JobCardWorkEntry;
use App\Operations\Models\Waybill;
use Database\Factories\BillingBatchLineFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingBatchLine extends Model
{
    /** @use HasFactory<BillingBatchLineFactory> */
    use HasFactory;

    protected $fillable = [
        'billing_batch_id',
        'source_type',
        'source_id',
        'source_reference',
        'work_entry_id',
        'job_card_id',
        'job_id',
        'job_reference',
        'activity_date',
        'vessel',
        'work_area',
        'from_time',
        'to_time',
        'equipment_reference',
        'machine_number',
        'hours',
        'quantity',
        'billing_unit',
        'pickup_point',
        'destination',
        'truck_number',
        'driver_name',
        'client_reference',
        'resolved_rate',
        'currency',
        'line_amount',
        'rate_agreement_id',
        'rate_agreement_line_id',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'from_time' => 'datetime:H:i:s',
            'to_time' => 'datetime:H:i:s',
            'hours' => 'decimal:2',
            'quantity' => 'decimal:2',
            'resolved_rate' => 'decimal:2',
            'line_amount' => 'decimal:2',
        ];
    }

    protected static function newFactory(): BillingBatchLineFactory
    {
        return BillingBatchLineFactory::new();
    }

    /**
     * @return BelongsTo<BillingBatch, $this>
     */
    public function billingBatch(): BelongsTo
    {
        return $this->belongsTo(BillingBatch::class);
    }

    /**
     * @return BelongsTo<JobCardWorkEntry, $this>
     */
    public function workEntry(): BelongsTo
    {
        return $this->belongsTo(JobCardWorkEntry::class, 'work_entry_id');
    }

    /** @return BelongsTo<Waybill, $this> */
    public function waybill(): BelongsTo
    {
        return $this->belongsTo(Waybill::class, 'source_id')->where('source_type', 'trucking_waybill');
    }

    /**
     * @return BelongsTo<JobCard, $this>
     */
    public function jobCard(): BelongsTo
    {
        return $this->belongsTo(JobCard::class);
    }

    /**
     * @return BelongsTo<Job, $this>
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * @return BelongsTo<RateAgreement, $this>
     */
    public function rateAgreement(): BelongsTo
    {
        return $this->belongsTo(RateAgreement::class);
    }

    /**
     * @return BelongsTo<RateAgreementLine, $this>
     */
    public function rateAgreementLine(): BelongsTo
    {
        return $this->belongsTo(RateAgreementLine::class);
    }
}
