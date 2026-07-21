<?php

declare(strict_types=1);

namespace App\Administration\Actions\Companies;

use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Arr;
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
    public function execute(array $data, ?User $actor = null): Tenant
    {
        return DB::transaction(function () use ($data, $actor): Tenant {
            $tenant = Tenant::query()->create([
                ...Arr::except($data, ['logo']),
                'slug' => $data['slug'] ?? Str::slug((string) $data['name']),
            ]);

            $this->logger->log('company.created', 'Company created', $actor, $tenant, [
                'tenant_id' => $tenant->id,
            ]);

            return $tenant;
        });
    }
}
