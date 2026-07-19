<?php

namespace Database\Seeders;

use App\Core\Settings\DTOs\SettingData;
use App\Core\Settings\Services\SettingService;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $tenant = Tenant::query()->firstOrCreate(
                ['slug' => 'atlas-demo'],
                [
                    'name' => 'Atlas Demo Company',
                    'timezone' => 'Africa/Accra',
                    'currency' => 'GHS',
                    'status' => 'active',
                ],
            );

            $permissions = collect([
                'users.view',
                'users.create',
                'users.update',
                'users.delete',
                'companies.view',
                'companies.update',
                'roles.manage',
                'permissions.manage',
                'settings.manage',
                'dashboard.view',
            ])->map(fn (string $permission) => Permission::query()->firstOrCreate(['name' => $permission, 'guard_name' => 'web']));

            $roles = [
                'Super Administrator',
                'Company Administrator',
                'Operations Manager',
                'Finance Manager',
                'Fleet Manager',
                'Warehouse Manager',
                'HR Manager',
                'Standard User',
            ];

            foreach ($roles as $roleName) {
                $role = Role::query()->firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

                if (in_array($roleName, ['Super Administrator', 'Company Administrator'], true)) {
                    $role->syncPermissions($permissions);
                }
            }

            $admin = User::query()->firstOrCreate(
                ['email' => 'admin@atlas-erp.test'],
                [
                    'tenant_id' => $tenant->id,
                    'first_name' => 'Atlas',
                    'last_name' => 'Administrator',
                    'phone' => '+233000000000',
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'password' => bcrypt('password'),
                ],
            );

            $admin->syncRoles(['Super Administrator']);

            app(SettingService::class)->update(new SettingData(
                tenantId: $tenant->id,
                group: 'company',
                key: 'profile',
                value: [
                    'name' => $tenant->name,
                    'timezone' => $tenant->timezone,
                    'currency' => $tenant->currency,
                    'date_format' => 'Y-m-d',
                    'time_format' => 'H:i',
                    'theme' => 'atlas',
                ],
            ));
        });
    }
}
