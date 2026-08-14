<x-filament-widgets::widget>
    @php
        $iconStyles = [
            'operations' => ['bg' => 'bg-[#eef2ff]', 'text' => 'text-[#4f67ff]', 'glyph' => '📋'],
            'clients' => ['bg' => 'bg-[#dcfce7]', 'text' => 'text-[#16a34a]', 'glyph' => '👥'],
            'crm' => ['bg' => 'bg-[#f3e8ff]', 'text' => 'text-[#9333ea]', 'glyph' => '📊'],
            'calendar' => ['bg' => 'bg-[#fee2e2]', 'text' => 'text-[#dc2626]', 'glyph' => '📅'],
            'settings' => ['bg' => 'bg-[#fef3c7]', 'text' => 'text-[#d97706]', 'glyph' => '⚙️'],
        ];
    @endphp

    <x-atlas-card class="p-6">
        <h3 class="text-[1.8rem] font-bold tracking-[-0.03em] text-[var(--atlas-color-text-primary)]">Quick Actions</h3>

        <div class="mt-5 grid grid-cols-2 gap-3.5">
            @forelse ($actionCards as $card)
                @php
                    $style = $iconStyles[$card['icon'] ?? 'settings'] ?? $iconStyles['settings'];
                @endphp
                <a
                    href="{{ $card['url'] }}"
                    class="rounded-[16px] border border-[var(--atlas-color-border-default)] bg-[var(--atlas-color-background-surface)] px-4 py-4 transition hover:border-[var(--atlas-color-border-strong)] hover:bg-[var(--atlas-color-background-muted)]"
                    aria-label="{{ $card['title'] }}"
                >
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-[12px] {{ $style['bg'] }} text-lg {{ $style['text'] }}">
                        {{ $style['glyph'] }}
                    </span>
                    <p class="mt-3 text-[1.15rem] font-semibold tracking-[-0.02em] text-[var(--atlas-color-text-secondary)]">{{ $card['title'] }}</p>
                </a>
            @empty
                <x-atlas.empty-state
                    title="No dashboard actions available"
                    description="This role does not currently have any create or management actions surfaced from the dashboard."
                />
            @endforelse
        </div>
    </x-atlas-card>
</x-filament-widgets::widget>
