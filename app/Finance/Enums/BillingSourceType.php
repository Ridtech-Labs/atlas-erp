<?php

declare(strict_types=1);

namespace App\Finance\Enums;

enum BillingSourceType: string
{
    case HeavyMachineryWorkEntry = 'heavy_machinery_work_entry';
    case TruckingWaybill = 'trucking_waybill';
}
