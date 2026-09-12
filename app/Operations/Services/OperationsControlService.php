<?php

declare(strict_types=1);

namespace App\Operations\Services;

use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Models\BillingBatch;
use App\Fleet\Enums\FleetAssetOperationalStatus;
use App\Fleet\Enums\JobAssetAssignmentStatus;
use App\Fleet\Models\FleetAsset;
use App\Operations\Enums\JobCardApprovalStatus;
use App\Operations\Enums\JobStatus;
use App\Operations\Enums\JobType;
use App\Operations\Enums\WaybillStatus;
use App\Operations\Models\Job;
use App\Operations\Models\JobCardWorkEntry;
use App\Operations\Models\Waybill;

class OperationsControlService
{
    /** @return array{jobs: array<string, int>, types: array<string, int>, backlog: array<string, int>, fleet: array<string, int>} */
    public function forCompany(int $tenantId, int $companyId): array
    {
        $jobCounts = Job::query()->where('tenant_id', $tenantId)->where('company_id', $companyId)->selectRaw('status, COUNT(*) as aggregate')->groupBy('status')->pluck('aggregate', 'status');

        return [
            'jobs' => [
                'total' => (int) $jobCounts->sum(),
                'draft' => (int) ($jobCounts[JobStatus::Draft->value] ?? 0),
                'pending_approval' => (int) ($jobCounts[JobStatus::PendingApproval->value] ?? 0),
                'approved_ready' => (int) (($jobCounts[JobStatus::Approved->value] ?? 0) + ($jobCounts[JobStatus::Scheduled->value] ?? 0)),
                'in_progress' => (int) ($jobCounts[JobStatus::InProgress->value] ?? 0),
                'completed' => (int) ($jobCounts[JobStatus::Completed->value] ?? 0),
                'cancelled' => (int) ($jobCounts[JobStatus::Cancelled->value] ?? 0),
            ],
            'types' => [
                'heavy_machinery' => Job::query()->where('tenant_id', $tenantId)->where('company_id', $companyId)->where('job_type', JobType::HeavyMachinery->value)->count(),
                'trucking' => Job::query()->where('tenant_id', $tenantId)->where('company_id', $companyId)->where('job_type', JobType::Trucking->value)->count(),
            ],
            'backlog' => [
                'jobs_pending_approval' => (int) ($jobCounts[JobStatus::PendingApproval->value] ?? 0),
                'job_cards_pending_review' => JobCardWorkEntry::query()->whereHas('jobCard', fn ($query) => $query->where('tenant_id', $tenantId)->where('company_id', $companyId)->whereIn('approval_status', [JobCardApprovalStatus::PendingVerification->value, JobCardApprovalStatus::Submitted->value]))->count(),
                'waybills_pending_verification' => Waybill::query()->where('tenant_id', $tenantId)->where('company_id', $companyId)->where('status', WaybillStatus::PendingVerification->value)->count(),
                'heavy_evidence_awaiting_finance' => JobCardWorkEntry::query()->whereDoesntHave('billingBatchLine')->whereHas('jobCard', fn ($query) => $query->where('tenant_id', $tenantId)->where('company_id', $companyId)->where('approval_status', JobCardApprovalStatus::Verified->value))->count(),
                'waybills_awaiting_finance' => Waybill::query()->where('tenant_id', $tenantId)->where('company_id', $companyId)->where('status', WaybillStatus::Verified->value)->whereHas('job', fn ($query) => $query->where('job_type', JobType::Trucking->value))->whereDoesntHave('billingBatchLine')->count(),
                'draft_billing_batches' => BillingBatch::query()->where('tenant_id', $tenantId)->where('company_id', $companyId)->where('status', BillingBatchStatus::Draft->value)->count(),
                'prepared_without_record' => BillingBatch::query()->where('tenant_id', $tenantId)->where('company_id', $companyId)->where('status', BillingBatchStatus::Prepared->value)->whereDoesntHave('billingRecord')->count(),
            ],
            'fleet' => [
                'available' => FleetAsset::query()->where('tenant_id', $tenantId)->where('company_id', $companyId)->where('operational_status', FleetAssetOperationalStatus::Available->value)->whereDoesntHave('blockingJobAssignments')->count(),
                'assigned' => FleetAsset::query()->where('tenant_id', $tenantId)->where('company_id', $companyId)->where('operational_status', FleetAssetOperationalStatus::Available->value)->whereHas('blockingJobAssignments', fn ($query) => $query->where('status', JobAssetAssignmentStatus::Assigned->value))->whereDoesntHave('blockingJobAssignments', fn ($query) => $query->where('status', JobAssetAssignmentStatus::Dispatched->value))->count(),
                'dispatched' => FleetAsset::query()->where('tenant_id', $tenantId)->where('company_id', $companyId)->where('operational_status', FleetAssetOperationalStatus::Available->value)->whereHas('blockingJobAssignments', fn ($query) => $query->where('status', JobAssetAssignmentStatus::Dispatched->value))->count(),
                'out_of_service' => FleetAsset::query()->where('tenant_id', $tenantId)->where('company_id', $companyId)->where('operational_status', FleetAssetOperationalStatus::OutOfService->value)->count(),
            ],
        ];
    }
}
