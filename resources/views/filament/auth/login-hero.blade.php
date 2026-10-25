@php
    $branding = app(\App\Core\Tenancy\Services\TenantBrandingService::class)->forLoginRequest(request());
    $hero = $branding?->loginHeroUrl ?? asset('images/auth/atlas-hero.png');
@endphp

<aside class="atlas-login-hero" aria-label="{{ $branding?->displayName ?? 'Atlas ERP' }} overview" @if ($branding) data-tenant-branding @endif>
    <img
        class="atlas-login-hero-image"
        src="{{ $hero }}"
        alt=""
    >

    <div class="atlas-login-hero-copy">
        @if ($branding?->logoUrl)
            <img class="atlas-login-hero-logo" src="{{ $branding->logoUrl }}" alt="{{ $branding->displayName }} logo">
        @endif
        @if (! $branding?->logoUrl)
            <p class="atlas-login-hero-kicker">{{ $branding?->displayName ?? 'ATLAS ERP' }}</p>
        @endif
        <h1 class="atlas-login-hero-heading">Run your operations<br>from one place.</h1>
        <p class="atlas-login-hero-description">
            Jobs, fleet, personnel and commercial workflows connected in one workspace.
        </p>
        @if ($branding)
            <p class="atlas-login-hero-attribution">Powered by Atlas ERP</p>
        @endif
    </div>

</aside>
