<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Personnel\Pages;

use App\Core\Administration\Filament\Resources\Personnel\PersonnelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersonnel extends ListRecords
{
    protected static string $resource = PersonnelResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
