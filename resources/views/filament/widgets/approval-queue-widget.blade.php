<x-filament-widgets::widget>
    <x-atlas-card class="overflow-hidden">
        <div class="flex items-center justify-between gap-3 border-b border-[var(--atlas-color-border-muted)] px-6 py-4">
            <div class="flex items-center gap-3">
                <h3 class="text-[1.8rem] font-bold tracking-[-0.03em] text-[var(--atlas-color-text-primary)]">Approval Queue</h3>
                @if ($items->isNotEmpty())
                    <span class="inline-flex h-8 min-w-8 items-center justify-center rounded-full bg-[var(--atlas-color-status-warning-soft)] px-2 text-sm font-bold text-[var(--atlas-color-status-warning)]">
                        {{ $items->count() }}
                    </span>
                @endif
            </div>
        </div>

        @if (! $canViewJobs)
            <div class="p-6">
                <x-atlas.empty-state
                    title="Approval visibility is restricted"
                    description="This role does not currently include access to the approval queue."
                />
            </div>
        @elseif ($items->isEmpty())
            <div class="p-6">
                <x-atlas.empty-state
                    title="Approval queue is clear"
                    description="Pending approvals will appear here when jobs are submitted for review."
                />
            </div>
        @else
            <div class="divide-y divide-[var(--atlas-color-border-muted)]">
                @foreach ($items as $job)
                    <div class="px-6 py-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="text-[1.45rem] font-semibold tracking-[-0.02em] text-[var(--atlas-color-text-primary)]">
                                    {{ $job->title }}
                                </div>
                                <div class="mt-1.5 text-base text-[var(--atlas-color-text-muted)]">{{ $job->job_number }}</div>

                                <div class="mt-3 flex items-center gap-3">
                                    <span class="atlas-avatar h-10 w-10 bg-[var(--atlas-color-action-primary-soft)] text-[var(--atlas-color-action-primary)]">
                                        {{ str($job->client?->display_name ?? 'AT')->substr(0, 2)->upper() }}
                                    </span>
                                    <span class="text-base text-[var(--atlas-color-text-secondary)]">{{ $job->client?->display_name ?? 'No client linked' }}</span>
                                </div>
                            </div>

                            <div class="shrink-0 text-right">
                                <div class="text-[1.35rem] font-bold tracking-[-0.03em] text-[var(--atlas-color-text-primary)]">
                                    {{ $job->planned_start_date?->format('g:i A') ?? 'Review' }}
                                </div>
                                <div class="mt-1.5 text-base text-[var(--atlas-color-text-muted)]">
                                    {{ $job->planned_start_date?->format('D, j M') ?? 'Awaiting schedule' }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-end gap-3">
                            <x-atlas.button
                                tag="a"
                                variant="secondary"
                                size="sm"
                                :href="\App\Core\Administration\Filament\Resources\Jobs\JobResource::getUrl('view', ['record' => $job])"
                            >
                                Review
                            </x-atlas.button>

                            <x-atlas.button
                                tag="a"
                                size="sm"
                                :href="\App\Core\Administration\Filament\Resources\Jobs\JobResource::getUrl('edit', ['record' => $job])"
                            >
                                Approve
                            </x-atlas.button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-atlas-card>
</x-filament-widgets::widget>
