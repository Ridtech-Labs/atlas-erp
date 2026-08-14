<?php

namespace Database\Seeders;

use App\Administration\Enums\RoleName;
use App\Core\Settings\DTOs\SettingData;
use App\Core\Settings\Services\SettingService;
use App\Core\Tenancy\Models\Company;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);

        DB::transaction(function (): void {
            $tenant = Tenant::query()->firstOrCreate(
                ['slug' => 'atlas-demo'],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => 'Atlas Demo Company',
                    'email' => 'hello@atlas-erp.test',
                    'phone' => '+233000000000',
                    'address' => 'Atlas ERP Demo Address',
                    'city' => 'Accra',
                    'country' => 'Ghana',
                    'timezone' => 'Africa/Accra',
                    'currency' => 'GHS',
                    'status' => 'active',
                ],
            );

            $kadmayTenant = Tenant::query()->firstOrCreate(
                ['slug' => 'kadmay'],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => 'Kadmay',
                    'email' => 'hello@kadmay.test',
                    'phone' => '+233000000001',
                    'address' => 'Kadmay Workspace',
                    'city' => 'Accra',
                    'country' => 'Ghana',
                    'timezone' => 'Africa/Accra',
                    'currency' => 'GHS',
                    'status' => 'active',
                ],
            );

            $admin = User::query()->firstOrCreate(
                ['email' => 'admin@atlas-erp.test'],
                [
                    'uuid' => (string) Str::uuid(),
                    'tenant_id' => $tenant->id,
                    'first_name' => 'Atlas',
                    'last_name' => 'Administrator',
                    'phone' => '+233000000000',
                    'last_login_ip' => '127.0.0.1',
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'password' => bcrypt('password'),
                ],
            );

            $admin->syncRoles([RoleName::SuperAdministrator->value]);

            $ridwan = User::query()->updateOrCreate(
                ['email' => 'kadriridwan0@gmail.com'],
                [
                    'uuid' => (string) Str::uuid(),
                    'tenant_id' => $kadmayTenant->id,
                    'first_name' => 'Ridwan',
                    'last_name' => 'Kadri',
                    'phone' => '+233000000002',
                    'last_login_ip' => '127.0.0.1',
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'password' => bcrypt('password'),
                ],
            );

            $ridwan->syncRoles([RoleName::SuperAdministrator->value]);

            $atlasCompany = Company::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'is_default' => true,
                ],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $tenant->name,
                    'legal_name' => $tenant->name,
                    'code' => 'ATLAS-001',
                    'status' => 'active',
                    'currency' => $tenant->currency,
                    'country' => $tenant->country,
                ],
            );

            $kadmayCompany = Company::query()->firstOrCreate(
                [
                    'tenant_id' => $kadmayTenant->id,
                    'is_default' => true,
                ],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $kadmayTenant->name,
                    'legal_name' => $kadmayTenant->name,
                    'code' => 'KADMAY-001',
                    'status' => 'active',
                    'currency' => $kadmayTenant->currency,
                    'country' => $kadmayTenant->country,
                ],
            );

            $admin->companies()->syncWithoutDetaching([$atlasCompany->getKey()]);
            $ridwan->companies()->syncWithoutDetaching([$kadmayCompany->getKey()]);

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
                    'country' => $tenant->country,
                    'language' => 'en',
                ],
            ));

            app(SettingService::class)->update(new SettingData(
                tenantId: $tenant->id,
                group: 'branding',
                key: 'identity',
                value: [
                    'logo' => null,
                    'small_logo' => null,
                    'primary_brand_preference' => 'amber',
                ],
            ));

            app(SettingService::class)->update(new SettingData(
                tenantId: $tenant->id,
                group: 'notifications',
                key: 'channels',
                value: [
                    'email_notifications_enabled' => true,
                    'database_notifications_enabled' => true,
                ],
            ));
        });
    }
}
