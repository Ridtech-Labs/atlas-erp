<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Administration\Enums\PermissionName;
use App\Administration\Enums\RoleName;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (PermissionName::values() as $permissionName) {
            Permission::query()->firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        $rolePermissions = [
            RoleName::SuperAdministrator->value => PermissionName::values(),
            RoleName::CompanyAdministrator->value => [
                PermissionName::DashboardView->value,
                PermissionName::CompaniesView->value,
                PermissionName::CompaniesUpdate->value,
                PermissionName::UsersView->value,
                PermissionName::UsersCreate->value,
                PermissionName::UsersUpdate->value,
                PermissionName::UsersDelete->value,
                PermissionName::UsersManageStatus->value,
                PermissionName::RolesAssign->value,
                PermissionName::SettingsView->value,
                PermissionName::SettingsUpdate->value,
                PermissionName::ActivityLogsView->value,
                PermissionName::HealthView->value,
            ],
            RoleName::OperationsManager->value => [
                PermissionName::DashboardView->value,
            ],
            RoleName::FinanceManager->value => [
                PermissionName::DashboardView->value,
            ],
            RoleName::FleetManager->value => [
                PermissionName::DashboardView->value,
            ],
            RoleName::WarehouseManager->value => [
                PermissionName::DashboardView->value,
            ],
            RoleName::StandardUser->value => [
                PermissionName::DashboardView->value,
            ],
        ];

        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::query()->firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            $role->syncPermissions($permissions);
        }
    }
}
