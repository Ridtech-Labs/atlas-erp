<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Users\Pages;

use App\Administration\Actions\Users\UpdateUserAction;
use App\Core\Administration\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()->requiresConfirmation(),
            ForceDeleteAction::make()->requiresConfirmation(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record instanceof User) {
            $data['roles'] = $this->record->getRoleNames()->all();
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof User) {
            throw new \RuntimeException('Expected user record.');
        }

        $roleNames = $data['roles'] ?? [];
        unset($data['roles'], $data['password_confirmation']);

        return app(UpdateUserAction::class)->execute($record, $data, $roleNames, auth()->user());
    }
}
