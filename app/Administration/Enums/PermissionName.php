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
    case RolesView = 'roles.view';
    case RolesCreate = 'roles.create';
    case RolesUpdate = 'roles.update';
    case RolesDelete = 'roles.delete';
    case RolesAssign = 'roles.assign';
    case PermissionsView = 'permissions.view';
    case SettingsView = 'settings.view';
    case SettingsUpdate = 'settings.update';
    case ActivityLogsView = 'activity_logs.view';
    case HealthView = 'health.view';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $permission): string => $permission->value, self::cases());
    }
}
