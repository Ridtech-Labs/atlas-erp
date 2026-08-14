<?php

namespace App\Core\Administration\Filament\Resources\Tenants\Pages;

use App\Core\Administration\Filament\Resources\Tenants\TenantResource;
use App\Core\Tenancy\Models\Tenant;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTenant extends ViewRecord
{
    protected static string $resource = TenantResource::class;

    public function getTitle(): string
    {
        $record = $this->getRecord();

        return $record instanceof Tenant ? $record->name : 'Tenant';
    }

    public function getSubheading(): ?string
    {
        return 'Tenant account metadata and default company profile for this Atlas customer.';
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Edit tenant'),
        ];
    }
}
