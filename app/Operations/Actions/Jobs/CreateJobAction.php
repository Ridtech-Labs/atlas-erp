<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Shared\Services\TenantSequenceService;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use App\CRM\Services\CrmTenantGuard;
use App\Models\User;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CreateJobAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly TenantSequenceService $sequences,
        private readonly CrmTenantGuard $guard,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, User $actor): Job
    {
        $tenantId = (int) ($data['tenant_id'] ?? $actor->tenant_id);

        if (! $actor->hasPermissionTo(PermissionName::JobsCreate->value) || ! $this->access->canAccessTenant($actor, $tenantId)) {
            throw new BusinessException('You are not allowed to create jobs for the selected company.', 403);
        }

        return DB::transaction(function () use ($data, $actor, $tenantId): Job {
            $client = Client::query()->withoutGlobalScopes()->findOrFail((int) $data['client_id']);
            $this->guard->ensureClientBelongsToTenant($client, $tenantId);

            $siteId = $data['client_site_id'] ?? null;
            $site = null;

            if (filled($siteId)) {
                $site = ClientSite::query()->withoutGlobalScopes()->findOrFail((int) $siteId);
                $this->guard->ensureSiteBelongsToClient($site, $client, $tenantId);

                if (! $site->is_active) {
                    throw new BusinessException('Only active client sites can be attached to a job.', 422);
                }
            }

            $this->validateDates($data);

            $job = Job::query()->create([
                ...Arr::except($data, ['tenant_id', 'job_number']),
                'tenant_id' => $tenantId,
                'client_id' => $client->getKey(),
                'client_site_id' => $site?->getKey(),
                'job_number' => $data['job_number'] ?? $this->generateJobNumber($tenantId),
                'status' => $data['status'] ?? JobStatus::Draft->value,
                'created_by' => $actor->getKey(),
                'updated_by' => $actor->getKey(),
            ]);

            $this->logger->log('job.created', 'Job created', $actor, $job, [
                'tenant_id' => $job->tenant_id,
                'job_number' => $job->job_number,
            ]);

            return $job->refresh();
        });
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
