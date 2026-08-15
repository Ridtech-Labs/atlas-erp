<?php

declare(strict_types=1);

namespace App\Operations\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobCardOperator extends Model
{
    protected $fillable = [
        'job_card_id',
        'user_id',
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

    public function displayName(): ?string
    {
        return $this->user->full_name ?? $this->operator_name;
    }
}
