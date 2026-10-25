@php
    $brandingService = app(\App\Core\Tenancy\Services\TenantBrandingService::class);
    $branding = auth()->check()
        ? $brandingService->forActiveUser(auth()->user())
        : $brandingService->forLoginRequest(request());
@endphp

@if ($branding)
    <style>
        :root {
            @foreach ($branding->cssVariables as $name => $value)
                {{ $name }}: {{ $value }};
            @endforeach
        }
    </style>
@endif
