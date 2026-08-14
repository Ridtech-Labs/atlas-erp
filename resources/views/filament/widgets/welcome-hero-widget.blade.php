<x-filament-widgets::widget>
    <section class="px-1 pt-2">
        <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-start">
            <div class="min-w-0">
                <h2 class="text-[2.35rem] font-bold tracking-[-0.045em] text-[var(--atlas-color-text-primary)] lg:text-[2.75rem]">
                    {{ $greeting }}, {{ $userName }}
                </h2>

                <div class="mt-2 text-[1.15rem] text-[var(--atlas-color-text-muted)] lg:text-[1.25rem]">
                    {{ $contextLabel }} · {{ $todayLabel }}
                </div>

                <div class="mt-5 flex flex-wrap items-center gap-3 text-[1.15rem] text-[var(--atlas-color-text-muted)] lg:text-[1.2rem]">
                    <span @class([
                        'inline-flex h-3 w-3 rounded-full',
                        'bg-[var(--atlas-color-status-success)]' => $operationalStatus['tone'] === 'success',
                        'bg-[var(--atlas-color-status-warning)]' => $operationalStatus['tone'] === 'warning',
                        'bg-[var(--atlas-color-status-info)]' => $operationalStatus['tone'] === 'info',
                    ])></span>
                    <span>{{ $operationalStatus['text'] }} · {{ $operationalStatus['detail'] }}</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 lg:justify-self-end">
                <x-atlas.button
                    tag="button"
                    variant="secondary"
                    size="lg"
                    disabled
                    class="min-w-[10.5rem]"
                >
                    Export Report
                </x-atlas.button>

                @if ($primaryAction)
                    <x-atlas.button
                        tag="a"
                        :href="$primaryAction['url']"
                        aria-label="Quick Create"
                        size="lg"
                        class="min-w-[10.5rem]"
                    >
                        + Quick Create
                    </x-atlas.button>
                @endif
            </div>
        </div>
    </section>
</x-filament-widgets::widget>
