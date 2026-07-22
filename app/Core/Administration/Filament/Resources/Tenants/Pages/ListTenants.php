<?php

namespace App\Core\Administration\Filament\Resources\Tenants\Pages;

use App\Core\Administration\Filament\Resources\Tenants\TenantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTenants extends ListRecords
{
    protected static string $resource = TenantResource::class;

    protected static ?string $title = 'Companies';

    protected ?string $subheading = 'Manage tenant workspaces, company identity, and operating regions.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('New company'),
        ];
    }
}
