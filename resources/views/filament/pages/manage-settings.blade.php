<x-filament-panels::page>
    <form wire:submit="save" class="atlas-settings-form space-y-6">
        <section class="atlas-card p-6">
            <div class="max-w-3xl">
                <h2 class="text-lg font-semibold text-[var(--atlas-color-text-primary)]">Workspace settings</h2>
                <p class="mt-2 text-sm leading-6 text-[var(--atlas-color-text-secondary)]">
                    Configure localisation and notification defaults for the active company. Company identity and tenant branding are managed separately.
                </p>
            </div>
        </section>

        <x-filament::section heading="Company preferences" description="Choose how dates, times, and language appear for the active company.">
            <div class="grid gap-6 md:grid-cols-2">
                <label class="block">
                    <span class="atlas-settings-field-label">Date format</span>
                    <input wire:model="general.date_format" type="text" class="atlas-settings-control" aria-invalid="{{ $errors->has('general.date_format') ? 'true' : 'false' }}" />
                    @error('general.date_format') <p class="atlas-settings-field-error">{{ $message }}</p> @enderror
                </label>
                <label class="block">
                    <span class="atlas-settings-field-label">Time format</span>
                    <input wire:model="general.time_format" type="text" class="atlas-settings-control" aria-invalid="{{ $errors->has('general.time_format') ? 'true' : 'false' }}" />
                    @error('general.time_format') <p class="atlas-settings-field-error">{{ $message }}</p> @enderror
                </label>
                <label class="block">
                    <span class="atlas-settings-field-label">Default language</span>
                    <input wire:model="general.language" type="text" class="atlas-settings-control" aria-invalid="{{ $errors->has('general.language') ? 'true' : 'false' }}" />
                    @error('general.language') <p class="atlas-settings-field-error">{{ $message }}</p> @enderror
                </label>
            </div>
        </x-filament::section>

        <x-filament::section heading="Notifications" description="Decide how operational updates should reach your team by default.">
            <div class="grid gap-6 md:grid-cols-2">
                <label class="flex items-start gap-3 rounded-[var(--atlas-radius-lg)] border border-[var(--atlas-color-border-default)] bg-[var(--atlas-color-background-muted)] px-4 py-4">
                    <input wire:model="notifications.email_notifications_enabled" type="checkbox" class="atlas-settings-checkbox mt-1" />
                    <div>
                        <div class="text-sm font-medium text-[var(--atlas-color-text-primary)]">Email notifications</div>
                        <p class="mt-1 text-xs text-[var(--atlas-color-text-muted)]">Send important workflow and approval events by email.</p>
                    </div>
                </label>
                <label class="flex items-start gap-3 rounded-[var(--atlas-radius-lg)] border border-[var(--atlas-color-border-default)] bg-[var(--atlas-color-background-muted)] px-4 py-4">
                    <input wire:model="notifications.database_notifications_enabled" type="checkbox" class="atlas-settings-checkbox mt-1" />
                    <div>
                        <div class="text-sm font-medium text-[var(--atlas-color-text-primary)]">In-app notifications</div>
                        <p class="mt-1 text-xs text-[var(--atlas-color-text-muted)]">Show alerts and reminders directly inside Atlas ERP.</p>
                    </div>
                </label>
            </div>
        </x-filament::section>

        <div class="flex justify-end">
            <x-filament::button type="submit" size="lg">
                Save workspace settings
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
