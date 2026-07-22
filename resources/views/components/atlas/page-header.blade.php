@props([
    'title',
    'description' => null,
    'meta' => [],
    'actions' => null,
])

<x-atlas-card class="p-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div class="min-w-0">
            <h1 class="text-2xl font-bold tracking-tight text-[var(--atlas-color-text-primary)] lg:text-[2rem]">{{ $title }}</h1>

            @if (filled($description))
                <p class="mt-2 max-w-3xl text-sm leading-6 text-[var(--atlas-color-text-secondary)]">{{ $description }}</p>
            @endif

            @if ($meta !== [])
                <div class="atlas-page-header-meta">
                    @foreach ($meta as $item)
                        <span class="atlas-page-chip">{{ $item }}</span>
                    @endforeach
                </div>
            @endif
        </div>

        @if (filled($actions))
            <div class="flex flex-wrap items-center gap-3">
                {{ $actions }}
            </div>
        @endif
    </div>
</x-atlas-card>
