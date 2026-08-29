<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\FleetAssetTypes\Pages;

use App\Core\Administration\Filament\Resources\FleetAssetTypes\FleetAssetTypeResource;
use App\Fleet\Actions\UpdateFleetAssetTypeAction;
use App\Fleet\Models\FleetAssetType;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditFleetAssetType extends EditRecord
{
    protected static string $resource = FleetAssetTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make(), DeleteAction::make()->requiresConfirmation()];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof FleetAssetType) {
            throw new \RuntimeException('Expected Fleet asset type.');
        }

        return app(UpdateFleetAssetTypeAction::class)->execute($record, $data, $this->authenticatedUser());
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
