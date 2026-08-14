<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Shared\Services\TenantSequenceService;
use App\Core\Tenancy\Models\Company;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use App\CRM\Services\CrmTenantGuard;
use App\Models\User;
use App\Operations\Enums\JobShift;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use App\Operations\Support\JobPlanningFieldMapper;
use App\Operations\Support\OperatorAssignmentService;
use App\Operations\Traits\AppliesJobAudit;
use Illuminate\Support\Facades\DB;

class CreateJobAction
{
    use AppliesJobAudit;

    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly TenantSequenceService $sequences,
        private readonly CrmTenantGuard $guard,
        private readonly JobPlanningFieldMapper $fieldMapper,
        private readonly OperatorAssignmentService $operators,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, User $actor): Job
    {
        $company = $this->resolveCompany($data, $actor);
        $tenantId = $company->tenant_id;

        if (! $actor->hasPermissionTo(PermissionName::JobsCreate->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $company->getKey(), $tenantId)) {
            throw new BusinessException('You are not allowed to create jobs for the selected company.', 403);
        }

        return DB::transaction(function () use ($data, $actor, $tenantId, $company): Job {
            $client = Client::query()->withoutGlobalScopes()->findOrFail((int) $data['client_id']);
            $this->guard->ensureClientBelongsToCompany($client, $company);

            $siteId = $data['client_site_id'] ?? null;
            $site = null;

            if (filled($siteId)) {
                $site = ClientSite::query()->withoutGlobalScopes()->findOrFail((int) $siteId);
                $this->guard->ensureSiteBelongsToClient($site, $client, $company);

                if (! $site->isActive()) {
                    throw new BusinessException('Only active client sites can be attached to a job.', 422);
                }
            }

            $assignment = $this->operators->resolveAssignment(
                $data['assigned_operator_id'] ?? null,
                $data['assigned_operator_name'] ?? null,
                $tenantId,
                $company->getKey(),
            );
            $this->validateDates($data);

            $attributes = $this->fieldMapper->canonicalAndLegacyAttributes($data, $company);

            $job = new Job;
            $job->fill(collect($attributes)->except(['tenant_id', 'company_id', 'job_number', 'created_by', 'updated_by'])->all());
            $job->tenant_id = $tenantId;
            $job->company_id = $company->getKey();
            $job->client_id = $client->getKey();
            $job->client_site_id = $site?->getKey();
            $job->assigned_operator_id = $assignment['operator']?->getKey();
            $job->assigned_operator_name = $assignment['external_name'];
            $job->shift = $data['shift'] ?? JobShift::Custom->value;
            $job->job_number = $this->generateJobNumber($tenantId);
            $job->status = $data['status'] ?? JobStatus::Draft->value;
            $this->stampCreationAudit($job, $actor);
            $job->save();

            $this->logger->log('job.created', sprintf('Job created by %s', $actor->full_name), $actor, $job, [
                'tenant_id' => $job->tenant_id,
                'company_id' => $job->company_id,
                'job_number' => $job->job_number,
            ]);

            $operatorName = $job->plannedOperatorName();

            if ($operatorName !== null) {
                $this->logger->log('job.operator_assigned', sprintf('Operator assigned: %s', $operatorName), $actor, $job, [
                    'tenant_id' => $job->tenant_id,
                    'company_id' => $job->company_id,
                    'job_number' => $job->job_number,
                    'operator_name' => $operatorName,
                ]);
            }

            return $job->refresh();
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function resolveCompany(array $data, User $actor): Company
    {
        $tenantId = $this->access->activeTenantId($actor) ?? $actor->tenant_id;
        $activeCompany = $this->access->activeCompany($actor);
        $selectedCompanyId = $activeCompany?->getKey();

        if (! is_numeric($selectedCompanyId)) {
            throw new BusinessException('Select an active company before creating a job.', 422);
        }

        $company = Company::query()
            ->whereKey((int) $selectedCompanyId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (! $company instanceof Company || ! $this->access->canAccessActiveOperationalCompany($actor, $company->getKey(), $tenantId)) {
            throw new BusinessException('You are not allowed to create jobs for the selected company.', 403);
        }

        return $company;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function validateDates(array $data): void
    {
        if (filled($data['planned_start_date'] ?? null) && filled($data['planned_end_date'] ?? null)
            && $data['planned_end_date'] < $data['planned_start_date']) {
            throw new BusinessException('Planned end date cannot precede planned start date.', 422);
        }

        if (filled($data['actual_start_date'] ?? null) && filled($data['actual_end_date'] ?? null)
            && $data['actual_end_date'] < $data['actual_start_date']) {
            throw new BusinessException('Actual end date cannot precede actual start date.', 422);
        }
    }

    private function generateJobNumber(int $tenantId): string
    {
        return sprintf('JOB-%05d', $this->sequences->nextValue($tenantId, 'job_number'));
    }
}
