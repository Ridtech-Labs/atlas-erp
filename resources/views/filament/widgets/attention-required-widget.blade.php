<x-filament-widgets::widget>
    <x-atlas-card class="p-6">
        <x-atlas.section-header
            title="Attention Required"
            description="Items that need a decision, schedule update, or profile completion next."
        />

        <div class="mt-6 space-y-3">
            @if (! $canViewJobs && ! $canViewClients)
                <x-atlas.empty-state
                    title="Operational alerts are restricted"
                    description="This role does not currently include access to job or client exceptions."
                />
            @elseif ($items === [])
                <x-atlas.empty-state
                    title="Nothing urgent right now"
                    description="Overdue jobs, pending approvals, and incomplete client records will surface here when they need attention."
                />
            @else
                @foreach ($items as $item)
                    <a
                        href="{{ $item['url'] }}"
                        class="block rounded-[var(--atlas-radius-lg)] border border-[var(--atlas-color-border-default)] bg-[var(--atlas-color-background-surface)] px-4 py-4 transition hover:border-[var(--atlas-color-border-strong)] hover:bg-[var(--atlas-color-background-muted)]"
                        aria-label="{{ $item['action_label'] }}"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <x-atlas.badge :status="$item['status']">{{ $item['action_label'] }}</x-atlas.badge>
                                    <span class="truncate text-xs text-[var(--atlas-color-text-muted)]">{{ $item['meta'] }}</span>
                                </div>

                                <p class="mt-2 text-sm font-semibold text-[var(--atlas-color-text-primary)]">{{ $item['title'] }}</p>
                                <p class="mt-1 text-sm leading-6 text-[var(--atlas-color-text-secondary)]">{{ $item['description'] }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </x-atlas-card>
</x-filament-widgets::widget>
