<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Recent activity</x-slot>
        <x-slot name="description">A live timeline of changes across your workspace, focused on the actions that move operations forward.</x-slot>

        <div class="space-y-3">
            @forelse ($activities as $activity)
                <div class="rounded-3xl border border-stone-200 bg-white px-4 py-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-600">
                                {{ str($activity->event ?? 'activity')->replace('_', ' ')->title() }}
                            </div>
                            <div class="mt-1 text-sm font-semibold text-stone-900">{{ $activity->description }}</div>
                            <div class="mt-1 text-xs uppercase tracking-[0.18em] text-stone-400">
                                {{ $activity->subject_type ? class_basename($activity->subject_type) : 'System' }}
                            </div>
                        </div>
                        <span class="shrink-0 rounded-full bg-stone-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-stone-600">
                            {{ $activity->created_at?->diffForHumans() }}
                        </span>
                    </div>
                    <div class="mt-3 text-xs text-stone-500">
                        @if ($activity->causer)
                            By {{ $activity->causer->full_name ?? class_basename($activity->causer_type) }}
                        @else
                            System activity
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-stone-300 px-4 py-8 text-sm text-stone-500">
                    Activity will appear here as your team creates clients, updates jobs, and moves work through approvals.
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
