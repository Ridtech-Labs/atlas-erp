<?php

declare(strict_types=1);

namespace App\Operations\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** @property int|null $personnel_id */
class JobCardOperator extends Model
{
    protected $fillable = [
        'job_card_id',
        'user_id',
        'personnel_id',
        'operator_name',
        'sort_order',
    ];

    /**
     * @return BelongsTo<JobCard, $this>
     */
    public function jobCard(): BelongsTo
    {
        return $this->belongsTo(JobCard::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Personnel, $this> */
    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class)->withTrashed();
    }

    public function displayName(): ?string
    {
        return $this->personnel?->full_name ?? $this->user?->full_name ?? $this->operator_name;
    }
}
