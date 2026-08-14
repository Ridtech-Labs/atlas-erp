@php
    use App\Core\Administration\Filament\Resources\JobCards\JobCardResource;
    use App\Operations\Enums\JobStatus;

    $jobCardsIndexUrl = $job ? JobCardResource::getUrl('index', ['job' => $job->getKey()]) : '#';
    $statusTone = [
        'gray' => 'border-stone-200 bg-stone-50 text-stone-800',
        'primary' => 'border-blue-200 bg-blue-50 text-blue-800',
        'info' => 'border-sky-200 bg-sky-50 text-sky-800',
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
        'danger' => 'border-rose-200 bg-rose-50 text-rose-800',
    ][$statusPanel['color']] ?? 'border-stone-200 bg-stone-50 text-stone-800';
    $statusBadgeTone = [
        'gray' => 'bg-stone-950 text-white',
        'primary' => 'bg-blue-600 text-white',
        'info' => 'bg-sky-600 text-white',
        'success' => 'bg-emerald-600 text-white',
        'warning' => 'bg-amber-500 text-white',
        'danger' => 'bg-rose-600 text-white',
    ][$statusPanel['color']] ?? 'bg-stone-950 text-white';
    $actionTone = match ($primaryNextAction['kind'] ?? null) {
        'warning' => 'border-amber-200 bg-amber-50 text-amber-900',
        'attention' => 'border-blue-200 bg-blue-50 text-blue-900',
        'summary' => 'border-stone-200 bg-stone-50 text-stone-900',
        'planning' => 'border-stone-200 bg-stone-50 text-stone-900',
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-900',
        default => 'border-stone-950 bg-stone-950 text-white',
    };
    $actionButtonTone = match ($primaryNextAction['kind'] ?? null) {
        'warning' => 'bg-amber-500 text-white hover:bg-amber-600',
        'attention' => 'bg-blue-600 text-white hover:bg-blue-700',
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-700',
        default => 'bg-stone-950 text-white hover:bg-stone-800',
    };
    $statusPillTone = static function (?string $status): string {
        return match ($status) {
            'approved' => 'bg-emerald-50 text-emerald-700',
            'submitted' => 'bg-blue-50 text-blue-700',
            'returned' => 'bg-amber-50 text-amber-700',
            default => 'bg-stone-100 text-stone-700',
        };
    };
@endphp

