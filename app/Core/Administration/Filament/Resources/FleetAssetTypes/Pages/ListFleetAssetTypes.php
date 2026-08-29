<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\FleetAssetTypes\Pages;

use App\Core\Administration\Filament\Resources\FleetAssetTypes\FleetAssetTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFleetAssetTypes extends ListRecords
{
    protected static string $resource = FleetAssetTypeResource::class;

    protected static ?string $title = 'Asset Types';

    protected ?string $subheading = 'Maintain the controlled Fleet classifications used to identify machinery and transport assets.';

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('New asset type')];
    }
}
