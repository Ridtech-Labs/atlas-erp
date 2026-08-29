<?php

declare(strict_types=1);

namespace App\Administration\Enums;

enum PermissionName: string
{
    case DashboardView = 'dashboard.view';
    case CompaniesView = 'companies.view';
    case CompaniesCreate = 'companies.create';
    case CompaniesUpdate = 'companies.update';
    case CompaniesDelete = 'companies.delete';
    case UsersView = 'users.view';
    case UsersCreate = 'users.create';
    case UsersUpdate = 'users.update';
    case UsersDelete = 'users.delete';
    case UsersManageStatus = 'users.manage_status';
    case RolesManage = 'roles.manage';
    case RolesView = 'roles.view';
    case RolesCreate = 'roles.create';
    case RolesUpdate = 'roles.update';
    case RolesDelete = 'roles.delete';
    case RolesAssign = 'roles.assign';
    case PermissionsView = 'permissions.view';
    case SettingsManage = 'settings.manage';
    case SettingsView = 'settings.view';
    case SettingsUpdate = 'settings.update';
    case ActivityLogsView = 'activity_logs.view';
    case HealthView = 'health.view';
    case ClientsViewAny = 'clients.view_any';
    case ClientsView = 'clients.view';
    case ClientsCreate = 'clients.create';
    case ClientsUpdate = 'clients.update';
    case ClientsDelete = 'clients.delete';
    case ClientsRestore = 'clients.restore';
    case ClientsForceDelete = 'clients.force_delete';
    case ClientContactsViewAny = 'client_contacts.view_any';
    case ClientContactsView = 'client_contacts.view';
    case ClientContactsCreate = 'client_contacts.create';
    case ClientContactsUpdate = 'client_contacts.update';
    case ClientContactsDelete = 'client_contacts.delete';
    case ClientSitesViewAny = 'client_sites.view_any';
    case ClientSitesView = 'client_sites.view';
    case ClientSitesCreate = 'client_sites.create';
    case ClientSitesUpdate = 'client_sites.update';
    case ClientSitesDelete = 'client_sites.delete';
    case JobsViewAny = 'jobs.view_any';
    case JobsView = 'jobs.view';
    case JobsCreate = 'jobs.create';
    case JobsUpdate = 'jobs.update';
    case JobsDelete = 'jobs.delete';
    case JobsSubmit = 'jobs.submit';
    case JobsApprove = 'jobs.approve';
    case JobCardsVerify = 'job_cards.verify';
    case JobCardsBill = 'job_cards.bill';
    case RateAgreementsView = 'rate_agreements.view';
    case RateAgreementsManage = 'rate_agreements.manage';
    case BillingBatchesView = 'billing_batches.view';
    case BillingBatchesManage = 'billing_batches.manage';
    case BillingRecordsView = 'billing_records.view';
    case BillingRecordsManage = 'billing_records.manage';
    case JobsSchedule = 'jobs.schedule';
    case JobsStart = 'jobs.start';
    case JobsHold = 'jobs.hold';
    case JobsResume = 'jobs.resume';
    case JobsComplete = 'jobs.complete';
    case JobsCancel = 'jobs.cancel';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(
            static fn (self $permission): string => $permission->value,
            self::cases(),
        );
    }
}
