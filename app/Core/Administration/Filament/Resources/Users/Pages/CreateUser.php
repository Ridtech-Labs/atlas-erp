<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Users\Pages;

use App\Administration\Actions\Users\CreateUserAction;
use App\Core\Administration\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $roleNames = $data['roles'] ?? [];
        unset($data['roles'], $data['password_confirmation']);

        if (! isset($data['tenant_id']) || blank($data['tenant_id'])) {
            $data['tenant_id'] = auth()->user()?->tenant_id;
        }

        return app(CreateUserAction::class)->execute($data, $roleNames, auth()->user());
    }
}
