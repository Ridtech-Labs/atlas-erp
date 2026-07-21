<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Tenants\Pages;

use App\Administration\Actions\Companies\CreateCompanyAction;
use App\Core\Administration\Filament\Resources\Tenants\TenantResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateCompanyAction::class)->execute($data, auth()->user());
    }
}
