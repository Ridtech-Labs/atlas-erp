<?php

declare(strict_types=1);

namespace App\Operations\Traits;

use App\Models\User;
use App\Operations\Models\Job;

trait AppliesJobAudit
{
    protected function stampCreationAudit(Job $job, User $actor): void
    {
        $job->created_by = $actor->getKey();
        $job->updated_by = $actor->getKey();
    }

    protected function stampUpdateAudit(Job $job, User $actor): void
    {
        $job->updated_by = $actor->getKey();
    }
}
