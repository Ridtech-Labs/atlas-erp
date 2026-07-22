<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">System Alerts</x-slot>

        <div class="space-y-3">
            @foreach ($alerts as $alert)
                <div class="rounded-3xl border border-stone-200 bg-white px-4 py-4 shadow-sm">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">{{ $alert['label'] }}</div>
                    <div class="mt-2 text-2xl font-semibold text-stone-950">{{ $alert['value'] }}</div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
