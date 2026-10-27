<?php

declare(strict_types=1);

namespace App\Operations\Actions\JobCards;

use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use App\Operations\Models\JobCard;

class MarkJobCardBillingReadyAction
{
    public function execute(JobCard $jobCard, User $actor): JobCard
    {
        throw new BusinessException(
            'Client Job Cards become billing ready only when Finance prepares their Billing Batch.',
            422,
        );
    }
}
