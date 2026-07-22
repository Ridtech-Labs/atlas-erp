<x-filament-widgets::widget>
    <section class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
        <div class="grid gap-6 px-6 py-6 lg:grid-cols-[1.4fr_0.9fr] lg:px-8">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full bg-stone-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-stone-700">
                    Operational workspace
                </div>

                <h2 class="mt-4 text-2xl font-semibold tracking-tight text-stone-950 sm:text-3xl">
                    {{ $job?->title ?? 'Job workspace' }}
                </h2>

                <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-stone-500">
                    <span>{{ $job?->job_number ?: 'Job number pending' }}</span>
                    <span>&middot;</span>
                    <span>{{ $job?->client?->display_name ?? 'No client linked' }}</span>
                    <span class="rounded-full bg-stone-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-stone-700">
                        {{ $job?->status?->label() ?? 'Unknown' }}
                    </span>
                    <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-amber-700">
                        {{ $job?->priority?->label() ?? 'Priority pending' }}
                    </span>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl bg-stone-50 px-4 py-4">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Client</div>
                        <div class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->client?->display_name ?? 'Not assigned' }}</div>
                    </div>
                    <div class="rounded-2xl bg-stone-50 px-4 py-4">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Site</div>
                        <div class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->site?->name ?? 'No site linked' }}</div>
                    </div>
                    <div class="rounded-2xl bg-stone-50 px-4 py-4">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Planned start</div>
                        <div class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->planned_start_date?->format('D, j M Y') ?? 'Not scheduled' }}</div>
                    </div>
                    <div class="rounded-2xl bg-amber-50 px-4 py-4">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-amber-700">Estimated value</div>
                        <div class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->estimated_value ? 'GHS '.number_format((float) $job->estimated_value, 2) : 'Not estimated' }}</div>
                    </div>
                </div>
            </div>

            <div class="grid gap-4">
                <div class="rounded-2xl border border-stone-200 bg-stone-50 p-5">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Workflow focus</div>
                    <div class="mt-2 text-lg font-semibold text-stone-950">Keep status transitions deliberate</div>
                    <p class="mt-2 text-sm text-stone-600">
                        Atlas keeps planning, approval, scheduling, and completion visible without exposing raw workflow edits.
                    </p>
                </div>

                <div class="rounded-2xl border border-dashed border-stone-300 bg-white p-5">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Coming soon</div>
                    <div class="mt-2 text-lg font-semibold text-stone-950">Crew, equipment, and waybills</div>
                    <p class="mt-2 text-sm text-stone-600">
                        This job workspace is ready to expand into deeper field-operations coordination when those modules arrive.
                    </p>
                </div>
            </div>
        </div>
    </section>
</x-filament-widgets::widget>
