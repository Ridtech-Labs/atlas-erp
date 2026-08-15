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

class VerifyWaybillAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(Waybill $waybill, User $actor): Waybill
    {
        if (! $actor->hasPermissionTo(PermissionName::JobsApprove->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $waybill->company_id, $waybill->tenant_id)) {
            throw new BusinessException('You are not allowed to verify this Waybill.', 403);
        }

        if ((string) $waybill->getRawOriginal('status') !== WaybillStatus::PendingVerification->value) {
            throw new BusinessException('Only Waybills pending verification can be verified.', 422);
        }

        if (blank($waybill->waybill_date)
            || blank($waybill->driver_name)
            || blank($waybill->truck_number)
            || blank($waybill->pickup_point)
            || blank($waybill->destination)
            || $waybill->number_of_trips < 1) {
            throw new BusinessException('Date, driver, truck number, client, pickup point, destination, and at least one trip are required before verification.', 422);
        }

        if (($waybill->amount_paid !== null && (float) $waybill->amount_paid < 0)
            || ($waybill->amount_paid_to_driver !== null && (float) $waybill->amount_paid_to_driver < 0)) {
            throw new BusinessException('Waybill amount fields must not be negative.', 422);
        }

        if (blank($waybill->signature_name) && $waybill->getMedia('waybill-documents')->isEmpty()) {
            throw new BusinessException('A signed Waybill name or supporting document is required before verification.', 422);
        }

        return DB::transaction(function () use ($waybill, $actor): Waybill {
            $waybill->forceFill([
                'status' => WaybillStatus::Verified->value,
                'verified_by' => $actor->getKey(),
                'verified_at' => now(),
                'updated_by' => $actor->getKey(),
            ])->save();

            $this->logger->log('waybill.verified', 'Waybill verified', $actor, $waybill, [
                'tenant_id' => $waybill->tenant_id,
                'company_id' => $waybill->company_id,
                'job_id' => $waybill->job_id,
            ]);

            return $waybill->refresh();
        });
    }
}
