<?php

namespace App\Core\Administration\Filament\Resources\Roles\Pages;

use App\Core\Administration\Filament\Resources\Roles\RoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected static ?string $title = 'Roles & Permissions';

    protected ?string $subheading = 'Bundle access safely into reusable roles for platform and company teams.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('New role'),
        ];
    }
}
