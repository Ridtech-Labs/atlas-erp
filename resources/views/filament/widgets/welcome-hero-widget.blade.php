<x-filament-widgets::widget>
    <section class="overflow-hidden rounded-3xl border border-stone-200 bg-gradient-to-br from-stone-950 via-stone-900 to-amber-900/80 text-white shadow-sm">
        <div class="grid gap-8 px-6 py-8 lg:grid-cols-[1.4fr_0.9fr] lg:px-8">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-amber-200">
                    Atlas ERP Workspace
                </div>

                <h2 class="mt-5 text-3xl font-semibold tracking-tight sm:text-4xl">
                    {{ $greeting }} {{ $user?->first_name ?? 'there' }}
                </h2>

                <div class="mt-3 text-sm font-medium text-stone-200 sm:text-base">
                    {{ $tenantName }}
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-3">
                    @foreach ($operationalSummary as $summary)
                        <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-4 backdrop-blur">
                            <div class="text-2xl font-semibold text-white">{{ $summary['value'] }}</div>
                            <div class="mt-1 text-xs font-semibold uppercase tracking-[0.2em] text-amber-200">{{ $summary['label'] }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ \App\Core\Administration\Filament\Resources\Clients\ClientResource::getUrl('create') }}" class="inline-flex items-center rounded-2xl bg-amber-400 px-4 py-2.5 text-sm font-semibold text-stone-950 transition hover:bg-amber-300">
                        Create Client
                    </a>
                    <a href="{{ \App\Core\Administration\Filament\Resources\Jobs\JobResource::getUrl('create') }}" class="inline-flex items-center rounded-2xl border border-white/15 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/15">
                        Create Job
                    </a>
                    <a href="{{ \App\Core\Administration\Filament\Resources\Clients\ClientResource::getUrl('index') }}" class="inline-flex items-center rounded-2xl border border-white/15 bg-transparent px-4 py-2.5 text-sm font-semibold text-stone-100 transition hover:bg-white/10">
                        Review CRM
                    </a>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-amber-200">Today's focus</div>
                    <div class="mt-2 text-lg font-semibold">Approvals and start-of-day execution</div>
                    <p class="mt-1 text-sm text-stone-200">Clear bottlenecks, confirm schedules, and move the most visible work first.</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-amber-200">Operational summary</div>
                    <div class="mt-2 text-lg font-semibold">Work, clients, and status at a glance</div>
                    <p class="mt-1 text-sm text-stone-200">Atlas keeps the team focused on outcomes instead of buried admin detail.</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-amber-200">Next step</div>
                    <div class="mt-2 text-lg font-semibold">Move directly into execution</div>
                    <p class="mt-1 text-sm text-stone-200">Use the action cards below to create, schedule, or review the work that matters now.</p>
                </div>
            </div>
        </div>
    </section>
</x-filament-widgets::widget>
