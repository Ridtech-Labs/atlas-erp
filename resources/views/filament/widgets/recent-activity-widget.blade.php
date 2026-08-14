<x-filament-widgets::widget>
    <x-atlas-card class="overflow-hidden">
        <div class="flex items-center justify-between gap-4 border-b border-[var(--atlas-color-border-muted)] px-6 py-4">
            <h3 class="text-[1.8rem] font-bold tracking-[-0.03em] text-[var(--atlas-color-text-primary)]">Recent Activity</h3>
            <span class="text-base font-semibold text-[var(--atlas-color-text-muted)]">See all →</span>
        </div>

        <div>
            @if ($restricted)
                <div class="p-6">
                    <x-atlas.empty-state
                        title="Activity visibility is restricted"
                        description="This role does not currently include access to the activity timeline."
                    />
                </div>
            @elseif ($activities->isEmpty())
                <div class="p-6">
                    <x-atlas.empty-state
                        title="No activity yet"
                        description="Client, job, and settings changes will appear here as the workspace becomes active."
                    />
                </div>
            @else
                <div class="divide-y divide-[var(--atlas-color-border-muted)]">
                    @foreach ($activities as $activity)
                        @php
                            $rowTag = filled($activity['url']) ? 'a' : 'div';
                            $icon = match ($activity['status']) {
                                'success' => '✓',
                                'danger' => '⚠',
                                'info' => '📄',
                                default => '📦',
                            };
                        @endphp

                        <{{ $rowTag }}
                            @if (filled($activity['url']))
                                href="{{ $activity['url'] }}"
                            @endif
                            class="flex items-center gap-4 px-6 py-5 transition hover:bg-[var(--atlas-color-background-muted)]/45"
                        >
                            <span class="text-xl">{{ $icon }}</span>
                            <div class="min-w-0 flex-1 text-[1.2rem] tracking-[-0.02em] text-[var(--atlas-color-text-secondary)]">
                                {{ $activity['description'] }}
                                @if (filled($activity['actor']) && $activity['actor'] !== 'System')
                                    <span class="text-[var(--atlas-color-text-primary)]">by {{ $activity['actor'] }}</span>
                                @endif
                            </div>
                            <div class="shrink-0 text-base text-[var(--atlas-color-text-disabled)]">
                                {{ $activity['timestamp'] }}
                            </div>
                        </{{ $rowTag }}>
                    @endforeach
                </div>
            @endif
        </div>
    </x-atlas-card>
</x-filament-widgets::widget>
