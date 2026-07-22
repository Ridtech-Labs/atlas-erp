<x-filament-widgets::widget>
    <section class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
        <div class="grid gap-6 px-6 py-6 lg:grid-cols-[1.4fr_0.9fr] lg:px-8">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-amber-700">
                    Customer workspace
                </div>

                <h2 class="mt-4 text-2xl font-semibold tracking-tight text-stone-950 sm:text-3xl">
                    {{ $client?->display_name ?? 'Client account' }}
                </h2>

                <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-stone-500">
                    <span>{{ $client?->client_code ?: 'Code pending' }}</span>
                    <span>&middot;</span>
                    <span>{{ $client?->country ?: 'No country set' }}</span>
                    <span class="rounded-full bg-stone-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-stone-700">
                        {{ $client?->status?->label() ?? 'Unknown' }}
                    </span>
                </div>

                <p class="mt-4 max-w-2xl text-sm leading-6 text-stone-600">
                    Keep customer context in one place: account details, key people, operating sites, and the work currently in motion.
                </p>

                <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl bg-stone-50 px-4 py-4">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Jobs</div>
                        <div class="mt-2 text-2xl font-semibold text-stone-950">{{ $stats['jobs'] }}</div>
                    </div>
                    <div class="rounded-2xl bg-stone-50 px-4 py-4">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Contacts</div>
                        <div class="mt-2 text-2xl font-semibold text-stone-950">{{ $stats['contacts'] }}</div>
                    </div>
                    <div class="rounded-2xl bg-stone-50 px-4 py-4">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Sites</div>
                        <div class="mt-2 text-2xl font-semibold text-stone-950">{{ $stats['sites'] }}</div>
                    </div>
                    <div class="rounded-2xl bg-amber-50 px-4 py-4">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-amber-700">Open work</div>
                        <div class="mt-2 text-2xl font-semibold text-stone-950">{{ $stats['open_work'] }}</div>
                    </div>
                </div>
            </div>

            <div class="grid gap-4">
                <div class="rounded-2xl border border-stone-200 bg-stone-50 p-5">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Latest activity</div>
                    <div class="mt-2 text-lg font-semibold text-stone-950">
                        {{ $client?->updated_at?->diffForHumans() ?? 'No activity yet' }}
                    </div>
                    <p class="mt-2 text-sm text-stone-600">
                        Recent changes to this account will flow into the activity timeline and operational views.
                    </p>
                </div>

                <div class="rounded-2xl border border-dashed border-stone-300 bg-white p-5">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Coming soon</div>
                    <div class="mt-2 text-lg font-semibold text-stone-950">Invoices and documents</div>
                    <p class="mt-2 text-sm text-stone-600">
                        This workspace is designed to expand naturally into billing, files, and commercial history.
                    </p>
                </div>
            </div>
        </div>
    </section>
</x-filament-widgets::widget>
