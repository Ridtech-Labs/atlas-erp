<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\FleetAssets\Pages;

use App\Core\Administration\Filament\Resources\FleetAssets\FleetAssetResource;
use App\Fleet\Models\FleetAsset;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFleetAsset extends ViewRecord
{
    protected static string $resource = FleetAssetResource::class;

    public function getTitle(): string
    {
        $record = $this->getRecord();

        return $record instanceof FleetAsset ? $record->asset_number : 'Fleet Asset';
    }

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
