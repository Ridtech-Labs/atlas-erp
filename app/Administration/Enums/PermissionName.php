<?php

declare(strict_types=1);

namespace App\Administration\Enums;

enum PermissionName: string
{
    case DashboardView = 'dashboard.view';
    case CompaniesView = 'companies.view';
    case CompaniesCreate = 'companies.create';
    case CompaniesUpdate = 'companies.update';
    case UsersView = 'users.view';
    case UsersCreate = 'users.create';
    case UsersUpdate = 'users.update';
    case UsersDelete = 'users.delete';
    case UsersManageStatus = 'users.manage_status';
    case SettingsManage = 'settings.manage';
    case HealthView = 'health.view';

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
