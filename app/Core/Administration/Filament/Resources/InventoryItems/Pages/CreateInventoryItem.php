<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\InventoryItems\Pages;

use App\Core\Administration\Filament\Resources\InventoryItems\InventoryItemResource;
use App\Inventory\Actions\CreateInventoryItemAction;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateInventoryItem extends CreateRecord
{
    protected static string $resource = InventoryItemResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateInventoryItemAction::class)->execute($data, $this->authenticatedUser());
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
