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

class SubmitWaybillForVerificationAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(Waybill $waybill, User $actor): Waybill
    {
        if (! $actor->hasPermissionTo(PermissionName::JobsUpdate->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $waybill->company_id, $waybill->tenant_id)) {
            throw new BusinessException('You are not allowed to submit this Waybill for verification.', 403);
        }

        if (! in_array((string) $waybill->getRawOriginal('status'), [WaybillStatus::Recorded->value, WaybillStatus::Returned->value], true)) {
            throw new BusinessException('Only recorded or returned Waybills can be sent for verification.', 422);
        }

        return DB::transaction(function () use ($waybill, $actor): Waybill {
            $waybill->forceFill([
                'status' => WaybillStatus::PendingVerification->value,
                'return_reason' => null,
                'updated_by' => $actor->getKey(),
            ])->save();

            $this->logger->log('waybill.pending_verification', 'Waybill moved to pending verification', $actor, $waybill, [
                'tenant_id' => $waybill->tenant_id,
                'company_id' => $waybill->company_id,
                'job_id' => $waybill->job_id,
            ]);

            return $waybill->refresh();
        });
    }
}
