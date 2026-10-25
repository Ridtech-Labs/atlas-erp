<?php

declare(strict_types=1);

namespace App\Core\Tenancy\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class TenantAwareLogoutResponse implements LogoutResponseContract
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $slug = $request->attributes->get('atlas.logout_tenant_slug');

        if (is_string($slug)) {
            return redirect()->route('atlas.tenant-login', ['tenant' => $slug]);
        }

        $loginUrl = Filament::hasLogin() ? Filament::getLoginUrl() : null;

        return redirect()->to(is_string($loginUrl) ? $loginUrl : (Filament::getUrl() ?? '/'));
    }
}
