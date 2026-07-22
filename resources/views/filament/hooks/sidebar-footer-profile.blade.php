@php
    $user = auth()->user();
    $role = $user?->roles->first()?->name ?? 'Workspace User';
    $initials = $user ? collect([$user->first_name, $user->last_name])->filter()->map(fn (string $part): string => str($part)->substr(0, 1)->upper())->implode('') : 'AT';
    $initials = $initials !== '' ? $initials : 'AT';
@endphp

@if ($user)
    <div class="rounded-[var(--atlas-radius-lg)] border border-[var(--atlas-color-border-sidebar)] bg-[rgba(255,255,255,0.02)] p-3 text-white">
        <div class="flex items-center gap-3">
            <span class="atlas-avatar h-10 w-10">{{ $initials }}</span>

            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-white">{{ $user->full_name }}</p>
                <p class="truncate text-xs text-[var(--atlas-color-text-sidebar)]">{{ $role }}</p>
            </div>
        </div>
    </div>
@endif
