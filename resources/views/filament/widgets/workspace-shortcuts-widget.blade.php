<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Workspace Shortcuts</x-slot>

        <div class="grid gap-3">
            @foreach ($shortcuts as $shortcut)
                <a href="{{ $shortcut['url'] }}" class="rounded-3xl border border-stone-200 bg-white px-4 py-4 text-sm font-semibold text-stone-900 shadow-sm transition hover:border-amber-300 hover:bg-amber-50/60">
                    {{ $shortcut['label'] }}
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
