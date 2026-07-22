<?php

declare(strict_types=1);

namespace App\CRM\Actions\Clients;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Shared\Services\TenantSequenceService;
use App\CRM\Models\Client;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CreateClientAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly TenantSequenceService $sequences,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, User $actor): Client
    {
        $tenantId = (int) ($data['tenant_id'] ?? $actor->tenant_id);

        if (! $actor->hasPermissionTo(PermissionName::ClientsCreate->value) || ! $this->access->canAccessTenant($actor, $tenantId)) {
            throw new BusinessException('You are not allowed to create clients for the selected company.', 403);
        }

        return DB::transaction(function () use ($data, $actor, $tenantId): Client {
            $client = Client::query()->create([
                ...Arr::except($data, ['tenant_id', 'client_code']),
                'tenant_id' => $tenantId,
                'client_code' => $data['client_code'] ?? $this->generateClientCode($tenantId),
                'created_by' => $actor->getKey(),
                'updated_by' => $actor->getKey(),
            ]);

            $this->logger->log('client.created', 'Client created', $actor, $client, [
                'tenant_id' => $client->tenant_id,
                'client_code' => $client->client_code,
            ]);

            return $client->refresh();
        });
    }

    private function generateClientCode(int $tenantId): string
    {
        return sprintf('CLI-%05d', $this->sequences->nextValue($tenantId, 'client_code'));
    }
}
