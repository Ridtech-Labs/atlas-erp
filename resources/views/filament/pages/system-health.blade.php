<x-filament-panels::page>
    <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
        <div class="max-w-3xl">
            <h2 class="text-lg font-semibold text-stone-900">Operational health overview</h2>
            <p class="mt-2 text-sm leading-6 text-stone-600">
                This page holds the underlying technical checks that support the business dashboard. Use it when investigating delivery issues, environment drift, or infrastructure readiness.
            </p>
        </div>
    </section>

    <div class="grid gap-4">
        @foreach ($this->checks() as $check)
            <x-filament::section>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-stone-900">{{ $check['label'] }}</h3>
                        <p class="mt-1 text-sm text-stone-600">{{ $check['detail'] }}</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $check['status'] === 'ok' ? 'bg-green-100 text-green-700' : ($check['status'] === 'warning' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                        {{ strtoupper($check['status']) }}
                    </span>
                </div>
            </x-filament::section>
        @endforeach
    </div>
</x-filament-panels::page>
