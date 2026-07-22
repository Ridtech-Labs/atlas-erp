<?php

namespace App\Core\Administration\Filament\Resources\Users\Pages;

use App\Core\Administration\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Users';

    protected ?string $subheading = 'Manage internal access, roles, and workspace membership across Atlas ERP.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('New user'),
        ];
    }
}
