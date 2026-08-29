<?php

declare(strict_types=1);

namespace App\Finance\Models;

use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Models\JobCardWorkEntry;
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
