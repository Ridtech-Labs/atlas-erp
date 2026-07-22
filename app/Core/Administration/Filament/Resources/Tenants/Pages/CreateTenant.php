<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Tenants\Pages;

use App\Administration\Actions\Companies\CreateCompanyAction;
use App\Core\Administration\Filament\Resources\Tenants\TenantResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected static ?string $title = 'Create Company';

    protected ?string $subheading = 'Set up a new Atlas ERP workspace with the right identity, locale, and contact details.';

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateCompanyAction::class)->execute($data, $this->authenticatedUser());
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
