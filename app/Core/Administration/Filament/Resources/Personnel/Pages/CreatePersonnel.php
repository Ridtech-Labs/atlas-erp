<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Personnel\Pages;

use App\Administration\Services\AdministrationAccessService;
use App\Core\Administration\Filament\Resources\Personnel\PersonnelResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreatePersonnel extends CreateRecord
{
    protected static string $resource = PersonnelResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        $company = $user ? app(AdministrationAccessService::class)->activeCompany($user) : null;
        abort_unless($user && $company, 403);

        return [...$data, 'uuid' => (string) Str::uuid(), 'tenant_id' => $company->tenant_id, 'company_id' => $company->id, 'created_by' => $user->id, 'updated_by' => $user->id];
    }
}
