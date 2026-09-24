<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Personnel\Pages;

use App\Core\Administration\Filament\Resources\Personnel\PersonnelResource;
use Filament\Resources\Pages\EditRecord;

class EditPersonnel extends EditRecord
{
    protected static string $resource = PersonnelResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();

        return $data;
    }
}
