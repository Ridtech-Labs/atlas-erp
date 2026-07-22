<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Clients\Pages;

use App\Core\Administration\Filament\Resources\Clients\ClientResource;
use App\CRM\Actions\Clients\CreateClientAction;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected static ?string $title = 'Create Client';

    protected ?string $subheading = 'Open a new customer workspace with the commercial and operational details your team needs.';

    protected function handleRecordCreation(array $data): Model
    {
        if (! isset($data['tenant_id']) || blank($data['tenant_id'])) {
            $data['tenant_id'] = $this->authenticatedUser()->tenant_id;
        }

        return app(CreateClientAction::class)->execute($data, $this->authenticatedUser());
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
