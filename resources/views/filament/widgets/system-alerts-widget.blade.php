<x-filament-widgets::widget>
    <x-atlas-card class="p-6">
        <h3 class="text-[1.8rem] font-bold tracking-[-0.03em] text-[var(--atlas-color-text-primary)]">System Alerts</h3>

        <div class="mt-5 space-y-4">
            @forelse ($alerts as $alert)
                <a
                    href="{{ $alert['url'] }}"
                    @class([
                        'block rounded-[16px] border px-4 py-4 transition hover:opacity-95',
                        'border-[#fca5a5] bg-[#feecec]' => $alert['tone'] === 'danger',
                        'border-[#fcd34d] bg-[#fff7d8]' => $alert['tone'] === 'warning',
                        'border-[#7dd3fc] bg-[#e8f5ff]' => $alert['tone'] === 'info',
                    ])
                >
                    <div class="text-[1.15rem] font-semibold tracking-[-0.02em] text-[var(--atlas-color-text-primary)]">
                        {{ $alert['title'] }}
                    </div>
                    <div class="mt-1 text-sm text-[var(--atlas-color-text-muted)]">
                        {{ $alert['detail'] }}
                    </div>
                </a>
            @empty
                <x-atlas.empty-state
                    title="No system alerts"
                    description="Operational exceptions will appear here when the workspace needs attention."
                />
            @endforelse
        </div>
    </x-atlas-card>
</x-filament-widgets::widget>
