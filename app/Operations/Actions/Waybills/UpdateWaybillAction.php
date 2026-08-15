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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateWaybillAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @param  list<string>  $attachmentPaths
     */
    public function execute(Waybill $waybill, array $data, User $actor, array $attachmentPaths = []): Waybill
    {
        if (! $actor->hasPermissionTo(PermissionName::JobsUpdate->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $waybill->company_id, $waybill->tenant_id)) {
            throw new BusinessException('You are not allowed to update this Waybill.', 403);
        }

        $status = (string) $waybill->getRawOriginal('status');

        if ($status === WaybillStatus::BillingReady->value) {
            throw new BusinessException('Billing-ready Waybills are read-only.', 422);
        }

        if (in_array($status, [WaybillStatus::PendingVerification->value, WaybillStatus::Verified->value], true)) {
            throw new BusinessException('This Waybill must be returned for correction before it can be edited.', 422);
        }

        return DB::transaction(function () use ($waybill, $data, $actor, $attachmentPaths): Waybill {
            $waybill->fill(Arr::except($data, [
                'tenant_id',
                'company_id',
                'job_id',
                'client_id',
                'created_by',
                'updated_by',
                'uuid',
                'waybill_number',
                'status',
                'verified_by',
                'verified_at',
                'attachments',
            ]));
            $waybill->updated_by = $actor->getKey();
            $waybill->save();

            $this->attachDocuments($waybill, $attachmentPaths);

            $this->logger->log('waybill.updated', sprintf('Waybill updated by %s', $actor->full_name), $actor, $waybill, [
                'tenant_id' => $waybill->tenant_id,
                'company_id' => $waybill->company_id,
                'job_id' => $waybill->job_id,
            ]);

            return $waybill->refresh();
        });
    }

    /**
     * @param  list<string>  $attachmentPaths
     */
    private function attachDocuments(Waybill $waybill, array $attachmentPaths): void
    {
        foreach ($attachmentPaths as $path) {
            if ($path === '') {
                continue;
            }

            $waybill
                ->addMedia(Storage::disk('local')->path($path))
                ->preservingOriginal()
                ->toMediaCollection('waybill-documents');
        }
    }
}
