<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Foundation Dashboard') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Sprint 0 shell for tenant-aware operations, access control, notifications, health checks, and platform settings.
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-3xl bg-stone-900 p-6 text-white shadow-sm">
                    <p class="text-xs uppercase tracking-[0.2em] text-amber-300">Current Tenant</p>
                    <h3 class="mt-4 text-2xl font-semibold">{{ $currentTenant?->name ?? 'Platform' }}</h3>
                    <p class="mt-2 text-sm text-stone-300">{{ $currentTenant?->timezone ?? config('app.timezone') }} · {{ $currentTenant?->currency ?? 'N/A' }}</p>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
                    <p class="text-xs uppercase tracking-[0.2em] text-stone-500">Current User</p>
                    <h3 class="mt-4 text-2xl font-semibold text-stone-900">{{ auth()->user()->full_name }}</h3>
                    <p class="mt-2 text-sm text-stone-500">{{ auth()->user()->email }}</p>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
                    <p class="text-xs uppercase tracking-[0.2em] text-stone-500">Platform Status</p>
                    <h3 class="mt-4 text-2xl font-semibold text-stone-900">{{ config('app.version') }}</h3>
                    <p class="mt-2 text-sm text-stone-500">Filament, Breeze, Spatie tooling, queues, and health monitoring are wired for Sprint 0.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
