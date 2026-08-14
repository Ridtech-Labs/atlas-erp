<?php

declare(strict_types=1);

namespace App\Core\Administration\Filament\Resources\Tenants\Pages;

use App\Administration\Actions\Companies\UpdateCompanyAction;
use App\Core\Administration\Filament\Resources\Tenants\TenantResource;
use App\Core\Tenancy\Models\Tenant;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTenant extends EditRecord
{
    protected static string $resource = TenantResource::class;

    public function getSubheading(): ?string
    {
        return 'Maintain tenant account metadata and the default operational company profile for this customer.';
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->label('View tenant'),
            DeleteAction::make()->requiresConfirmation(),
            ForceDeleteAction::make()->requiresConfirmation(),
            RestoreAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $tenant = $this->getRecord();

        if (! $tenant instanceof Tenant) {
            return $data;
        }

        $company = $tenant->defaultCompany;

        return [
            ...$data,
            'email' => $company?->email,
            'phone' => $company?->phone,
            'logo_path' => $company?->logo_path,
            'address' => $company?->address,
            'city' => $company?->city,
            'country' => $company?->country,
            'timezone' => $company?->timezone,
            'currency' => $company?->currency,
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof Tenant) {
            throw new \RuntimeException('Expected tenant record.');
        }

        return app(UpdateCompanyAction::class)->execute($record, $data, $this->authenticatedUser());
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
