<?php

declare(strict_types=1);

namespace App\CRM\Actions\ClientSites;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\CRM\Models\ClientSite;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SetPrimaryClientSiteAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(ClientSite $site, User $actor): ClientSite
    {
        if (! $actor->hasPermissionTo(PermissionName::ClientSitesUpdate->value)
            || ! $this->access->canAccessOperationalCompany($actor, $site->company_id, $site->tenant_id)) {
            throw new BusinessException('You are not allowed to update this client site.', 403);
        }

        return DB::transaction(function () use ($site, $actor): ClientSite {
            ClientSite::query()
                ->where('client_id', $site->client_id)
                ->lockForUpdate()
                ->update(['is_primary' => false]);

            $site->forceFill(['is_primary' => true])->save();

            $this->logger->log('client.primary_site_changed', 'Primary client site changed', $actor, $site, [
                'tenant_id' => $site->tenant_id,
                'company_id' => $site->company_id,
                'client_id' => $site->client_id,
            ]);

            return $site->refresh();
        });
    }
}
