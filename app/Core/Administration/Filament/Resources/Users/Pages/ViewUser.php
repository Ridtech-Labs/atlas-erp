<?php

namespace App\Core\Administration\Filament\Resources\Users\Pages;

use App\Core\Administration\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        $record = $this->getRecord();

        return $record instanceof User ? $record->full_name : 'User';
    }

    public function getSubheading(): ?string
    {
        return 'User identity, company membership, security status, and role access.';
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Edit user'),
        ];
    }
}
