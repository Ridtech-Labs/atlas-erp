<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\FleetAssetTypes\Pages;

use App\Core\Administration\Filament\Resources\FleetAssetTypes\FleetAssetTypeResource;
use App\Fleet\Actions\CreateFleetAssetTypeAction;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateFleetAssetType extends CreateRecord
{
    protected static string $resource = FleetAssetTypeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateFleetAssetTypeAction::class)->execute($data, $this->authenticatedUser());
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
