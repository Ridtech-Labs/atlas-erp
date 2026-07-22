@php
    $tenant = $currentTenant ?? auth()->user()?->tenant;
@endphp

@if ($tenant)
    <div class="hidden lg:flex items-center gap-2">
        <span class="atlas-page-chip border-none bg-[var(--atlas-color-background-muted)] text-[var(--atlas-color-text-secondary)]">
            <span class="inline-block h-2 w-2 rounded-full bg-[var(--atlas-color-status-success)]"></span>
            {{ $tenant->name }}
        </span>
    </div>
@endif
