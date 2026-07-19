<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Recent Activity
        </x-slot>

        <div class="space-y-3">
            @forelse ($activities as $activity)
                <div class="rounded-2xl border border-stone-200 px-4 py-3">
                    <div class="text-sm font-medium text-stone-800">{{ $activity->description }}</div>
                    <div class="mt-1 text-xs text-stone-500">
                        {{ $activity->created_at?->diffForHumans() }}
                        @if ($activity->causer)
                            · {{ $activity->causer->full_name ?? class_basename($activity->causer_type) }}
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-stone-300 px-4 py-6 text-sm text-stone-500">
                    No activity has been recorded yet.
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
