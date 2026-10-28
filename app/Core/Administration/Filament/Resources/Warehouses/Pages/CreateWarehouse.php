<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Warehouses\Pages;

use App\Core\Administration\Filament\Resources\Warehouses\WarehouseResource;
use App\Inventory\Actions\CreateWarehouseAction;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateWarehouse extends CreateRecord
{
    protected static string $resource = WarehouseResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateWarehouseAction::class)->execute($data, $this->authenticatedUser());
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
