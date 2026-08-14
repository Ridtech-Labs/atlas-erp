<?php

namespace App\Core\Administration\Filament\Resources\Tenants\Pages;

use App\Core\Administration\Filament\Resources\Tenants\TenantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTenants extends ListRecords
{
    protected static string $resource = TenantResource::class;

    protected static ?string $title = 'Tenants';

    protected ?string $subheading = 'Manage Atlas customer accounts and their default operational company profiles.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('New tenant'),
        ];
    }
}
