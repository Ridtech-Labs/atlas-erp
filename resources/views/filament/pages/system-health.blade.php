<x-filament-panels::page>
    <section class="atlas-card p-6">
        <div class="max-w-3xl">
            <h2 class="text-lg font-semibold text-[var(--atlas-color-text-primary)]">Operational health overview</h2>
            <p class="mt-2 text-sm leading-6 text-[var(--atlas-color-text-secondary)]">
                This page holds the underlying technical checks that support the business dashboard. Use it when investigating delivery issues, environment drift, or infrastructure readiness.
            </p>
        </div>
    </section>

    <div class="grid gap-4">
        @foreach ($this->checks() as $check)
            <x-filament::section>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-[var(--atlas-color-text-primary)]">{{ $check['label'] }}</h3>
                        <p class="mt-1 text-sm text-[var(--atlas-color-text-secondary)]">{{ $check['detail'] }}</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $check['status'] === 'ok' ? 'bg-[var(--atlas-color-status-success-soft)] text-[var(--atlas-color-status-success)]' : ($check['status'] === 'warning' ? 'bg-[var(--atlas-color-status-warning-soft)] text-[var(--atlas-color-status-warning)]' : 'bg-[var(--atlas-color-status-danger-soft)] text-[var(--atlas-color-status-danger)]') }}">
                        {{ strtoupper($check['status']) }}
                    </span>
                </div>
            </x-filament::section>
        @endforeach
    </div>
</x-filament-panels::page>
