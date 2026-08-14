@php
    $access = app(\App\Administration\Services\AdministrationAccessService::class);
    $isPlatformSession = $access->isPlatformSession(auth()->user());
    $tenant = $isPlatformSession ? null : ($currentTenant ?? auth()->user()?->tenant);
    $company = $isPlatformSession ? null : ($currentCompany ?? $access->activeCompany(auth()->user()));
@endphp

<div class="flex items-center gap-3">
    <span class="inline-flex h-10 w-10 items-center justify-center rounded-[10px] bg-[var(--atlas-color-action-primary)] text-white shadow-[var(--atlas-shadow-sm)]">
        <svg class="h-4 w-4" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
            <polygon points="12 2 21 7 21 17 12 22 3 17 3 7 12 2"></polygon>
            <path d="M7.5 12h9"></path>
            <path d="M12 7.5v9"></path>
        </svg>
    </span>

    <div class="min-w-0">
        <div class="truncate text-sm font-bold tracking-tight text-white">Atlas ERP</div>

        @if ($isPlatformSession)
            <div class="truncate text-xs text-[var(--atlas-color-text-sidebar)]">Platform Administration</div>
        @else
            <div class="truncate text-xs text-[var(--atlas-color-text-sidebar)]">{{ $tenant?->name ?? 'Tenant workspace' }}</div>
            <div class="truncate text-[11px] text-[color:rgba(148,163,184,0.82)]">{{ $company?->name ?? 'Select a company' }}</div>
        @endif
    </div>
</div>
