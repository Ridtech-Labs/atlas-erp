<?php

declare(strict_types=1);

namespace App\CRM\Services;

use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Tenancy\Models\Company;
use App\CRM\Models\Client;
use App\CRM\Models\ClientSite;

class CrmTenantGuard
{
    public function ensureClientBelongsToCompany(Client $client, Company $company): void
    {
        if ($client->tenant_id !== $company->tenant_id || $client->company_id !== $company->getKey()) {
            throw new BusinessException('The selected client does not belong to the active company.', 422);
        }
    }

    public function ensureSiteBelongsToClient(ClientSite $site, Client $client, Company $company): void
    {
        if (
            $site->tenant_id !== $company->tenant_id
            || $site->company_id !== $company->getKey()
            || $site->client_id !== $client->getKey()
            || $client->company_id !== $company->getKey()
        ) {
            throw new BusinessException('The selected site does not belong to the selected client.', 422);
        }
    }
}
