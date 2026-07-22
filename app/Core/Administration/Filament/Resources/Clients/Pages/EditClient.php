<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Clients\Pages;

use App\Core\Administration\Filament\Resources\Clients\ClientResource;
use App\CRM\Actions\Clients\UpdateClientAction;
use App\CRM\Models\Client;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    public function getSubheading(): ?string
    {
        return 'Keep this customer workspace accurate across billing, operations, and account management.';
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->label('View account'),
            DeleteAction::make()->requiresConfirmation(),
            RestoreAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof Client) {
            throw new \RuntimeException('Expected client record.');
        }

        return app(UpdateClientAction::class)->execute($record, $data, $this->authenticatedUser());
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
