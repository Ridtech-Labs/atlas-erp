<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Waybills\Pages;

use App\Core\Administration\Filament\Resources\Waybills\WaybillResource;
use Filament\Resources\Pages\ListRecords;

class ListWaybills extends ListRecords
{
    protected static string $resource = WaybillResource::class;
}
