@php
    use App\Core\Administration\Filament\Resources\JobCards\JobCardResource;
    use App\Core\Administration\Filament\Resources\Waybills\WaybillResource;
    use App\Operations\Enums\JobShift;
    use App\Operations\Enums\JobStatus;

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
    $trackerTone = static function (string $state): string {
        return match ($state) {
            'complete' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
            'current' => 'border-stone-950 bg-stone-950 text-white',
            default => 'border-stone-200 bg-white text-stone-500',
        };
    };
    $pillTone = static function (?string $status): string {
        return match ($status) {
            'approved', 'verified' => 'bg-emerald-50 text-emerald-700',
            'submitted', 'pending_verification' => 'bg-blue-50 text-blue-700',
            'billing_ready' => 'bg-violet-50 text-violet-700',
            'returned' => 'bg-amber-50 text-amber-700',
            default => 'bg-stone-100 text-stone-700',
        };
    };
    $jobCardsBrowseUrl = $jobCardsIndexUrl ?? ($job ? JobCardResource::getUrl('index', ['job' => $job->getKey()]) : '#');
    $waybillsBrowseUrl = $waybillsIndexUrl ?? ($job ? WaybillResource::getUrl('index', ['job' => $job->getKey()]) : '#');
@endphp

<x-filament-widgets::widget>
    <section class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
        <div
            x-data="{
                tab: 'overview',
                openStage: null,
                stageGuideOpen: false,
                toggleStage(key) {
                    this.openStage = this.openStage === key ? null : key
                },
            }"
            class="px-6 py-6 lg:px-8 lg:py-8"
        >
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
                    </div>

                    <div class="mt-6 grid gap-5 xl:grid-cols-[1.2fr,0.9fr]">
                        <div class="rounded-[1.75rem] border px-5 py-5 {{ $statusTone }}">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.22em] opacity-80">Current stage</div>
                            <div class="mt-3 text-3xl font-semibold tracking-tight">{{ strtoupper($statusPanel['status']) }}</div>
                            <div class="mt-3 text-sm leading-6 opacity-90">{{ $statusPanel['message'] }}</div>
                            @if (($workflowGuide['show_help'] ?? false) && filled($statusPanel['next_action_label']))
                                <div class="mt-3 text-xs font-medium opacity-90">
                                    Next action: {{ $statusPanel['next_action_label'] }}
                                </div>
                            @endif

                            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl border border-black/5 bg-white/70 px-4 py-3">
                                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] opacity-70">Last transition</div>
                                    <div class="mt-2 text-sm font-semibold text-stone-950">{{ $statusPanel['last_transition_label'] ?? 'No transition recorded yet' }}</div>
                                    @if ($statusPanel['last_transition_time'])
                                        <div class="mt-1 text-xs text-stone-500">{{ $statusPanel['last_transition_time']->format('D, j M Y H:i') }}</div>
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
                                        <div class="mt-1 text-xs text-stone-500">Next action: {{ $statusPanel['next_action_label'] }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="rounded-[1.75rem] border border-stone-200 bg-stone-50 px-5 py-5 text-stone-900">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.22em] text-stone-500">Next action</div>
                            <div class="mt-3 text-2xl font-semibold tracking-tight">{{ $primaryNextAction['label'] ?? 'No operational action available' }}</div>
                            <div class="mt-3 text-sm leading-6 text-stone-600">{{ $primaryNextAction['helper'] ?? 'This Job has no active workflow transition available right now.' }}</div>

                            @if ($primaryNextAction)
                                <div class="mt-5">
                                    @if ($primaryNextAction['url'])
                                        <a href="{{ $primaryNextAction['url'] }}" class="inline-flex items-center rounded-full bg-stone-950 px-4 py-2 text-sm font-semibold text-white hover:bg-stone-800">
                                            {{ $primaryNextAction['button_label'] ?? $primaryNextAction['label'] }}
                                        </a>
                                    @elseif ($primaryNextAction['trigger'])
                                        <button type="button" wire:click="runWorkflowAction('{{ $primaryNextAction['trigger'] }}')" class="inline-flex items-center rounded-full bg-stone-950 px-4 py-2 text-sm font-semibold text-white hover:bg-stone-800">
                                            {{ $primaryNextAction['button_label'] ?? $primaryNextAction['label'] }}
                                        </button>
                                    @endif
                                </div>
                            @endif

                            @if ($completionBlockers !== [])
                                <div class="mt-4 rounded-2xl border border-stone-200 bg-white px-4 py-3">
                                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">What is blocking completion?</div>
                                    <ul class="mt-2 space-y-1 text-sm text-stone-700">
                                        @foreach ($completionBlockers as $blocker)
                                            <li>{{ $blocker }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @elseif ($completionEligible)
                                <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                                    This Job can now be completed because all {{ strtolower($operationalDocumentLabelPlural) }} are billing-ready.
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
                            <div class="mt-1 text-xs text-stone-500">Latest Client Job Card operator: {{ $latestCardOperatorName }}</div>
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
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Lifecycle</div>
                    <div class="mt-3 text-base font-semibold text-stone-950">{{ $workflowTracker[0]['label'] ?? 'Planning' }}</div>
                    <div class="mt-2 text-sm text-stone-500">Follow the tracker below to move this Job safely from planning through billing.</div>
                </div>
                <div class="rounded-3xl border border-stone-200 bg-white p-5">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">{{ $operationalDocumentLabelPlural }}</div>
                    <div class="mt-3 text-3xl font-semibold text-stone-950">{{ $isTrucking ? $waybillCount : $jobCardCount }}</div>
                    <div class="mt-2 text-sm text-stone-500">{{ $pendingVerificationCount }} awaiting Accounts review · {{ $billingReadyCount }} billing ready</div>
                </div>
                <div class="rounded-3xl border border-stone-200 bg-white p-5">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Recorded hours</div>
                    <div class="mt-3 text-3xl font-semibold text-stone-950">{{ number_format($totalRecordedHours, 2) }}</div>
                    <div class="mt-2 text-sm text-stone-500">{{ number_format($totalNormalHours, 2) }} normal · {{ number_format($totalOvertimeHours, 2) }} overtime</div>
                </div>
                <div class="rounded-3xl border border-stone-200 bg-white p-5">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Accounts Review</div>
                    <div class="mt-3 text-3xl font-semibold text-stone-950">{{ $verifiedCount }}</div>
                    <div class="mt-2 text-sm text-stone-500">{{ $returnedCount }} returned · {{ $recordedCount }} still in recording</div>
                </div>
                <div class="rounded-3xl border border-stone-200 bg-white p-5">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Documents</div>
                    <div class="mt-3 text-3xl font-semibold text-stone-950">{{ $attachmentCount }}</div>
                    <div class="mt-2 text-sm text-stone-500">Signed cards and supporting uploads</div>
                </div>
            </div>

            <div class="mt-8 rounded-3xl border border-stone-200 bg-white p-6">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <div class="text-lg font-semibold text-stone-950">Workflow tracker</div>
                        <div class="mt-1 text-sm text-stone-500">Keep track of where this Job is now and what each stage means in day-to-day operations.</div>
                    </div>
                    @if ($workflowGuide['show_help'] ?? false)
                        <button
                            type="button"
                            x-on:click="stageGuideOpen = true"
                            class="inline-flex items-center rounded-full border border-stone-200 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition hover:border-stone-300 hover:bg-stone-50"
                        >
                            What do these stages mean?
                        </button>
                    @endif
                </div>
                <div class="mt-5 grid gap-3 md:grid-cols-3 {{ ($workflowGuide['show_help'] ?? false) ? 'xl:grid-cols-7' : 'xl:grid-cols-6' }}">
                    @foreach ($workflowTracker as $step)
                        <div
                            class="relative rounded-2xl border px-4 py-4 {{ $trackerTone($step['state']) }}"
                            @mouseenter="openStage = '{{ $step['key'] }}'"
                            @mouseleave="openStage = null"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-[11px] font-semibold uppercase tracking-[0.18em]">{{ strtoupper($step['state']) }}</div>
                                    <div class="mt-2 text-sm font-semibold">{{ $step['label'] }}</div>
                                </div>
                                @if (filled($step['description'] ?? null))
                                    <button
                                        type="button"
                                        x-on:click="toggleStage('{{ $step['key'] }}')"
                                        x-on:focus="openStage = '{{ $step['key'] }}'"
                                        x-on:blur="setTimeout(() => { if (openStage === '{{ $step['key'] }}') openStage = null }, 120)"
                                        x-on:keydown.escape.stop="openStage = null"
                                        x-bind:aria-expanded="openStage === '{{ $step['key'] }}' ? 'true' : 'false'"
                                        aria-haspopup="dialog"
                                        aria-label="What does {{ $step['label'] }} mean?"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-current/15 bg-white/80 text-xs font-semibold text-current transition hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-stone-400"
                                    >
                                        ?
                                    </button>
                                @endif
                            </div>

                            @if (filled($step['description'] ?? null))
                                <div
                                    x-cloak
                                    x-show="openStage === '{{ $step['key'] }}'"
                                    x-transition.opacity.duration.150ms
                                    x-on:click.outside="openStage = null"
                                    class="absolute left-3 right-3 top-[calc(100%-0.25rem)] z-20 rounded-2xl border border-stone-200 bg-white p-4 text-left shadow-xl"
                                    role="dialog"
                                    aria-label="{{ $step['label'] }} explanation"
                                >
                                    <div class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">{{ $step['label'] }}</div>
                                    <div class="mt-2 text-sm leading-6 text-stone-700">{{ $step['description'] }}</div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($workflowGuide['show_help'] ?? false)
                <div
                    x-cloak
                    x-show="stageGuideOpen"
                    x-on:keydown.escape.window="stageGuideOpen = false"
                    class="fixed inset-0 z-40 flex items-center justify-center bg-stone-950/40 px-4 py-8"
                    role="dialog"
                    aria-modal="true"
                    aria-label="Heavy Machinery workflow stage guide"
                >
                    <div
                        x-on:click.outside="stageGuideOpen = false"
                        class="max-h-[85vh] w-full max-w-2xl overflow-y-auto rounded-[2rem] border border-stone-200 bg-white p-6 shadow-2xl lg:p-8"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-xl font-semibold text-stone-950">Heavy Machinery stage guide</div>
                                <div class="mt-1 text-sm text-stone-500">A quick explanation of each stage in plain language.</div>
                            </div>
                            <button
                                type="button"
                                x-on:click="stageGuideOpen = false"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 bg-white text-sm font-semibold text-stone-600 transition hover:bg-stone-50"
                                aria-label="Close stage guide"
                            >
                                ×
                            </button>
                        </div>

                        <div class="mt-6 grid gap-3">
                            @foreach ($workflowTracker as $step)
                                <div class="rounded-2xl border border-stone-200 bg-stone-50 px-4 py-4">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <div class="text-sm font-semibold text-stone-950">{{ $step['label'] }}</div>
                                        <span class="rounded-full bg-white px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">
                                            {{ strtoupper($step['state']) }}
                                        </span>
                                    </div>
                                    <div class="mt-2 text-sm leading-6 text-stone-600">{{ $step['description'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-8 flex flex-wrap gap-2 border-b border-stone-200 pb-4">
                @foreach ([
                    'overview' => 'Overview',
                    'planning' => 'Planning',
                    'crew' => 'Crew',
                    'equipment' => 'Equipment',
                    'operational' => $operationalDocumentLabelPlural,
                    'verification' => 'Accounts Review',
                    'billing' => 'Billing',
                    'documents' => 'Documents',
                    'activity' => 'Activity',
                    'notes' => 'Notes',
                ] as $key => $label)
                    @continue($key === 'billing' && ! $showBillingTab)
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
                    @if (! $planningReady && $jobStatus === JobStatus::Draft)
                        <div class="rounded-3xl border border-amber-200 bg-amber-50 p-6">
                            <div class="text-lg font-semibold text-stone-950">Planning incomplete</div>
                            <div class="mt-2 text-sm text-stone-600">Complete these items before scheduling:</div>
                            <ul class="mt-3 space-y-1 text-sm text-stone-700">
                                @foreach ($planningMissingLabels as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid gap-6 xl:grid-cols-[1.15fr,0.85fr]">
                        <div class="rounded-3xl border border-stone-200 bg-white p-6">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <div class="text-lg font-semibold text-stone-950">{{ $operationalDocumentLabelPlural }}</div>
                                    <div class="mt-1 text-sm text-stone-500">
                                        {{ $isTrucking ? 'Waybills remain the trucking execution record.' : 'Client-issued Job Cards remain the execution evidence for heavy machinery work.' }}
                                    </div>
                                </div>
                                <a href="{{ $isTrucking ? $waybillsBrowseUrl : $jobCardsBrowseUrl }}" class="rounded-full bg-stone-950 px-4 py-2 text-sm font-medium text-white">
                                    Browse all {{ strtolower($operationalDocumentLabelPlural) }}
                                </a>
                            </div>

                            @if ($isTrucking)
                                @if ($activeWaybill)
                                    <div class="mt-6 rounded-2xl border border-stone-200 bg-stone-50 px-5 py-5">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <div class="text-sm font-semibold text-stone-950">{{ $activeWaybill->waybill_number ?? 'Waybill' }}</div>
                                            <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide {{ $pillTone((string) $activeWaybill->getRawOriginal('status')) }}">
                                                {{ $activeWaybill->status?->label() ?? 'Recorded' }}
                                            </span>
                                        </div>
                                        <div class="mt-3 grid gap-3 md:grid-cols-2 xl:grid-cols-4 text-sm text-stone-600">
                                            <div>{{ $activeWaybill->waybill_date?->format('D, j M Y') ?? 'No date' }}</div>
                                            <div>{{ $activeWaybill->driver_name ?? 'No driver recorded' }}</div>
                                            <div>{{ $activeWaybill->pickup_point ?? 'No pickup recorded' }}</div>
                                            <div>{{ $activeWaybill->destination ?? 'No destination recorded' }}</div>
                                        </div>
                                    </div>
                                @elseif ($jobStatus === JobStatus::Scheduled)
                                    <div class="mt-6 rounded-3xl border border-dashed border-blue-200 bg-blue-50 px-5 py-6">
                                        <div class="text-lg font-semibold text-stone-950">No Waybill exists yet</div>
                                        <div class="mt-2 text-sm leading-6 text-stone-600">Starting this Job will move it into In Progress so the first Waybill can be recorded from live operations.</div>
                                        <div class="mt-4">
                                            <button type="button" wire:click="runWorkflowAction('start')" class="inline-flex items-center rounded-full bg-stone-950 px-4 py-2 text-sm font-semibold text-white hover:bg-stone-800">
                                                Start Job
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-6 rounded-2xl border border-dashed border-stone-300 px-5 py-8 text-sm text-stone-500">
                                        No Waybills have been recorded for this Job yet.
                                    </div>
                                @endif
                            @else
                                @if ($activeCard)
                                    <div class="mt-6 rounded-2xl border border-stone-200 bg-stone-50 px-5 py-5">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <div class="text-sm font-semibold text-stone-950">{{ $activeCard->card_number ?? 'Client Job Card' }}</div>
                                            <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide {{ $pillTone((string) $activeCard->getRawOriginal('approval_status')) }}">
                                                {{ $activeCard->approval_status?->label() ?? 'Recorded' }}
                                            </span>
                                        </div>
                                        <div class="mt-3 grid gap-3 md:grid-cols-2 xl:grid-cols-4 text-sm text-stone-600">
                                            <div>{{ $activeCard->card_date?->format('D, j M Y') ?? 'No date' }}</div>
                                            <div>{{ $activeCard->operatorDisplayName() ?? 'No operator recorded' }}</div>
                                            <div>{{ $activeCard->machine_number ?: ($activeCard->equipment_reference ?: 'No machine recorded') }}</div>
                                            <div>{{ number_format((float) $activeCard->total_hours, 2) }} total hours</div>
                                        </div>
                                        <div class="mt-4">
                                            <a href="{{ JobCardResource::getUrl(in_array((string) $activeCard->getRawOriginal('approval_status'), ['pending_verification', 'submitted', 'billing_ready'], true) ? 'view' : 'edit', ['record' => $activeCard]) }}" class="inline-flex items-center rounded-full bg-stone-950 px-4 py-2 text-sm font-semibold text-white hover:bg-stone-800">
                                                {{ $primaryNextAction['label'] ?? 'View Client Job Card' }}
                                            </a>
                                        </div>
                                    </div>
                                @elseif ($jobStatus === JobStatus::Scheduled)
                                    <div class="mt-6 rounded-3xl border border-dashed border-blue-200 bg-blue-50 px-5 py-6">
                                        <div class="text-lg font-semibold text-stone-950">No Client Job Card exists yet</div>
                                        <div class="mt-2 text-sm leading-6 text-stone-600">Starting this Job will move it into In Progress so the first Client Job Card can be recorded from live operations.</div>
                                        <div class="mt-4">
                                            <button type="button" wire:click="runWorkflowAction('start')" class="inline-flex items-center rounded-full bg-stone-950 px-4 py-2 text-sm font-semibold text-white hover:bg-stone-800">
                                                Start Job
                                            </button>
                                        </div>
                                    </div>
                                @elseif ($jobStatus === JobStatus::InProgress)
                                    <div class="mt-6 rounded-3xl border border-dashed border-amber-200 bg-amber-50 px-5 py-6">
                                        <div class="text-lg font-semibold text-stone-950">No active Client Job Card is open</div>
                                        <div class="mt-2 text-sm leading-6 text-stone-600">This Job is already in progress, but no active Client Job Card is available. Record the next Client Job Card from live operations to continue safely.</div>
                                        <div class="mt-4">
                                            <a href="{{ JobCardResource::getUrl('create', ['job' => $job]) }}" class="inline-flex items-center rounded-full bg-stone-950 px-4 py-2 text-sm font-semibold text-white hover:bg-stone-800">
                                                Record Client Job Card
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-6 rounded-2xl border border-dashed border-stone-300 px-5 py-8 text-sm text-stone-500">
                                        No Client Job Cards have been created for this Job yet.
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="space-y-6">
                            <div class="rounded-3xl border border-stone-200 bg-white p-6">
                                <div class="text-lg font-semibold text-stone-950">Workspace summary</div>
                                <div class="mt-5 space-y-4">
                                    <div class="rounded-2xl bg-stone-50 p-4">
                                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Planning</div>
                                        <div class="mt-2 text-sm font-semibold text-stone-950">{{ $planningReady ? 'Ready for deployment' : 'Planning incomplete' }}</div>
                                        <div class="mt-1 text-sm text-stone-500">{{ $planningSummary }}</div>
                                    </div>
                                    <div class="rounded-2xl bg-stone-50 p-4">
                                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Accounts review attention</div>
                                        <div class="mt-2 text-sm font-semibold text-stone-950">
                                            @if ($pendingVerificationCount > 0)
                                                {{ $pendingVerificationCount }} {{ $operationalDocumentLabel }}{{ $pendingVerificationCount === 1 ? '' : 's' }} awaiting Accounts review
                                            @elseif ($returnedCount > 0)
                                                {{ $returnedCount }} returned {{ $operationalDocumentLabel }}{{ $returnedCount === 1 ? '' : 's' }} require operational correction
                                            @else
                                                No immediate Accounts review bottlenecks
                                            @endif
                                        </div>
                                    </div>
                                    <div class="rounded-2xl bg-stone-50 p-4">
                                        <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Billing basis</div>
                                        <div class="mt-2 text-sm font-semibold text-stone-950">
                                            @if ($showBillingTab)
                                                {{ $billingSummary['billing_ready_cards'] }} billing-ready card{{ (int) $billingSummary['billing_ready_cards'] === 1 ? '' : 's' }}
                                            @else
                                                {{ $billingReadyCount }} billing-ready Waybill{{ $billingReadyCount === 1 ? '' : 's' }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-3xl border border-stone-200 bg-white p-6">
                                <div class="text-lg font-semibold text-stone-950">Safe controls</div>
                                <div class="mt-4 flex flex-wrap gap-3">
                                    @if ($deleteEligible)
                                        <button type="button" wire:click="$parent.mountAction('deleteDraft')" class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-100">
                                            Delete Job
                                        </button>
                                    @endif
                                    @if ($canCancel)
                                        <button type="button" wire:click="$parent.mountAction('cancel')" class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-100">
                                            Cancel Job
                                        </button>
                                    @endif
                                    @if (! $deleteEligible && ! $canCancel)
                                        <div class="text-sm text-stone-500">No destructive workflow action is currently available.</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'planning'" class="rounded-3xl border border-stone-200 bg-white p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="text-lg font-semibold text-stone-950">Planning record</div>
                            <div class="mt-1 text-sm text-stone-500">Draft Jobs remain editable so the operational record can be corrected before deployment.</div>
                        </div>
                        @if ($canEditPlanning)
                            <a href="{{ \App\Core\Administration\Filament\Resources\Jobs\JobResource::getUrl('edit', ['record' => $job]) }}" class="rounded-full bg-stone-950 px-4 py-2 text-sm font-medium text-white">
                                {{ $jobStatus === JobStatus::Draft ? 'Edit Job' : 'View Planning' }}
                            </a>
                        @endif
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($planningChecklist as $item)
                            <div class="rounded-2xl border {{ $item['complete'] ? 'border-emerald-200 bg-emerald-50' : 'border-amber-200 bg-amber-50' }} p-4">
                                <div class="text-[11px] font-semibold uppercase tracking-[0.18em] {{ $item['complete'] ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ $item['complete'] ? 'Ready' : 'Missing' }}
                                </div>
                                <div class="mt-2 text-sm font-semibold text-stone-950">{{ $item['label'] }}</div>
                            </div>
                        @endforeach
                    </div>

                    <dl class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        <div class="rounded-2xl bg-stone-50 p-4"><dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Requested date</dt><dd class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->requested_start_date?->format('j M Y') ?? 'Not set' }}</dd></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Planned start</dt><dd class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->planned_start_date?->format('j M Y') ?? 'Not set' }} {{ $job?->planned_start_time ?? '' }}</dd></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Planned end</dt><dd class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->planned_end_date?->format('j M Y') ?? 'Not set' }} {{ $job?->planned_end_time ?? '' }}</dd></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Vessel</dt><dd class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->vessel ?? 'Not set' }}</dd></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Work area</dt><dd class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->work_area ?? 'Not set' }}</dd></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><dt class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Estimated value</dt><dd class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->estimated_value ? strtoupper((string) $job->currency).' '.number_format((float) $job->estimated_value, 2) : 'Not estimated' }}</dd></div>
                    </dl>
                </div>

                <div x-show="tab === 'crew'" class="rounded-3xl border border-stone-200 bg-white p-6">
                    <div class="text-lg font-semibold text-stone-950">Crew</div>
                    <div class="mt-1 text-sm text-stone-500">Operators are recorded deliberately and are never inferred from the authenticated Atlas user.</div>
                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl bg-stone-50 p-4">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Planned operator</div>
                            <div class="mt-2 text-sm font-semibold text-stone-950">{{ $plannedOperatorName ?? 'No operator assigned yet' }}</div>
                        </div>
                        <div class="rounded-2xl bg-stone-50 p-4">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Latest recorded operator</div>
                            <div class="mt-2 text-sm font-semibold text-stone-950">{{ $latestCardOperatorName ?? 'No Client Job Card operator recorded yet' }}</div>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'equipment'" class="rounded-3xl border border-stone-200 bg-white p-6">
                    <div class="text-lg font-semibold text-stone-950">Equipment</div>
                    <div class="mt-1 text-sm text-stone-500">The planning requirement stays visible beside what was actually recorded on the operational document.</div>
                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl bg-stone-50 p-4">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Required equipment</div>
                            <div class="mt-2 text-sm font-semibold text-stone-950">{{ $job?->equipment_requirement ?? 'Not set' }}</div>
                        </div>
                        <div class="rounded-2xl bg-stone-50 p-4">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Latest executed equipment</div>
                            <div class="mt-2 text-sm font-semibold text-stone-950">{{ $isTrucking ? ($latestWaybill?->truck_number ?? 'No Waybill yet') : ($latestCard?->equipment_reference ?? 'No Client Job Card yet') }}</div>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'operational'" class="rounded-3xl border border-stone-200 bg-white p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="text-lg font-semibold text-stone-950">{{ $operationalDocumentLabelPlural }}</div>
                            <div class="mt-1 text-sm text-stone-500">
                                {{ $isTrucking ? 'Each Waybill captures one trucking execution record and its billing readiness.' : 'Each client-issued Job Card captures the actual work evidence that supports Accounts review and billing.' }}
                            </div>
                        </div>
                        <a href="{{ $isTrucking ? $waybillsBrowseUrl : $jobCardsBrowseUrl }}" class="rounded-full bg-stone-950 px-4 py-2 text-sm font-medium text-white">
                            Browse all {{ strtolower($operationalDocumentLabelPlural) }}
                        </a>
                    </div>

                    <div class="mt-6 space-y-3">
                        @if ($isTrucking)
                            @forelse ($waybills as $waybill)
                                <a href="{{ WaybillResource::getUrl('view', ['record' => $waybill]) }}" class="block rounded-2xl border border-stone-200 bg-stone-50 px-4 py-4 transition hover:border-stone-300 hover:bg-white">
                                    <div class="flex flex-wrap items-start justify-between gap-4">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <div class="text-sm font-semibold text-stone-950">{{ $waybill->waybill_number ?? 'Waybill' }}</div>
                                                <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide {{ $pillTone((string) $waybill->getRawOriginal('status')) }}">
                                                    {{ $waybill->status?->label() ?? 'Recorded' }}
                                                </span>
                                            </div>
                                            <div class="mt-2 text-sm text-stone-600">{{ $waybill->waybill_date?->format('D, j M Y') ?? 'No date' }} · {{ $waybill->driver_name ?? 'No driver recorded' }}</div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="rounded-2xl border border-dashed border-stone-300 px-5 py-8 text-sm text-stone-500">No Waybills have been recorded for this Job yet.</div>
                            @endforelse
                        @else
                            @forelse ($jobCards as $card)
                                <a href="{{ JobCardResource::getUrl('view', ['record' => $card]) }}" class="block rounded-2xl border border-stone-200 bg-stone-50 px-4 py-4 transition hover:border-stone-300 hover:bg-white">
                                    <div class="flex flex-wrap items-start justify-between gap-4">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <div class="text-sm font-semibold text-stone-950">{{ $card->card_number ?? 'Client Job Card' }}</div>
                                                <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide {{ $pillTone((string) $card->getRawOriginal('approval_status')) }}">
                                                    {{ $card->approval_status?->label() ?? 'Recorded' }}
                                                </span>
                                            </div>
                                            <div class="mt-2 text-sm text-stone-600">{{ $card->card_date?->format('D, j M Y') ?? 'No date' }} · {{ JobShift::tryFrom((string) $card->shift)?->label() ?? (filled($card->shift) ? ucfirst((string) $card->shift) : 'Shift not set') }}</div>
                                            <div class="mt-2 grid gap-2 text-sm text-stone-500 md:grid-cols-2 xl:grid-cols-4">
                                                <div>{{ $card->operatorDisplayName() ?? 'No operator assigned' }}</div>
                                                <div>{{ $card->machine_number ?: ($card->equipment_reference ?: 'No machine recorded') }}</div>
                                                <div>{{ $card->workEntries->first()?->vessel ?? $job?->vessel ?? 'No vessel recorded' }}</div>
                                                <div>{{ number_format((float) $card->total_hours, 2) }} total hours</div>
                                            </div>
                                        </div>
                                        <div class="text-right text-sm font-semibold text-stone-700">Commercial snapshot available in Billing Batch</div>
                                    </div>
                                </a>
                            @empty
                                <div class="rounded-2xl border border-dashed border-stone-300 px-5 py-8 text-sm text-stone-500">No Client Job Cards have been recorded for this Job yet.</div>
                            @endforelse
                        @endif
                    </div>
                </div>

                <div x-show="tab === 'verification'" class="rounded-3xl border border-stone-200 bg-white p-6">
                    <div class="text-lg font-semibold text-stone-950">Accounts Review</div>
                    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Recorded</div><div class="mt-2 text-2xl font-semibold text-stone-950">{{ $recordedCount }}</div></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Awaiting Accounts Review</div><div class="mt-2 text-2xl font-semibold text-stone-950">{{ $pendingVerificationCount }}</div></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Accounts Reviewed</div><div class="mt-2 text-2xl font-semibold text-stone-950">{{ $verifiedCount }}</div></div>
                        <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Billing ready</div><div class="mt-2 text-2xl font-semibold text-stone-950">{{ $billingReadyCount }}</div></div>
                    </div>
                </div>

                @if ($showBillingTab)
                    <div x-show="tab === 'billing'" class="rounded-3xl border border-stone-200 bg-white p-6">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <div class="text-lg font-semibold text-stone-950">Billing</div>
                                <div class="mt-1 text-sm text-stone-500">Commercial values below are immutable snapshots from Billing Batch preparation. Invoice generation is not included here.</div>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                            <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Client Job Cards</div><div class="mt-2 text-2xl font-semibold text-stone-950">{{ $billingSummary['job_card_count'] }}</div></div>
                            <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Total hours</div><div class="mt-2 text-2xl font-semibold text-stone-950">{{ $billingSummary['total_hours'] }}</div></div>
                            <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Accounts-reviewed cards</div><div class="mt-2 text-2xl font-semibold text-stone-950">{{ $billingSummary['verified_cards'] }}</div></div>
                            <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Billing-ready cards</div><div class="mt-2 text-2xl font-semibold text-stone-950">{{ $billingSummary['billing_ready_cards'] }}</div></div>
                            <div class="rounded-2xl bg-stone-50 p-4"><div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">Commercial snapshots</div><div class="mt-2 text-2xl font-semibold text-stone-950">{{ $billingSummary['snapshot_count'] }}</div></div>
                        </div>

                        <div class="mt-6 overflow-hidden rounded-3xl border border-stone-200">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-stone-200 text-sm">
                                    <thead class="bg-stone-50 text-left text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">
                                        <tr>
                                            <th class="px-4 py-3">Date</th>
                                            <th class="px-4 py-3">Reference</th>
                                            <th class="px-4 py-3">Machine</th>
                                            <th class="px-4 py-3">From</th>
                                            <th class="px-4 py-3">To</th>
                                            <th class="px-4 py-3">Hours</th>
                                            <th class="px-4 py-3">Resolved rate</th>
                                            <th class="px-4 py-3">Amount</th>
                                            <th class="px-4 py-3">Billing Batch</th>
                                            <th class="px-4 py-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-stone-200 bg-white">
                                        @forelse ($billingRows as $row)
                                            <tr class="align-top">
                                                <td class="px-4 py-4">{{ $row['date'] ?? 'No date' }}</td>
                                                <td class="px-4 py-4">
                                                    <a href="{{ $row['url'] }}" class="font-semibold text-stone-950 hover:text-blue-700">{{ $row['reference'] ?: $row['batch_number'] }}</a>
                                                </td>
                                                <td class="px-4 py-4">{{ $row['machine_number'] ?? 'Not captured' }}</td>
                                                <td class="px-4 py-4">{{ $row['from'] ?? '-' }}</td>
                                                <td class="px-4 py-4">{{ $row['to'] ?? '-' }}</td>
                                                <td class="px-4 py-4">{{ $row['hours'] !== null ? number_format((float) $row['hours'], 2) : '-' }}</td>
                                                <td class="px-4 py-4">{{ strtoupper((string) $row['currency']) }} {{ number_format((float) $row['resolved_rate'], 2) }}/hr</td>
                                                <td class="px-4 py-4">{{ strtoupper((string) $row['currency']) }} {{ number_format((float) $row['line_amount'], 2) }}</td>
                                                <td class="px-4 py-4">{{ $row['batch_number'] }}</td>
                                                <td class="px-4 py-4">{{ $row['batch_status'] }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="px-4 py-8 text-center text-sm text-stone-500">No Billing Batch snapshots exist for this Job yet. Accounts-reviewed Work Entries can be added to a Billing Batch when Finance is ready.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <div x-show="tab === 'documents'" class="rounded-3xl border border-stone-200 bg-white p-6">
                    <div class="text-lg font-semibold text-stone-950">Operational Documents</div>
                    <div class="mt-1 text-sm text-stone-500">{{ $isTrucking ? 'Signed Waybills and supporting delivery documentation remain attached to each Waybill.' : 'Signed client Job Cards, endorsement evidence, worksite photos, and other supporting documents remain attached to Client Job Cards.' }}</div>
                    <div class="mt-6 rounded-2xl bg-stone-50 p-4 text-sm font-semibold text-stone-950">{{ $attachmentCount }} document(s) recorded</div>
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
                    <div class="mt-4 text-sm leading-7 text-stone-600">{{ $job?->description ?: 'No planning notes have been recorded for this Job yet.' }}</div>
                </div>
            </div>
        </div>
    </section>
</x-filament-widgets::widget>
