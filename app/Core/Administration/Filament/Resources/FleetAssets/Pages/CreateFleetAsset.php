<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\FleetAssets\Pages;

use App\Core\Administration\Filament\Resources\FleetAssets\FleetAssetResource;
use App\Fleet\Actions\CreateFleetAssetAction;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateFleetAsset extends CreateRecord
{
    protected static string $resource = FleetAssetResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateFleetAssetAction::class)->execute($data, $this->authenticatedUser());
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
