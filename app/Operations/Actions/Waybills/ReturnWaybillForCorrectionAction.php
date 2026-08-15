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

class ReturnWaybillForCorrectionAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(Waybill $waybill, User $actor, string $reason): Waybill
    {
        if (! $actor->hasPermissionTo(PermissionName::JobsApprove->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $waybill->company_id, $waybill->tenant_id)) {
            throw new BusinessException('You are not allowed to return this Waybill for correction.', 403);
        }

        if ((string) $waybill->getRawOriginal('status') !== WaybillStatus::PendingVerification->value) {
            throw new BusinessException('Only Waybills pending verification can be returned for correction.', 422);
        }

        if (trim($reason) === '') {
            throw new BusinessException('A correction reason is required when returning a Waybill.', 422);
        }

        return DB::transaction(function () use ($waybill, $actor, $reason): Waybill {
            $waybill->forceFill([
                'status' => WaybillStatus::Returned->value,
                'return_reason' => $reason,
                'verified_by' => null,
                'verified_at' => null,
                'updated_by' => $actor->getKey(),
            ])->save();

            $this->logger->log('waybill.returned', 'Waybill returned for correction', $actor, $waybill, [
                'tenant_id' => $waybill->tenant_id,
                'company_id' => $waybill->company_id,
                'job_id' => $waybill->job_id,
                'return_reason' => $reason,
            ]);

            return $waybill->refresh();
        });
    }
}
