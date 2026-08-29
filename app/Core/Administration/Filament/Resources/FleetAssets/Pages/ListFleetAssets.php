<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\FleetAssets\Pages;

use App\Core\Administration\Filament\Resources\FleetAssets\FleetAssetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFleetAssets extends ListRecords
{
    protected static string $resource = FleetAssetResource::class;

    protected static ?string $title = 'Fleet Assets';

    protected ?string $subheading = 'Maintain the canonical operational identity and availability status for company Fleet assets.';

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('New Fleet asset')];
    }
}
