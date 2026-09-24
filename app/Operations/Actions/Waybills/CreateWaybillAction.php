<?php

declare(strict_types=1);

namespace App\Operations\Actions\Waybills;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Shared\Services\TenantSequenceService;
use App\Models\User;
use App\Operations\Enums\JobStatus;
use App\Operations\Enums\WaybillStatus;
use App\Operations\Models\Job;
use App\Operations\Models\Waybill;
use App\Operations\Support\OperatorAssignmentService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateWaybillAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly TenantSequenceService $sequences,
        private readonly OperatorAssignmentService $personnel,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     * @param  list<string>  $attachmentPaths
     */
    public function execute(Job $job, array $data, User $actor, array $attachmentPaths = []): Waybill
    {
        if (! $actor->hasPermissionTo(PermissionName::JobsCreate->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $job->company_id, $job->tenant_id)) {
            throw new BusinessException('You are not allowed to create Waybills for this job.', 403);
        }

        if (! $job->isTrucking()) {
            throw new BusinessException('Waybills can only be recorded for trucking jobs.', 422);
        }

        if ((string) $job->getRawOriginal('status') !== JobStatus::InProgress->value) {
            throw new BusinessException('Waybills can only be recorded while the job is in progress.', 422);
        }

        return DB::transaction(function () use ($job, $data, $actor, $attachmentPaths): Waybill {
            if (! is_int($job->company_id)) {
                throw new BusinessException('The active Job is missing its company context.', 422);
            }
            $driver = $this->personnel->resolveDriverAssignment($data['driver_personnel_id'] ?? null, $data['driver_name'] ?? null, $job->tenant_id, $job->company_id);
            $driverName = $driver['personnel'] !== null ? $driver['personnel']->full_name : $driver['external_name'];
            if ($driverName === null) {
                throw new BusinessException('Record a driver before continuing.', 422);
            }
            $waybill = Waybill::query()->create([
                ...Arr::except($data, ['tenant_id', 'company_id', 'job_id', 'client_id', 'created_by', 'updated_by', 'uuid', 'waybill_number', 'status', 'attachments', 'driver_personnel_id']),
                'uuid' => (string) Str::uuid(),
                'tenant_id' => $job->tenant_id,
                'company_id' => $job->company_id,
                'job_id' => $job->getKey(),
                'client_id' => $job->client_id,
                'waybill_number' => filled($data['waybill_number'] ?? null)
                    ? (string) $data['waybill_number']
                    : $this->generateWaybillNumber($job),
                'status' => WaybillStatus::Recorded->value,
                'driver_personnel_id' => $driver['personnel']?->getKey(),
                'driver_name' => $driverName,
                'created_by' => $actor->getKey(),
                'updated_by' => $actor->getKey(),
            ]);

            $this->attachDocuments($waybill, $attachmentPaths);

            $this->logger->log('waybill.created', sprintf('Waybill recorded by %s', $actor->full_name), $actor, $waybill, [
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

    private function generateWaybillNumber(Job $job): string
    {
        return sprintf('WB-%05d', $this->sequences->nextValue($job->tenant_id, 'waybill_number'));
    }
}
