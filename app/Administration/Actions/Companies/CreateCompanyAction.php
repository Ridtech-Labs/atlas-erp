<?php

declare(strict_types=1);

namespace App\Administration\Actions\Companies;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateCompanyAction
{
    public function __construct(
        private readonly AdministrationActivityLogger $logger,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, User $actor): Tenant
    {
        if (! $actor->can(PermissionName::CompaniesCreate->value)) {
            throw new BusinessException('You are not allowed to create companies.', 403);
        }

        return DB::transaction(function () use ($data, $actor): Tenant {
            $tenant = Tenant::query()->create([
                'name' => (string) $data['name'],
                'slug' => (string) ($data['slug'] ?? Str::slug((string) $data['name'])),
                'timezone' => (string) ($data['timezone'] ?? 'Africa/Accra'),
                'currency' => (string) ($data['currency'] ?? 'GHS'),
                'status' => (string) ($data['status'] ?? 'active'),
            ]);

            $this->logger->log('company.created', 'Company created', $actor, $tenant, [
                'tenant_id' => $tenant->getKey(),
            ]);

            return $tenant;
        });
    }
}
