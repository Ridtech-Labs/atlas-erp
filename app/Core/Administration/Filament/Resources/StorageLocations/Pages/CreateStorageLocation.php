<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\StorageLocations\Pages;

use App\Core\Administration\Filament\Resources\StorageLocations\StorageLocationResource;
use App\Inventory\Actions\CreateStorageLocationAction;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateStorageLocation extends CreateRecord
{
    protected static string $resource = StorageLocationResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateStorageLocationAction::class)->execute($data, $this->authenticatedUser());
    }

    private function authenticatedUser(): User
    {
        $user = auth()->user();
        if (! $user instanceof User) {
            abort(403);
        }

return $user;
    }
}
