<?php

namespace Database\Seeders;

use App\Administration\Enums\RoleName;
use App\Core\Settings\DTOs\SettingData;
use App\Core\Settings\Services\SettingService;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->call(RoleAndPermissionSeeder::class);

            $tenant = Tenant::query()->firstOrCreate(
                ['slug' => 'atlas-demo'],
                [
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

            $admin = User::query()->firstOrCreate(
                ['email' => 'admin@atlas-erp.test'],
                [
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
