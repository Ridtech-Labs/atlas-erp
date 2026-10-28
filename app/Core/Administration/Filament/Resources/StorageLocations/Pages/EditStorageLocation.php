<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\StorageLocations\Pages;

use App\Core\Administration\Filament\Resources\StorageLocations\StorageLocationResource;
use App\Inventory\Actions\UpdateStorageLocationAction;
use App\Inventory\Models\StorageLocation;
use App\Models\User;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditStorageLocation extends EditRecord
{
    protected static string $resource = StorageLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make()];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof StorageLocation) {
            throw new \RuntimeException('Expected Storage Location.');
        }

return app(UpdateStorageLocationAction::class)->execute($record, $data, $this->authenticatedUser());
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
