<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Quick Navigation
        </x-slot>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('dashboard') }}" class="rounded-2xl border border-stone-200 bg-stone-50 px-4 py-5 text-sm font-medium text-stone-700 transition hover:border-amber-400 hover:bg-amber-50">
                Dashboard Shell
            </a>
            <a href="{{ route('profile') }}" class="rounded-2xl border border-stone-200 bg-stone-50 px-4 py-5 text-sm font-medium text-stone-700 transition hover:border-amber-400 hover:bg-amber-50">
                My Profile
            </a>
            <a href="{{ route('health') }}" class="rounded-2xl border border-stone-200 bg-stone-50 px-4 py-5 text-sm font-medium text-stone-700 transition hover:border-amber-400 hover:bg-amber-50">
                Health Endpoint
            </a>
            <div class="rounded-2xl border border-dashed border-stone-300 px-4 py-5 text-sm text-stone-500">
                CRM, Finance, Fleet, Inventory, and Operations modules arrive in future sprints.
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
