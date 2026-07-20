<?php

namespace Tests;

use App\Administration\Enums\RoleName;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Support\InteractsWithAtlas;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use InteractsWithAtlas;

    protected function seedAccessControl(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);
    }

    protected function tenant(array $attributes = []): Tenant
    {
        return Tenant::factory()->create($attributes);
    }

    /**
     * @param  list<string>  $roles
     */
    protected function tenantUser(?Tenant $tenant = null, array $attributes = [], array $roles = []): User
    {
        $user = User::factory()->create([
            'tenant_id' => $tenant?->getKey() ?? $this->tenant()->getKey(),
            ...$attributes,
        ]);

        if ($roles !== []) {
            $user->syncRoles($roles);
        }

        return $user;
    }

    protected function actingAsRole(string $roleName, ?Tenant $tenant = null, array $attributes = []): User
    {
        $user = $this->tenantUser($tenant, $attributes, [$roleName]);
        $this->actingAs($user);

        return $user;
    }

    protected function actingAsCompanyAdministrator(?Tenant $tenant = null, array $attributes = []): User
    {
        return $this->actingAsRole(RoleName::CompanyAdministrator->value, $tenant, $attributes);
    }
}
