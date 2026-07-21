<?php

declare(strict_types=1);

namespace App\Administration\Enums;

enum RoleName: string
{
    case SuperAdministrator = 'Super Administrator';
    case CompanyAdministrator = 'Company Administrator';
    case OperationsManager = 'Operations Manager';
    case FinanceManager = 'Finance Manager';
    case FleetManager = 'Fleet Manager';
    case WarehouseManager = 'Warehouse Manager';
    case StandardUser = 'Standard User';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $role): string => $role->value, self::cases());
    }
}
