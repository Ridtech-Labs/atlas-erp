<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Roles\Pages;

use App\Core\Administration\Filament\Resources\Roles\RoleResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);

        $role = Role::query()->create($data);
        $role->syncPermissions($permissions);

        return $role;
    }
}
