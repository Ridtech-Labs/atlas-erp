<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Activities\Pages;

use App\Core\Administration\Filament\Resources\Activities\ActivityResource;
use Filament\Resources\Pages\ListRecords;

class ListActivities extends ListRecords
{
    protected static string $resource = ActivityResource::class;
}
