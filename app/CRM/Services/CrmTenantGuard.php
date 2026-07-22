<?php

declare(strict_types=1);

namespace App\CRM\Services;

use App\Core\Shared\Exceptions\BusinessException;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;

class CrmTenantGuard
{
    public function ensureClientBelongsToTenant(Client $client, int $tenantId): void
    {
        if ($client->tenant_id !== $tenantId) {
            throw new BusinessException('The selected client does not belong to the active company.', 422);
        }
    }

    public function ensureSiteBelongsToClient(ClientSite $site, Client $client, int $tenantId): void
    {
        if ($site->tenant_id !== $tenantId || $site->client_id !== $client->getKey()) {
            throw new BusinessException('The selected site does not belong to the selected client.', 422);
        }
    }
}
