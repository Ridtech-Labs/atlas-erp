<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Warehouses\Pages;

use App\Core\Administration\Filament\Resources\Warehouses\WarehouseResource;
use App\Inventory\Actions\UpdateWarehouseAction;
use App\Inventory\Models\Warehouse;
use App\Models\User;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditWarehouse extends EditRecord
{
    protected static string $resource = WarehouseResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make()];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof Warehouse) {
            throw new \RuntimeException('Expected Warehouse.');
        }

return app(UpdateWarehouseAction::class)->execute($record, $data, $this->authenticatedUser());
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
