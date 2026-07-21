<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Tenants\Pages;

use App\Administration\Actions\Companies\UpdateCompanyAction;
use App\Core\Administration\Filament\Resources\Tenants\TenantResource;
use App\Core\Tenancy\Models\Tenant;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTenant extends EditRecord
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()->requiresConfirmation(),
            ForceDeleteAction::make()->requiresConfirmation(),
            RestoreAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof Tenant) {
            throw new \RuntimeException('Expected tenant record.');
        }

        return app(UpdateCompanyAction::class)->execute($record, $data, auth()->user());
    }
}
