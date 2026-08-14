<x-filament-widgets::widget>
    <x-atlas-card class="overflow-hidden">
        <div class="flex items-center justify-between gap-4 border-b border-[var(--atlas-color-border-muted)] px-6 py-4">
            <div>
                <h3 class="text-[1.8rem] font-bold tracking-[-0.03em] text-[var(--atlas-color-text-primary)]">{{ $heading }}</h3>
                <p class="mt-1 text-base text-[var(--atlas-color-text-muted)]">{{ $mode === 'upcoming' ? 'Next 48 hours' : $description }}</p>
            </div>

            @if ($canViewJobs)
                <a
                    href="{{ \App\Core\Administration\Filament\Resources\Jobs\JobResource::getUrl('index') }}"
                    class="text-base font-semibold text-[var(--atlas-color-text-muted)] transition hover:text-[var(--atlas-color-text-primary)]"
                >
                    View all jobs →
                </a>
            @endif
        </div>

        <div>
            @if (! $canViewJobs)
                <div class="p-6">
                    <x-atlas.empty-state
                        title="Job visibility is restricted"
                        description="This role does not currently include access to the operations queue."
                    />
                </div>
            @elseif ($jobs->isEmpty())
                <div class="p-6">
                    <x-atlas.empty-state
                        title="No work is scheduled yet"
                        description="When jobs are planned or updated, the next operational queue will appear here."
                    >
                        @if (\App\Core\Administration\Filament\Resources\Jobs\JobResource::canCreate())
                            <x-atlas.button tag="a" :href="\App\Core\Administration\Filament\Resources\Jobs\JobResource::getUrl('create')">
                                Create first job
                            </x-atlas.button>
                        @endif
                    </x-atlas.empty-state>
                </div>
            @else
                <div class="divide-y divide-[var(--atlas-color-border-muted)]">
                    @foreach ($jobs as $job)
                        <a
                            href="{{ \App\Core\Administration\Filament\Resources\Jobs\JobResource::getUrl('view', ['record' => $job]) }}"
                            class="flex items-center gap-5 px-6 py-5 transition hover:bg-[var(--atlas-color-background-muted)]/45"
                        >
                            <div class="w-24 shrink-0 text-base font-medium tracking-[-0.02em] text-[var(--atlas-color-text-disabled)]">
                                {{ $job->job_number }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="truncate text-[1.45rem] font-semibold tracking-[-0.03em] text-[var(--atlas-color-text-primary)]">
                                    {{ $job->title }}
                                </div>
                                <div class="mt-1 truncate text-base text-[var(--atlas-color-text-muted)]">
                                    {{ $job->client?->display_name ?? 'No client linked' }}
                                </div>
                            </div>

                            <div class="hidden shrink-0 text-base text-[var(--atlas-color-text-muted)] lg:block">
                                {{ $job->planned_start_date?->format('D, g:i A') ?? 'Unscheduled' }}
                            </div>

                            <div class="hidden shrink-0 text-base text-[var(--atlas-color-text-disabled)] lg:block">
                                {{ $job->site?->name ? 'Site ready' : 'No site linked' }}
                            </div>

                            <div class="shrink-0">
                                <x-atlas.badge
                                    :status="$job->status->color()"
                                    class="px-3 py-1.5 text-sm font-bold"
                                >
                                    {{ $job->status->label() }}
                                </x-atlas.badge>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </x-atlas-card>
</x-filament-widgets::widget>
