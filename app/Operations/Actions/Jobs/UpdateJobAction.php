<?php

declare(strict_types=1);

namespace App\Operations\Actions\Jobs;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;
use App\CRM\Services\CrmTenantGuard;
use App\Models\User;
use App\Operations\Enums\JobStatus;
use App\Operations\Models\Job;
use Illuminate\Support\Facades\DB;

class UpdateJobAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly CrmTenantGuard $guard,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Job $job, array $data, User $actor): Job
    {
        if (! $actor->hasPermissionTo(PermissionName::JobsUpdate->value) || ! $this->access->canAccessTenant($actor, $job->tenant_id)) {
            throw new BusinessException('You are not allowed to update this job.', 403);
        }

        if ((string) $job->getRawOriginal('status') !== JobStatus::Draft->value && array_key_exists('status', $data)) {
            unset($data['status']);
        }

        return DB::transaction(function () use ($job, $data, $actor): Job {
            $client = Client::query()->withoutGlobalScopes()->findOrFail((int) ($data['client_id'] ?? $job->client_id));
            $this->guard->ensureClientBelongsToTenant($client, $job->tenant_id);

            $siteId = $data['client_site_id'] ?? $job->client_site_id;
            if (filled($siteId)) {
                $site = ClientSite::query()->withoutGlobalScopes()->findOrFail((int) $siteId);
                $this->guard->ensureSiteBelongsToClient($site, $client, $job->tenant_id);
            }

            $job->fill($data);
            $job->updated_by = $actor->getKey();
            $job->save();

            $this->logger->log('job.updated', 'Job updated', $actor, $job, [
                'tenant_id' => $job->tenant_id,
                'job_number' => $job->job_number,
            ]);

            return $job->refresh();
        });
    }
}
