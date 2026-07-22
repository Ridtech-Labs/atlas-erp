@php
    use App\Core\Administration\Filament\Resources\Activities\ActivityResource;
@endphp

@if (ActivityResource::canViewAny())
    <a
        href="{{ ActivityResource::getUrl('index') }}"
        aria-label="Open activity log"
        class="fi-icon-btn relative inline-flex h-10 w-10 items-center justify-center rounded-[var(--atlas-radius-sm)] border border-[var(--atlas-color-border-default)] bg-white text-[var(--atlas-color-text-secondary)] transition hover:bg-[var(--atlas-color-background-muted)] hover:text-[var(--atlas-color-text-primary)]"
    >
        <svg class="h-4 w-4" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
        </svg>
        <span class="sr-only">Activity log</span>
    </a>
@endif