<x-filament-widgets::widget>
    <section class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
        <div x-data="{ tab: 'overview' }" class="px-6 py-6 lg:px-8 lg:py-8">
            <div class="flex flex-wrap items-start justify-between gap-6">
                <div class="max-w-4xl">
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-[2rem] font-semibold tracking-tight text-stone-950">
                            {{ $job?->title ?? 'Job workspace' }}
                        </h2>
                        <span class="rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] {{ $statusBadgeTone }}">
                            {{ $statusPanel['status'] }}
                        </span>
                    </div>

                    <div class="mt-3 flex flex-wrap items-center gap-3 text-sm text-stone-500">
                        <span>{{ $job?->job_number ?: 'Pending number' }}</span>
                        <span>&middot;</span>
                        <span>{{ $job?->client?->display_name ?? 'No client linked' }}</span>
                        <span>&middot;</span>
                        <span>{{ $job?->site?->name ?? 'No site linked' }}</span>
                        <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-amber-700">
                            {{ $job?->priority?->label() ?? 'Priority pending' }}
                        </span>
                    </div>

                    <div class="mt-6 grid gap-5 lg:grid-cols-[1.45fr,0.95fr]">
                        <div class="rounded-[1.75rem] border px-5 py-5 {{ $statusTone }}">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.22em] opacity-80">
                                {{ $statusPanel['title'] }}
                            </div>
                            <div class="mt-3 text-3xl font-semibold tracking-tight">
                                {{ strtoupper($statusPanel['status']) }}
                            </div>
                            <div class="mt-3 text-sm leading-6 opacity-90">
                                {{ $statusPanel['message'] }}
                            </div>

                            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl border border-black/5 bg-white/70 px-4 py-3">
                                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] opacity-70">Last transition</div>
                                    <div class="mt-2 text-sm font-semibold text-stone-950">
                                        {{ $statusPanel['last_transition_label'] ?? 'No transition recorded yet' }}
                                    </div>
                                    @if ($statusPanel['last_transition_time'])
                                        <div class="mt-1 text-xs text-stone-500">
                                            {{ $statusPanel['last_transition_time']->format('D, j M Y H:i') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="rounded-2xl border border-black/5 bg-white/70 px-4 py-3">
                                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] opacity-70">Planned range</div>
                                    <div class="mt-2 text-sm font-semibold text-stone-950">
                                        {{ $job?->planned_start_date?->format('j M Y') ?? 'Not set' }}
                                        <span class="text-stone-400">to</span>
                                        {{ $job?->planned_end_date?->format('j M Y') ?? 'Not set' }}
                                    </div>
                                    @if ($statusPanel['next_action_label'])
                                        <div class="mt-1 text-xs text-stone-500">
                                            Next action: {{ $statusPanel['next_action_label'] }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="rounded-[1.75rem] border px-5 py-5 {{ $actionTone }}">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.22em] opacity-80">
                                Next action
                            </div>
                            <div class="mt-3 text-2xl font-semibold tracking-tight">
                                {{ $primaryNextAction['label'] ?? 'No operational action available' }}
                            </div>
                            <div class="mt-3 text-sm leading-6 opacity-90">
                                {{ $primaryNextAction['helper'] ?? 'This Job has no active workflow transition available right now.' }}
                            </div>

                            @if ($primaryNextAction)
                                <div class="mt-5">
                                    @if ($primaryNextAction['url'])
                                        <a href="{{ $primaryNextAction['url'] }}" class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold {{ $actionButtonTone }}">
                                            {{ $primaryNextAction['label'] }}
                                        </a>
                                    @elseif ($primaryNextAction['trigger'])
                                        <button type="button" wire:click="$parent.mountAction('{{ $primaryNextAction['trigger'] }}')" class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold {{ $actionButtonTone }}">
                                            {{ $primaryNextAction['label'] }}
                                        </button>
                                    @endif
                                </div>
                            @endif

                            @if ($completionEligible)
                                <div class="mt-4 rounded-2xl border border-emerald-200 bg-white/80 px-4 py-3 text-sm text-emerald-800">
                                    This Job can now be completed because all Job Cards are approved.
                                </div>
                            @elseif ($completionBlockers !== [])
                                <div class="mt-4 rounded-2xl border border-stone-200 bg-white/80 px-4 py-3">
                                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Completion blockers</div>
                                    <ul class="mt-2 space-y-1 text-sm text-stone-700">
                                        @foreach ($completionBlockers as $blocker)
                                            <li>{{ $blocker }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid min-w-[18rem] gap-3 self-stretch sm:grid-cols-2 lg:w-[21rem] lg:grid-cols-1">
                    <div class="rounded-2xl border border-stone-200 bg-stone-50 px-4 py-4">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Planned operator</div>
                        <div class="mt-2 text-sm font-semibold text-stone-950">{{ $plannedOperatorName ?? 'Not assigned' }}</div>
                        @if ($latestCardOperatorName && $latestCardOperatorName !== $plannedOperatorName)
                            <div class="mt-1 text-xs text-stone-500">Latest Job Card operator: {{ $latestCardOperatorName }}</div>
                        @endif
                    </div>
                    <div class="rounded-2xl border border-stone-200 bg-stone-50 px-4 py-4">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Equipment requirement</div>
                        <div class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->equipment_requirement ?? 'Not specified' }}</div>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                <div class="rounded-3xl border border-stone-200 bg-white p-5">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Job Cards</div>
                    <div class="mt-3 text-3xl font-semibold text-stone-950">{{ $jobCardCount }}</div>
                    <div class="mt-2 text-sm text-stone-500">{{ $approvedJobCardCount }} approved · {{ $submittedJobCardCount }} submitted · {{ $draftJobCardCount + $returnedJobCardCount }} open</div>
                </div>
                <div class="rounded-3xl border border-stone-200 bg-white p-5">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Recorded Hours</div>
                    <div class="mt-3 text-3xl font-semibold text-stone-950">{{ number_format($totalRecordedHours, 2) }}</div>
                    <div class="mt-2 text-sm text-stone-500">{{ number_format($totalNormalHours, 2) }} normal · {{ number_format($totalOvertimeHours, 2) }} overtime</div>
                </div>
                <div class="rounded-3xl border border-stone-200 bg-white p-5">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Crew</div>
                    <div class="mt-3 text-lg font-semibold text-stone-950">{{ $plannedOperatorName ?? 'Not assigned' }}</div>
                    <div class="mt-2 text-sm text-stone-500">
                        @if ($latestCardOperatorName && $latestCardOperatorName !== $plannedOperatorName)
                            Latest Job Card operator: {{ $latestCardOperatorName }}
                        @elseif ($crewCount > 0)
                            1 planned operator assigned
                        @else
                            No active operator assignment
                        @endif
                    </div>
                </div>
                <div class="rounded-3xl border border-stone-200 bg-white p-5">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Equipment</div>
                    <div class="mt-3 text-lg font-semibold text-stone-950">{{ $latestCard?->equipment_reference ?? $job?->equipment_requirement ?? 'Not assigned' }}</div>
                    <div class="mt-2 text-sm text-stone-500">{{ $equipmentCount > 0 ? 'Operational equipment requirement recorded' : 'No equipment requirement recorded' }}</div>
                </div>
                <div class="rounded-3xl border border-stone-200 bg-white p-5">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Operational Documents</div>
                    <div class="mt-3 text-3xl font-semibold text-stone-950">{{ $attachmentCount }}</div>
                    <div class="mt-2 text-sm text-stone-500">Signed cards and supporting uploads</div>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap gap-2 border-b border-stone-200 pb-4">
                @foreach ([
                    'overview' => 'Overview',
                    'planning' => 'Planning',
                    'crew' => 'Crew',
                    'equipment' => 'Equipment',
                    'job-cards' => 'Job Cards',
                    'documents' => 'Operational Documents',
                    'approvals' => 'Approvals',
                    'activity' => 'Activity',
                    'notes' => 'Notes',
                ] as $key => $label)
                    <button
                        type="button"
                        x-on:click="tab = '{{ $key }}'"
                        x-bind:class="tab === '{{ $key }}' ? 'bg-stone-950 text-white' : 'bg-stone-100 text-stone-600'"
                        class="rounded-full px-4 py-2 text-sm font-medium transition"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <div class="mt-8 space-y-6">
                <div x-show="tab === 'overview'" class="space-y-6">
                    <div class="grid gap-6 xl:grid-cols-[1.25fr,0.95fr]">
                        <div class="rounded-3xl border border-stone-200 bg-white p-6">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <div class="text-lg font-semibold text-stone-950">
                                        {{ $activeCard ? 'Active Job Card' : 'Latest Job Card' }}
                                    </div>
                                    <div class="mt-1 text-sm text-stone-500">
                                        {{ $activeCard ? 'This card currently needs the most attention in the workflow.' : 'Operational focus will appear here as soon as a Job Card exists.' }}
                                    </div>
                                </div>
                                @if ($activeCard)
                                    <span class="rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] {{ $statusPillTone((string) $activeCard->getRawOriginal('approval_status')) }}">
                                        {{ $activeCard->approval_status?->label() ?? 'Draft' }}
                                    </span>
                                @endif
                            </div>

                            @if ($activeCard)
                                <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                    <div class="rounded-2xl bg-stone-50 p-4">
                                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Card</div>
                                        <div class="mt-2 text-sm font-semibold text-stone-950">{{ $activeCard->card_number ?? 'Job card' }}</div>
                                        <div class="mt-1 text-sm text-stone-500">{{ $activeCard->card_date?->format('D, j M Y') ?? 'No date' }}</div>
                                    </div>
                                    <div class="rounded-2xl bg-stone-50 p-4">
                                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Shift and operator</div>
                                        <div class="mt-2 text-sm font-semibold text-stone-950">{{ \App\Operations\Enums\JobShift::tryFrom((string) $activeCard->shift)?->label() ?? (filled($activeCard->shift) ? ucfirst((string) $activeCard->shift) : 'Not set') }}</div>
                                        <div class="mt-1 text-sm text-stone-500">{{ $activeCard->operatorDisplayName() ?? 'No operator assigned' }}</div>
                                    </div>
                                    <div class="rounded-2xl bg-stone-50 p-4">
                                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Equipment and work area</div>
                                        <div class="mt-2 text-sm font-semibold text-stone-950">{{ $activeCard->equipment_reference ?? 'No equipment recorded' }}</div>
                                        <div class="mt-1 text-sm text-stone-500">{{ $activeCard->workEntries->first()?->work_area ?? $job?->work_area ?? 'No work area recorded' }}</div>
                                    </div>
                                    <div class="rounded-2xl bg-stone-50 p-4">
                                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Recorded hours</div>
                                        <div class="mt-2 text-sm font-semibold text-stone-950">{{ number_format((float) $activeCard->workEntries->sum('total_hours'), 2) }} total</div>
                                        <div class="mt-1 text-sm text-stone-500">{{ number_format((float) $activeCard->workEntries->sum('normal_hours'), 2) }} normal · {{ number_format((float) $activeCard->workEntries->sum('overtime_hours'), 2) }} overtime</div>
                                    </div>
                                </div>

                                <div class="mt-5 flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-4">
                                    <div>
                                        <div class="text-sm font-semibold text-stone-950">{{ $activeCardAction['label'] ?? 'View Job Card' }}</div>
                                        <div class="mt-1 text-sm text-stone-500">
                                            @if ((string) $activeCard->getRawOriginal('approval_status') === 'returned')
                                                {{ $activeCard->return_reason ?? 'This card needs corrections before resubmission.' }}
                                            @elseif ((string) $activeCard->getRawOriginal('approval_status') === 'submitted')
                                                This card is waiting for approval attention.
                                            @elseif ((string) $activeCard->getRawOriginal('approval_status') === 'approved')
                                                This card is approved and remains part of the final operational record.
                                            @else
                                                Continue recording operational work on this card.
                                            @endif
                                        </div>
                                    </div>
                                    @if ($activeCardAction)
                                        <a href="{{ $activeCardAction['url'] }}" class="inline-flex items-center rounded-full bg-stone-950 px-4 py-2 text-sm font-semibold text-white hover:bg-stone-800">
                                            {{ $activeCardAction['label'] }}
                                        </a>
                                    @endif
                                </div>
                            @elseif ($jobStatus === JobStatus::Scheduled)
                                <div class="mt-6 rounded-3xl border border-dashed border-blue-200 bg-blue-50 px-5 py-6">
                                    <div class="text-lg font-semibold text-stone-950">No Job Card exists yet</div>
                                    <div class="mt-2 text-sm leading-6 text-stone-600">
                                        Starting this Job will move it into In Progress and create Job Card #1 automatically using the current planning details.
                                    </div>
                                    <div class="mt-4">
                                        <button type="button" wire:click="$parent.mountAction('start')" class="inline-flex items-center rounded-full bg-stone-950 px-4 py-2 text-sm font-semibold text-white hover:bg-stone-800">
                                            Start Job
                                        </button>
                                    </div>
                                </div>
                            @elseif ($jobStatus === JobStatus::InProgress)
                                <div class="mt-6 rounded-3xl border border-dashed border-amber-200 bg-amber-50 px-5 py-6">
                                    <div class="text-lg font-semibold text-stone-950">No active Job Card is open</div>
                                    <div class="mt-2 text-sm leading-6 text-stone-600">
                                        This Job is already in progress, but no draft Job Card is available. Create the next operational record to continue safely.
                                    </div>
                                    <div class="mt-4">
                                        <a href="{{ JobCardResource::getUrl('create', ['job' => $job]) }}" class="inline-flex items-center rounded-full bg-stone-950 px-4 py-2 text-sm font-semibold text-white hover:bg-stone-800">
                                            Create New Job Card
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="mt-6 rounded-3xl border border-dashed border-stone-300 px-5 py-8 text-sm text-stone-500">
                                    No Job Cards have been created for this Job yet.
                                </div>
                            @endif
                        </div>

                        <div class="space-y-6">
                            <div class="rounded-3xl border border-stone-200 bg-white p-6">
                                <div class="text-lg font-semibold text-stone-950">Operational snapshot</div>
                                <div class="mt-5 space-y-4">
                                    <div class="rounded-2xl bg-stone-50 p-4">
                                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Approval attention</div>
                                        <div class="mt-2 text-sm font-semibold text-stone-950">
                                            @if ($jobCardCount === 0)
                                                No Job Cards recorded yet
                                            @elseif ($submittedAttentionCount > 0)
                                                {{ $submittedAttentionCount }} Job Card{{ $submittedAttentionCount === 1 ? '' : 's' }} awaiting approval
                                            @elseif ($returnedAttentionCount > 0)
                                                {{ $returnedAttentionCount }} Job Card{{ $returnedAttentionCount === 1 ? '' : 's' }} returned for correction
                                            @else
                                                All Job Cards approved or in active drafting
                                            @endif
                                        </div>
                                    </div>
                                    <div class="rounded-2xl bg-stone-50 p-4">
                                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Latest milestone</div>
                                        <div class="mt-2 text-sm font-semibold text-stone-950">{{ $latestTransition['label'] ?? 'No milestone recorded yet' }}</div>
                                        @if ($latestTransition && $latestTransition['time'])
                                            <div class="mt-1 text-sm text-stone-500">{{ $latestTransition['time']->format('D, j M Y H:i') }}</div>
                                        @endif
                                    </div>
                                    <div class="rounded-2xl bg-stone-50 p-4">
                                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Work entries</div>
                                        <div class="mt-2 text-sm font-semibold text-stone-950">{{ $totalWorkEntries }}</div>
                                        <div class="mt-1 text-sm text-stone-500">Across all Job Cards for this workspace</div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-3xl border border-stone-200 bg-white p-6">
                                <div class="text-lg font-semibold text-stone-950">Approval states</div>
                                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                                    <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Draft</div><div class="mt-2 text-2xl font-semibold text-stone-950">{{ $draftJobCardCount }}</div></div>
                                    <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Submitted</div><div class="mt-2 text-2xl font-semibold text-stone-950">{{ $submittedJobCardCount }}</div></div>
                                    <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Approved</div><div class="mt-2 text-2xl font-semibold text-stone-950">{{ $approvedJobCardCount }}</div></div>
                                    <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Returned</div><div class="mt-2 text-2xl font-semibold text-stone-950">{{ $returnedJobCardCount }}</div></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($isPlanningHeavy)
                        <div class="rounded-3xl border border-stone-200 bg-white p-6">
                            <div class="text-lg font-semibold text-stone-950">Planning context</div>
                            <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Client reference</div><div class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->client_reference ?? 'Not set' }}</div></div>
                                <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Internal reference</div><div class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->internal_reference ?? 'Not set' }}</div></div>
                                <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Estimated value</div><div class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->estimated_value ? strtoupper((string) $job->currency).' '.number_format((float) $job->estimated_value, 2) : 'Not estimated' }}</div></div>
                                <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Requested date</div><div class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->requested_start_date?->format('j M Y') ?? 'Not set' }}</div></div>
                            </div>
                        </div>
                    @endif
                </div>

                <div x-show="tab === 'planning'" class="rounded-3xl border border-stone-200 bg-white p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-lg font-semibold text-stone-950">Planning record</div>
                            <div class="mt-1 text-sm text-stone-500">Planning fields remain the source plan for this Job and become operationally restricted once work is active.</div>
                        </div>
                    </div>
                    <dl class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        <div class="rounded-2xl bg-stone-50 p-4"><dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Requested date</dt><dd class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->requested_start_date?->format('j M Y') ?? 'Not set' }}</dd></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Planned start</dt><dd class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->planned_start_date?->format('j M Y') ?? 'Not set' }} {{ $job?->planned_start_time ?? '' }}</dd></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Planned end</dt><dd class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->planned_end_date?->format('j M Y') ?? 'Not set' }} {{ $job?->planned_end_time ?? '' }}</dd></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Vessel</dt><dd class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->vessel ?? 'Not set' }}</dd></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Work area</dt><dd class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->work_area ?? 'Not set' }}</dd></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Client reference</dt><dd class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->client_reference ?? 'Not set' }}</dd></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Internal reference</dt><dd class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->internal_reference ?? 'Not set' }}</dd></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Estimated value</dt><dd class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->estimated_value ? strtoupper((string) $job->currency).' '.number_format((float) $job->estimated_value, 2) : 'Not estimated' }}</dd></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Job reference</dt><dd class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->job_reference ?? 'Not set' }}</dd></div>
                    </dl>
                </div>

                <div x-show="tab === 'crew'" class="rounded-3xl border border-stone-200 bg-white p-6">
                    <div class="text-lg font-semibold text-stone-950">Crew</div>
                    <div class="mt-1 text-sm text-stone-500">The assigned operator is the current operational crew assignment for this Job.</div>
                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl bg-stone-50 p-4">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Planned operator</div>
                            <div class="mt-2 text-sm font-semibold text-stone-950">{{ $plannedOperatorName ?? 'No operator assigned yet' }}</div>
                        </div>
                        <div class="rounded-2xl bg-stone-50 p-4">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Latest Job Card operator</div>
                            <div class="mt-2 text-sm font-semibold text-stone-950">{{ $latestCardOperatorName ?? 'No Job Card operator recorded yet' }}</div>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'equipment'" class="rounded-3xl border border-stone-200 bg-white p-6">
                    <div class="text-lg font-semibold text-stone-950">Equipment</div>
                    <div class="mt-1 text-sm text-stone-500">Atlas currently surfaces the planning requirement alongside the latest equipment recorded on a Job Card.</div>
                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Required equipment</div><div class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->equipment_requirement ?? 'Not set' }}</div></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Latest executed equipment</div><div class="mt-2 text-sm font-semibold text-stone-950">{{ $latestCard?->equipment_reference ?? 'No Job Card yet' }}</div></div>
                    </div>
                </div>

                <div x-show="tab === 'job-cards'" class="rounded-3xl border border-stone-200 bg-white p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="text-lg font-semibold text-stone-950">Job Cards</div>
                            <div class="mt-1 text-sm text-stone-500">Each Job Card captures one dated operational period and clearly shows the action it needs next.</div>
                        </div>
                        <a href="{{ $jobCardsIndexUrl }}" class="rounded-full bg-stone-950 px-4 py-2 text-sm font-medium text-white">
                            Browse all cards
                        </a>
                    </div>

                    <div class="mt-6 space-y-3">
                        @forelse ($jobCards as $card)
                            @php
                                $cardStatus = (string) $card->getRawOriginal('approval_status');
                                $cardAction = match ($cardStatus) {
                                    'submitted' => auth()->user()?->hasPermissionTo('jobs.approve') ? 'Review' : 'Awaiting Approval',
                                    'returned' => 'Correct and Resubmit',
                                    'approved' => 'View',
                                    default => 'Continue',
                                };
                                $cardUrl = match ($cardStatus) {
                                    'approved' => JobCardResource::getUrl('view', ['record' => $card]),
                                    'submitted' => auth()->user()?->hasPermissionTo('jobs.approve')
                                        ? JobCardResource::getUrl('edit', ['record' => $card])
                                        : JobCardResource::getUrl('view', ['record' => $card]),
                                    default => JobCardResource::getUrl('edit', ['record' => $card]),
                                };
                            @endphp
                            <a href="{{ $cardUrl }}" class="block rounded-2xl border border-stone-200 bg-stone-50 px-4 py-4 transition hover:border-stone-300 hover:bg-white">
                                <div class="flex flex-wrap items-start justify-between gap-4">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <div class="text-sm font-semibold text-stone-950">{{ $card->card_number ?? 'Job card' }}</div>
                                            <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide {{ $statusPillTone($cardStatus) }}">
                                                {{ $card->approval_status?->label() ?? 'Draft' }}
                                            </span>
                                        </div>
                                        <div class="mt-2 text-sm text-stone-600">{{ $card->card_date?->format('D, j M Y') ?? 'No date' }} · {{ \App\Operations\Enums\JobShift::tryFrom((string) $card->shift)?->label() ?? (filled($card->shift) ? ucfirst((string) $card->shift) : 'Shift not set') }}</div>
                                        <div class="mt-2 grid gap-2 text-sm text-stone-500 md:grid-cols-2 xl:grid-cols-4">
                                            <div>{{ $card->operatorDisplayName() ?? 'No operator assigned' }}</div>
                                            <div>{{ $card->equipment_reference ?: 'No equipment recorded' }}</div>
                                            <div>{{ $card->workEntries->first()?->vessel ?? $job?->vessel ?? 'No vessel recorded' }}</div>
                                            <div>{{ $card->workEntries->first()?->work_area ?? $job?->work_area ?? 'No work area recorded' }}</div>
                                        </div>
                                    </div>
                                    <div class="grid gap-2 text-right text-sm text-stone-500 sm:min-w-[17rem]">
                                        <div>{{ number_format((float) $card->workEntries->sum('normal_hours'), 2) }} normal · {{ number_format((float) $card->workEntries->sum('overtime_hours'), 2) }} overtime</div>
                                        <div>{{ number_format((float) $card->workEntries->sum('total_hours'), 2) }} total hours · {{ $card->workEntries->count() }} entries</div>
                                        <div class="font-semibold text-stone-700">{{ $cardAction }}</div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="rounded-2xl border border-dashed border-stone-300 px-5 py-8 text-sm text-stone-500">
                                @if ($jobStatus === JobStatus::Scheduled)
                                    No Job Cards have been created yet. Start the Job to generate the first operational card automatically.
                                @elseif ($jobStatus === JobStatus::OnHold)
                                    No new Job Cards can be created while the Job is On Hold. Resume the Job first.
                                @else
                                    No Job Cards have been created for this Job yet.
                                @endif
                            </div>
                        @endforelse
                    </div>
                </div>

                <div x-show="tab === 'documents'" class="rounded-3xl border border-stone-200 bg-white p-6">
                    <div class="text-lg font-semibold text-stone-950">Operational Documents</div>
                    <div class="mt-1 text-sm text-stone-500">Signed Job Cards, worksite photographs, delivery notes, incident reports, and supporting documents remain attached to Job Cards.</div>
                    <div class="mt-6 rounded-2xl bg-stone-50 p-4 text-sm font-semibold text-stone-950">{{ $attachmentCount }} document(s) recorded</div>
                </div>

                <div x-show="tab === 'approvals'" class="rounded-3xl border border-stone-200 bg-white p-6">
                    <div class="text-lg font-semibold text-stone-950">Approvals</div>
                    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Draft cards</div><div class="mt-2 text-2xl font-semibold text-stone-950">{{ $draftJobCardCount }}</div></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Submitted cards</div><div class="mt-2 text-2xl font-semibold text-stone-950">{{ $submittedJobCardCount }}</div></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Approved cards</div><div class="mt-2 text-2xl font-semibold text-stone-950">{{ $approvedJobCardCount }}</div></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Returned cards</div><div class="mt-2 text-2xl font-semibold text-stone-950">{{ $returnedJobCardCount }}</div></div>
                    </div>
                    <div class="mt-6 rounded-2xl bg-stone-50 p-4">
                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Latest approved card</div>
                        <div class="mt-2 text-sm font-semibold text-stone-950">{{ $latestApprovedCard?->card_number ?? 'No approved card yet' }}</div>
                        <div class="mt-1 text-sm text-stone-500">{{ $latestApprovedCard?->approved_at?->format('D, j M Y H:i') ?? 'Approval timestamps will appear here once cards are approved.' }}</div>
                    </div>
                </div>

                <div x-show="tab === 'activity'" class="rounded-3xl border border-stone-200 bg-white p-6">
                    <div class="text-lg font-semibold text-stone-950">Activity</div>
                    <div class="mt-6 space-y-3">
                        @forelse ($activityItems as $activity)
                            <div class="rounded-2xl bg-stone-50 px-4 py-4">
                                <div class="text-sm font-semibold text-stone-950">{{ $activity->description ?: $activity->event }}</div>
                                <div class="mt-1 text-xs text-stone-500">{{ $activity->created_at?->format('D, j M Y H:i') }}</div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-stone-300 px-4 py-6 text-sm text-stone-500">No activity has been recorded for this Job yet.</div>
                        @endforelse
                    </div>
                </div>

                <div x-show="tab === 'notes'" class="rounded-3xl border border-stone-200 bg-white p-6">
                    <div class="text-lg font-semibold text-stone-950">Notes</div>
                    <div class="mt-4 text-sm leading-7 text-stone-600">
                        {{ $job?->description ?: 'No planning notes have been recorded for this Job yet.' }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-filament-widgets::widget>
