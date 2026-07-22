@php
    $tenant = $currentTenant ?? auth()->user()?->tenant;
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
        <div class="truncate text-xs text-[var(--atlas-color-text-sidebar)]">{{ $tenant?->name ?? 'Platform workspace' }}</div>
    </div>
</div>
