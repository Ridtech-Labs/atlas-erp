<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Roles\Pages;

use App\Core\Administration\Filament\Resources\Roles\RoleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()->requiresConfirmation(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record instanceof Role) {
            $data['permissions'] = $this->record->permissions()->pluck('name')->all();
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof Role) {
            throw new \RuntimeException('Expected role record.');
        }

        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);

        $record->fill($data);
        $record->save();
        $record->syncPermissions($permissions);

        return $record;
    }
}
