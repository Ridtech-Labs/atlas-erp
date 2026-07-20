<?php

declare(strict_types=1);

namespace App\Administration\Actions\Companies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateCompanyAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Tenant $tenant, array $data, User $actor): Tenant
    {
        if (! $actor->can(PermissionName::CompaniesUpdate->value) || ! $this->access->canManageTenant($actor, $tenant->getKey())) {
            throw new BusinessException('You are not allowed to update this company.', 403);
        }

        return DB::transaction(function () use ($tenant, $data, $actor): Tenant {
            $tenant->fill([
                'name' => (string) ($data['name'] ?? $tenant->name),
                'slug' => (string) ($data['slug'] ?? Str::slug((string) ($data['name'] ?? $tenant->name))),
                'timezone' => (string) ($data['timezone'] ?? $tenant->timezone),
                'currency' => (string) ($data['currency'] ?? $tenant->currency),
                'status' => (string) ($data['status'] ?? $tenant->getRawOriginal('status')),
            ]);
            $tenant->save();

            $this->logger->log('company.updated', 'Company updated', $actor, $tenant, [
                'tenant_id' => $tenant->getKey(),
            ]);

            return $tenant->refresh();
        });
    }
}
