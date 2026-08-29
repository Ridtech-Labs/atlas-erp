<?php

declare(strict_types=1);

namespace App\Finance\Actions\BillingBatches;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Shared\Services\TenantSequenceService;
use App\CRM\Models\Client;
use App\Finance\Enums\BillingBatchStatus;
use App\Finance\Models\BillingBatch;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateBillingBatchAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
        private readonly TenantSequenceService $sequences,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, User $actor): BillingBatch
    {
        if (! $actor->hasPermissionTo(PermissionName::BillingBatchesManage->value)) {
            throw new BusinessException('You are not allowed to manage Billing Batches.', 403);
        }

        $client = $this->resolveClient($data, $actor);

        return DB::transaction(function () use ($data, $actor, $client): BillingBatch {
            $batch = BillingBatch::query()->create([
                ...Arr::except($data, ['tenant_id', 'company_id', 'client_id']),
                'uuid' => (string) Str::uuid(),
                'tenant_id' => $client->tenant_id,
                'company_id' => $client->company_id,
                'client_id' => $client->getKey(),
                'batch_number' => sprintf('BB-%05d', $this->sequences->nextValue($client->tenant_id, 'billing_batch_number')),
                'status' => BillingBatchStatus::Draft->value,
                'created_by' => $actor->getKey(),
                'updated_by' => $actor->getKey(),
            ]);

            $this->logger->log('billing_batch.created', 'Billing Batch created', $actor, $batch, [
                'tenant_id' => $batch->tenant_id,
                'company_id' => $batch->company_id,
                'client_id' => $batch->client_id,
                'batch_number' => $batch->batch_number,
            ]);

            return $batch->refresh();
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function resolveClient(array $data, User $actor): Client
    {
        $clientId = $data['client_id'] ?? null;

        if (! is_numeric($clientId)) {
            throw new BusinessException('Select a client for this Billing Batch.', 422);
        }

        $client = Client::query()->find((int) $clientId);

        if (! $client instanceof Client || ! $this->access->canAccessActiveOperationalCompany($actor, $client->company_id, $client->tenant_id)) {
            throw new BusinessException('You are not allowed to create a Billing Batch for the selected client.', 403);
        }

        return $client;
    }
}
