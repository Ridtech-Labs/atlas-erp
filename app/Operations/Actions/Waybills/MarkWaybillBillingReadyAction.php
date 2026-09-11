<?php

declare(strict_types=1);

namespace App\Operations\Actions\Waybills;

use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use App\Operations\Models\Waybill;

class MarkWaybillBillingReadyAction
{
    public function execute(Waybill $waybill, User $actor): Waybill
    {
        throw new BusinessException('Waybills become billing ready only when Finance prepares their Billing Batch.', 422);
    }
}
