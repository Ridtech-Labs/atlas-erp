<?php

namespace App\Core\Administration\Filament\Resources\Tenants\Pages;

use App\Core\Administration\Filament\Pages\Dashboard;
use App\Core\Administration\Filament\Resources\Tenants\TenantResource;
use App\Core\Tenancy\Models\Tenant;
use App\Core\Tenancy\Services\SupportTenantWorkspaceService;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTenant extends ViewRecord
{
    protected static string $resource = TenantResource::class;

    public function getTitle(): string
    {
        $record = $this->getRecord();

        return $record instanceof Tenant ? $record->name : 'Tenant';
    }

    public function getSubheading(): ?string
    {
        return 'Tenant account metadata and default company profile for this Atlas customer.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('enterWorkspace')
                ->label('Enter Workspace')
                ->icon('heroicon-o-arrow-right-start-on-rectangle')
                ->visible(fn (): bool => $this->canEnterWorkspace())
                ->action(function (): mixed {
                    $record = $this->getRecord();

                    if (! $record instanceof Tenant) {
                        abort(404);
                    }

                    app(SupportTenantWorkspaceService::class)->enter($this->authenticatedUser(), $record);

                    return redirect(Dashboard::getUrl());
                }),
            EditAction::make()->label('Edit tenant'),
        ];
    }

    private function canEnterWorkspace(): bool
    {
        $record = $this->getRecord();

        return $record instanceof Tenant
            && app(SupportTenantWorkspaceService::class)->canEnter($this->authenticatedUser(), $record);
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
