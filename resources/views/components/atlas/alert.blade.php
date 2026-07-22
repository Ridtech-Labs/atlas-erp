@props([
    'status' => 'info',
    'title' => null,
])

@php
    $toneClasses = match ($status) {
        'success' => 'border-[var(--atlas-color-status-success)]/20 bg-[var(--atlas-color-status-success-soft)] text-[var(--atlas-color-status-success)]',
        'warning' => 'border-[var(--atlas-color-status-warning)]/20 bg-[var(--atlas-color-status-warning-soft)] text-[var(--atlas-color-status-warning)]',
        'danger' => 'border-[var(--atlas-color-status-danger)]/20 bg-[var(--atlas-color-status-danger-soft)] text-[var(--atlas-color-status-danger)]',
        default => 'border-[var(--atlas-color-status-info)]/20 bg-[var(--atlas-color-status-info-soft)] text-[var(--atlas-color-status-info)]',
    };
@endphp

<div {{ $attributes->class(['rounded-[var(--atlas-radius-lg)] border px-4 py-3 text-sm', $toneClasses]) }}>
    @if (filled($title))
        <p class="font-semibold">{{ $title }}</p>
    @endif

    <div @class(['mt-1' => filled($title)])>
        {{ $slot }}
    </div>
</div>
