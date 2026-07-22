<?php

declare(strict_types=1);

namespace App\CRM\Actions\Clients;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\CRM\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateClientAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Client $client, array $data, User $actor): Client
    {
        if (! $actor->hasPermissionTo(PermissionName::ClientsUpdate->value) || ! $this->access->canAccessTenant($actor, $client->tenant_id)) {
            throw new BusinessException('You are not allowed to update this client.', 403);
        }

        return DB::transaction(function () use ($client, $data, $actor): Client {
            $client->fill($data);
            $client->updated_by = $actor->getKey();
            $client->save();

            $this->logger->log('client.updated', 'Client updated', $actor, $client, [
                'tenant_id' => $client->tenant_id,
                'client_code' => $client->client_code,
            ]);

            return $client->refresh();
        });
    }
}
