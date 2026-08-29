<?php

declare(strict_types=1);

namespace App\Operations\Models;

use App\Finance\Models\BillingBatchLine;
use Database\Factories\JobCardWorkEntryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobCardWorkEntry extends Model
{
    /** @use HasFactory<JobCardWorkEntryFactory> */
    use HasFactory;

    protected $fillable = [
        'job_card_id',
        'vessel',
        'work_area',
        'from_time',
        'to_time',
        'normal_hours',
        'overtime_hours',
        'total_hours',
        'officer_name',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'normal_hours' => 'decimal:2',
            'overtime_hours' => 'decimal:2',
            'total_hours' => 'decimal:2',
        ];
    }

    protected static function newFactory(): JobCardWorkEntryFactory
    {
        return JobCardWorkEntryFactory::new();
    }

    /**
     * @return BelongsTo<JobCard, $this>
     */
    public function jobCard(): BelongsTo
    {
        return $this->belongsTo(JobCard::class);
    }

    /**
     * @return BelongsTo<BillingBatchLine, $this>
     */
    public function billingBatchLine(): BelongsTo
    {
        return $this->belongsTo(BillingBatchLine::class, 'id', 'work_entry_id');
    }
}
