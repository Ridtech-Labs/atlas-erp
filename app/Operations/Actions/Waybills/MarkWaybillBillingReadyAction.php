<?php

declare(strict_types=1);

namespace App\Operations\Actions\Waybills;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use App\Operations\Enums\WaybillStatus;
use App\Operations\Models\Waybill;
use Illuminate\Support\Facades\DB;

class MarkWaybillBillingReadyAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(Waybill $waybill, User $actor): Waybill
    {
        if (! $actor->hasPermissionTo(PermissionName::JobsApprove->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $waybill->company_id, $waybill->tenant_id)) {
            throw new BusinessException('You are not allowed to mark this Waybill as billing ready.', 403);
        }

        if ((string) $waybill->getRawOriginal('status') !== WaybillStatus::Verified->value) {
            throw new BusinessException('Only verified Waybills can move to billing ready.', 422);
        }

        return DB::transaction(function () use ($waybill, $actor): Waybill {
            $waybill->forceFill([
                'status' => WaybillStatus::BillingReady->value,
                'updated_by' => $actor->getKey(),
            ])->save();

            $this->logger->log('waybill.billing_ready', 'Waybill marked billing ready', $actor, $waybill, [
                'tenant_id' => $waybill->tenant_id,
                'company_id' => $waybill->company_id,
                'job_id' => $waybill->job_id,
            ]);

            return $waybill->refresh();
        });
    }
}
