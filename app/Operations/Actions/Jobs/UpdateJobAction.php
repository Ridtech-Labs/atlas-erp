<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
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

class UpdateJobAction
{
    use AppliesJobAudit;

    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly CrmTenantGuard $guard,
        private readonly JobPlanningFieldMapper $fieldMapper,
        private readonly OperatorAssignmentService $operators,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Job $job, array $data, User $actor): Job
    {
        if (! $actor->hasPermissionTo(PermissionName::JobsUpdate->value)
            || ! $this->access->canAccessActiveOperationalCompany($actor, $job->company_id, $job->tenant_id)) {
            throw new BusinessException('You are not allowed to update this job.', 403);
        }

        if (in_array((string) $job->getRawOriginal('status'), [JobStatus::InProgress->value, JobStatus::OnHold->value, JobStatus::Completed->value, JobStatus::Cancelled->value], true)
            && ! $actor->hasPermissionTo(PermissionName::JobsApprove->value)) {
            throw new BusinessException('Planning fields are read-only after the job has started.', 422);
        }

        if ((string) $job->getRawOriginal('status') !== JobStatus::Draft->value && array_key_exists('status', $data)) {
            unset($data['status']);
        }

        return DB::transaction(function () use ($job, $data, $actor): Job {
            $company = Company::query()->findOrFail($job->company_id);
            $client = Client::query()->withoutGlobalScopes()->findOrFail((int) ($data['client_id'] ?? $job->client_id));
            $this->guard->ensureClientBelongsToCompany($client, $company);

            $siteId = $data['client_site_id'] ?? $job->client_site_id;
            if (filled($siteId)) {
                $site = ClientSite::query()->withoutGlobalScopes()->findOrFail((int) $siteId);
                $this->guard->ensureSiteBelongsToClient($site, $client, $company);

                if (! $site->isActive()) {
                    throw new BusinessException('Only active client sites can be attached to a job.', 422);
                }
            }

            $previousOperatorName = $job->plannedOperatorName();
            $assignment = $this->operators->resolveAssignment(
                $data['assigned_operator_id'] ?? $job->assigned_operator_id,
                array_key_exists('assigned_operator_name', $data) ? $data['assigned_operator_name'] : $job->assigned_operator_name,
                $job->tenant_id,
                $company->getKey(),
            );

            $attributes = $this->fieldMapper->canonicalAndLegacyAttributes($data, $company, $job);

            $job->fill(collect($attributes)->except(['tenant_id', 'company_id', 'created_by', 'updated_by'])->all());
            $job->assigned_operator_id = $assignment['operator']?->getKey();
            $job->assigned_operator_name = $assignment['external_name'];
            $job->shift = $data['shift'] ?? $job->getRawOriginal('shift') ?? JobShift::Custom->value;
            $this->stampUpdateAudit($job, $actor);
            $job->save();
            $job->unsetRelation('assignedOperator');

            $this->logger->log('job.updated', sprintf('Job updated by %s', $actor->full_name), $actor, $job, [
                'tenant_id' => $job->tenant_id,
                'company_id' => $job->company_id,
                'job_number' => $job->job_number,
            ]);

            $currentOperatorName = $job->plannedOperatorName();

            if ($previousOperatorName !== $currentOperatorName) {
                $description = match (true) {
                    $previousOperatorName === null && $currentOperatorName !== null => sprintf('Operator assigned: %s', $currentOperatorName),
                    $previousOperatorName !== null && $currentOperatorName === null => sprintf('Operator cleared from %s', $previousOperatorName),
                    default => sprintf('Operator changed from %s to %s', $previousOperatorName, $currentOperatorName),
                };

                $this->logger->log('job.operator_changed', $description, $actor, $job, [
                    'tenant_id' => $job->tenant_id,
                    'company_id' => $job->company_id,
                    'job_number' => $job->job_number,
                    'previous_operator_name' => $previousOperatorName,
                    'current_operator_name' => $currentOperatorName,
                ]);
            }

            return $job->refresh();
        });
    }
}
