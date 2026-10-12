<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\InventoryItems\Pages;

use App\Core\Administration\Filament\Resources\InventoryItems\InventoryItemResource;
use App\Inventory\Actions\UpdateInventoryItemAction;
use App\Inventory\Models\InventoryItem;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditInventoryItem extends EditRecord
{
    protected static string $resource = InventoryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make(), DeleteAction::make()->requiresConfirmation()];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof InventoryItem) {
            throw new \RuntimeException('Expected Inventory item.');
        }

        return app(UpdateInventoryItemAction::class)->execute($record, $data, $this->authenticatedUser());
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
