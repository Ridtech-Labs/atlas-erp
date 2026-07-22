<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Pipeline</x-slot>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            @foreach ($stages as $stage)
                <div class="rounded-3xl border border-stone-200 bg-white px-5 py-5 shadow-sm">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">{{ $stage['label'] }}</div>
                    <div class="mt-3 text-3xl font-semibold tracking-tight text-stone-950">{{ $stage['count'] }}</div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
