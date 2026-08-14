@props([
    'title',
    'description' => null,
    'actionLabel' => null,
    'actionUrl' => null,
])

<div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
    <div class="min-w-0">
        <h2 class="text-lg font-semibold tracking-tight text-[var(--atlas-color-text-primary)]">{{ $title }}</h2>

        @if (filled($description))
            <p class="mt-1 text-sm leading-6 text-[var(--atlas-color-text-secondary)]">{{ $description }}</p>
        @endif
    </div>

    @if (filled($actionLabel) && filled($actionUrl))
        <x-atlas.button
            tag="a"
            variant="secondary"
            size="sm"
            :href="$actionUrl"
            :aria-label="$actionLabel"
            class="shrink-0"
        >
            {{ $actionLabel }}
        </x-atlas.button>
    @endif
</div>
