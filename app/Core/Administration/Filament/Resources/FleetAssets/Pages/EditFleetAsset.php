<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\FleetAssets\Pages;

use App\Core\Administration\Filament\Resources\FleetAssets\FleetAssetResource;
use App\Fleet\Actions\UpdateFleetAssetAction;
use App\Fleet\Models\FleetAsset;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditFleetAsset extends EditRecord
{
    protected static string $resource = FleetAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make(), DeleteAction::make()->requiresConfirmation()];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof FleetAsset) {
            throw new \RuntimeException('Expected Fleet asset.');
        }

        return app(UpdateFleetAssetAction::class)->execute($record, $data, $this->authenticatedUser());
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
