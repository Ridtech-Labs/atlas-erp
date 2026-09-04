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
                PermissionName::UsersView->value,
                PermissionName::UsersCreate->value,
                PermissionName::UsersUpdate->value,
                PermissionName::UsersDelete->value,
                PermissionName::UsersManageStatus->value,
                PermissionName::RolesAssign->value,
                PermissionName::SettingsManage->value,
                PermissionName::SettingsView->value,
                PermissionName::SettingsUpdate->value,
                PermissionName::ActivityLogsView->value,
                PermissionName::HealthView->value,
                PermissionName::ClientsViewAny->value,
                PermissionName::ClientsView->value,
                PermissionName::ClientsCreate->value,
                PermissionName::ClientsUpdate->value,
                PermissionName::ClientsDelete->value,
                PermissionName::ClientContactsViewAny->value,
                PermissionName::ClientContactsView->value,
                PermissionName::ClientContactsCreate->value,
                PermissionName::ClientContactsUpdate->value,
                PermissionName::ClientContactsDelete->value,
                PermissionName::ClientSitesViewAny->value,
                PermissionName::ClientSitesView->value,
                PermissionName::ClientSitesCreate->value,
                PermissionName::ClientSitesUpdate->value,
                PermissionName::ClientSitesDelete->value,
                PermissionName::JobsViewAny->value,
                PermissionName::JobsView->value,
                PermissionName::JobsCreate->value,
                PermissionName::JobsUpdate->value,
                PermissionName::JobsDelete->value,
                PermissionName::JobsSubmit->value,
                PermissionName::JobsApprove->value,
                PermissionName::JobCardsVerify->value,
                PermissionName::JobCardsBill->value,
                PermissionName::RateAgreementsView->value,
                PermissionName::RateAgreementsManage->value,
                PermissionName::BillingBatchesView->value,
                PermissionName::BillingBatchesManage->value,
                PermissionName::BillingRecordsView->value,
                PermissionName::BillingRecordsManage->value,
                PermissionName::FleetAssetsView->value,
                PermissionName::FleetAssetsManage->value,
                PermissionName::FleetAssignmentsView->value,
                PermissionName::FleetAssignmentsManage->value,
                PermissionName::JobsSchedule->value,
                PermissionName::JobsStart->value,
                PermissionName::JobsHold->value,
                PermissionName::JobsResume->value,
                PermissionName::JobsComplete->value,
                PermissionName::JobsCancel->value,
            ],
            RoleName::OperationsManager->value => [
                PermissionName::DashboardView->value,
                PermissionName::ClientsViewAny->value,
                PermissionName::ClientsView->value,
                PermissionName::ClientContactsViewAny->value,
                PermissionName::ClientContactsView->value,
                PermissionName::ClientSitesViewAny->value,
                PermissionName::ClientSitesView->value,
                PermissionName::JobsViewAny->value,
                PermissionName::JobsView->value,
                PermissionName::JobsCreate->value,
                PermissionName::JobsUpdate->value,
                PermissionName::JobsSubmit->value,
                PermissionName::JobsSchedule->value,
                PermissionName::JobsStart->value,
                PermissionName::JobsHold->value,
                PermissionName::JobsResume->value,
                PermissionName::JobsComplete->value,
                PermissionName::JobsCancel->value,
                PermissionName::FleetAssetsView->value,
                PermissionName::FleetAssetsManage->value,
                PermissionName::FleetAssignmentsView->value,
                PermissionName::FleetAssignmentsManage->value,
            ],
            RoleName::DataEntryClerk->value => [
                PermissionName::DashboardView->value,
                PermissionName::ClientsViewAny->value,
                PermissionName::ClientsView->value,
                PermissionName::ClientSitesViewAny->value,
                PermissionName::ClientSitesView->value,
                PermissionName::JobsViewAny->value,
                PermissionName::JobsView->value,
                PermissionName::JobsCreate->value,
                PermissionName::JobsUpdate->value,
                PermissionName::JobsDelete->value,
                PermissionName::JobsSubmit->value,
                PermissionName::FleetAssetsView->value,
                PermissionName::FleetAssetsManage->value,
                PermissionName::FleetAssignmentsView->value,
            ],
            RoleName::FinanceManager->value => [
                PermissionName::DashboardView->value,
                PermissionName::JobsViewAny->value,
                PermissionName::JobsView->value,
                PermissionName::JobCardsVerify->value,
                PermissionName::JobCardsBill->value,
                PermissionName::RateAgreementsView->value,
                PermissionName::RateAgreementsManage->value,
                PermissionName::BillingBatchesView->value,
                PermissionName::BillingBatchesManage->value,
                PermissionName::BillingRecordsView->value,
                PermissionName::BillingRecordsManage->value,
            ],
            RoleName::Accountant->value => [
                PermissionName::DashboardView->value,
                PermissionName::JobsViewAny->value,
                PermissionName::JobsView->value,
                PermissionName::JobCardsVerify->value,
                PermissionName::JobCardsBill->value,
                PermissionName::RateAgreementsView->value,
                PermissionName::BillingBatchesView->value,
                PermissionName::BillingBatchesManage->value,
                PermissionName::BillingRecordsView->value,
                PermissionName::BillingRecordsManage->value,
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
