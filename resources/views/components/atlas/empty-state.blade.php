@props([
    'title',
    'description' => null,
    'icon' => null,
])

<div {{ $attributes->class(['atlas-empty-state']) }}>
    @if (filled($icon))
        <div class="inline-flex h-11 w-11 items-center justify-center rounded-[var(--atlas-radius-lg)] bg-[var(--atlas-color-action-primary-soft)] text-[var(--atlas-color-action-primary)]">
            {{ $icon }}
        </div>
    @endif

    <div>
        <h3 class="text-sm font-semibold text-[var(--atlas-color-text-primary)]">{{ $title }}</h3>

        @if (filled($description))
            <p class="mt-1 text-sm leading-6 text-[var(--atlas-color-text-secondary)]">{{ $description }}</p>
        @endif
    </div>

    @if (trim($slot) !== '')
        <div class="pt-1">
            {{ $slot }}
        </div>
    @endif
</div>
