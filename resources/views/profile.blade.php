<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-[var(--atlas-color-text-primary)]">
                {{ __('Profile') }}
            </h2>
            <p class="mt-2 text-sm text-[var(--atlas-color-text-secondary)]">Manage your identity, account security, and access preferences.</p>
        </div>
    </x-slot>

    <div class="atlas-shell-container py-6">
        <div class="space-y-6">
            <div class="atlas-card p-4 sm:p-8">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="atlas-card p-4 sm:p-8">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="atlas-card p-4 sm:p-8">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
