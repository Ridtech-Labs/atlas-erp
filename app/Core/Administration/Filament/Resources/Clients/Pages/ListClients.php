<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Clients\Pages;

use App\Core\Administration\Filament\Resources\Clients\ClientResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClients extends ListRecords
{
    protected static string $resource = ClientResource::class;

    protected static ?string $title = 'Clients';

    protected ?string $subheading = 'Manage customer accounts, operating sites, and the people tied to each relationship.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('New client'),
        ];
    }
}
