@props([
    'variant' => 'primary',
    'size' => 'md',
    'tag' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 rounded-[var(--atlas-radius-sm)] border text-sm font-semibold transition focus:outline-none disabled:cursor-not-allowed disabled:opacity-60';

    $sizeClasses = match ($size) {
        'sm' => 'min-h-9 px-3 py-2 text-xs',
        'lg' => 'min-h-12 px-5 py-3 text-sm',
        default => 'min-h-10 px-4 py-2.5 text-sm',
    };

    $variantClasses = match ($variant) {
        'secondary' => 'border-[var(--atlas-color-border-default)] bg-white text-[var(--atlas-color-text-primary)] hover:border-[var(--atlas-color-border-strong)] hover:bg-[var(--atlas-color-background-muted)]',
        'ghost' => 'border-transparent bg-transparent text-[var(--atlas-color-text-secondary)] hover:bg-[var(--atlas-color-background-muted)] hover:text-[var(--atlas-color-text-primary)]',
        'danger' => 'border-[var(--atlas-color-status-danger)] bg-[var(--atlas-color-status-danger)] text-white hover:border-[#b91c1c] hover:bg-[#b91c1c]',
        default => 'border-[var(--atlas-color-action-primary)] bg-[var(--atlas-color-action-primary)] text-white hover:border-[var(--atlas-color-action-primary-hover)] hover:bg-[var(--atlas-color-action-primary-hover)]',
    };
@endphp

<{{ $tag }} {{ $attributes->class([$baseClasses, $sizeClasses, $variantClasses]) }}>
    {{ $slot }}
</{{ $tag }}>
