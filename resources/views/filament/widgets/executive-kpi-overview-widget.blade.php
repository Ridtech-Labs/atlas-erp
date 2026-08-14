@php
    $iconStyles = [
        'clients' => ['bg' => 'bg-[#e9edff]', 'text' => 'text-[#4f67ff]'],
        'operations' => ['bg' => 'bg-[#dcfce7]', 'text' => 'text-[#16a34a]'],
        'approval' => ['bg' => 'bg-[#fef3c7]', 'text' => 'text-[#d97706]'],
        'calendar' => ['bg' => 'bg-[#dff2ff]', 'text' => 'text-[#0f8bd7]'],
        'check' => ['bg' => 'bg-[#f3e8ff]', 'text' => 'text-[#9333ea]'],
        'revenue' => ['bg' => 'bg-[#dcfce7]', 'text' => 'text-[#16a34a]'],
        'alert' => ['bg' => 'bg-[#fee2e2]', 'text' => 'text-[#dc2626]'],
    ];
@endphp

<x-filament-widgets::widget>
    <div class="grid gap-4 xl:grid-cols-6">
        @foreach ($kpis as $kpi)
            @php
                $style = $iconStyles[$kpi['icon']] ?? $iconStyles['alert'];
            @endphp

            <x-atlas-card class="atlas-kpi-card overflow-hidden px-5 py-5">
                <div class="flex items-start justify-between gap-4">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-[16px] {{ $style['bg'] }} {{ $style['text'] }}">
                        @switch($kpi['icon'])
                            @case('clients')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><path d="M20 8v6"></path><path d="M23 11h-6"></path></svg>
                                @break
                            @case('operations')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="13" rx="2"></rect><path d="M16 7V5a4 4 0 0 0-8 0v2"></path></svg>
                                @break
                            @case('approval')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                                @break
                            @case('calendar')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4"></path><path d="M8 2v4"></path><path d="M3 10h18"></path></svg>
                                @break
                            @case('check')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"></path><path d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z"></path></svg>
                                @break
                            @case('revenue')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22"></path><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14.5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                @break
                        @endswitch
                    </span>

                    @if (filled($kpi['trend'] ?? null))
                        <span class="text-sm font-semibold text-[var(--atlas-color-status-success)]">{{ $kpi['trend'] }}</span>
                    @endif
                </div>

                <div class="mt-6 text-[2.35rem] font-bold tracking-[-0.045em] text-[var(--atlas-color-text-primary)]">
                    {{ $kpi['value'] }}
                </div>
                <div class="mt-2 text-[1.2rem] font-medium tracking-[-0.02em] text-[var(--atlas-color-text-secondary)]">
                    {{ $kpi['label'] }}
                </div>
                <div class="mt-1 text-sm text-[var(--atlas-color-text-muted)]">
                    {{ $kpi['description'] }}
                </div>
            </x-atlas-card>
        @endforeach
    </div>
</x-filament-widgets::widget>
