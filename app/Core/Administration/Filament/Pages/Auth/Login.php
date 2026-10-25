<?php

namespace App\Core\Administration\Filament\Pages\Auth;

use App\Core\Tenancy\Services\TenantBrandingService;
use App\Core\Tenancy\Support\TenantBranding;
use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    public function getHeading(): string|Htmlable|null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return parent::getHeading();
        }

        $branding = $this->branding();

        return $branding instanceof TenantBranding
            ? 'Welcome to '.$branding->displayName
            : 'Welcome back';
    }

    public function getSubheading(): string|Htmlable|null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return parent::getSubheading();
        }

        return 'Sign in to continue to your workspace.';
    }

    private function branding(): ?TenantBranding
    {
        return app(TenantBrandingService::class)->forLoginRequest(request());
    }
}
