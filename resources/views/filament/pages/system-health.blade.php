<x-filament-panels::page>
    <div class="grid gap-4">
        @foreach ($this->checks() as $check)
            <x-filament::section>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold">{{ $check['label'] }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $check['detail'] }}</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-medium {{ $check['status'] === 'ok' ? 'bg-green-100 text-green-700' : ($check['status'] === 'warning' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                        {{ strtoupper($check['status']) }}
                    </span>
                </div>
            </x-filament::section>
        @endforeach
    </div>
</x-filament-panels::page>
