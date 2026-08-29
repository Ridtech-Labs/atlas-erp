<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\FleetAssetTypes\Pages;

use App\Core\Administration\Filament\Resources\FleetAssetTypes\FleetAssetTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFleetAssetType extends ViewRecord
{
    protected static string $resource = FleetAssetTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
