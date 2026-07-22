<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Upcoming Work</x-slot>

        <div class="space-y-3">
            @forelse ($jobs as $job)
                <div class="rounded-3xl border border-stone-200 bg-white px-5 py-4 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-stone-950">{{ $job->title }}</div>
                            <div class="mt-1 text-xs uppercase tracking-[0.18em] text-stone-400">{{ $job->job_number }}</div>
                            <div class="mt-3 flex flex-wrap gap-3 text-sm text-stone-600">
                                <span>{{ $job->client?->display_name ?? 'No client' }}</span>
                                <span>{{ $job->site?->name ?? 'No site' }}</span>
                                <span>{{ $job->planned_start_date?->format('D, j M') ?? 'Unscheduled' }}</span>
                            </div>
                        </div>
                        <span class="rounded-full bg-stone-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-stone-700">
                            {{ $job->status->label() }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-stone-300 bg-white px-5 py-8 text-sm text-stone-500">
                    No upcoming work is scheduled yet.
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
