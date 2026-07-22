<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Quick actions</x-slot>

        <div class="grid gap-4">
            @foreach ($actionCards as $card)
                <a href="{{ $card['url'] }}" class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-amber-300 hover:bg-amber-50/50">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-amber-700">Action</div>
                    <div class="mt-3 text-lg font-semibold text-stone-900">{{ $card['title'] }}</div>
                    <div class="mt-2 text-sm text-stone-600">{{ $card['description'] }}</div>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
