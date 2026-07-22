<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        <section class="atlas-card p-6">
            <div class="max-w-3xl">
                <h2 class="text-lg font-semibold text-[var(--atlas-color-text-primary)]">Workspace settings</h2>
                <p class="mt-2 text-sm leading-6 text-[var(--atlas-color-text-secondary)]">
                    Shape how {{ auth()->user()?->tenant?->name ?? 'your company' }} appears to internal teams. These controls affect branding, localisation, and notification defaults.
                </p>
            </div>
        </section>

        <x-filament::section heading="Company profile" description="Keep business-facing details accurate across the workspace.">
            <div class="grid gap-5 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-medium text-[var(--atlas-color-text-secondary)]">Company display name</span>
                    <input wire:model="general.name" type="text" class="mt-1.5 w-full rounded-[var(--atlas-radius-sm)] border-[var(--atlas-color-border-default)] text-sm shadow-sm focus:border-[var(--atlas-color-action-primary)] focus:ring-[var(--atlas-color-action-primary)]" />
                    <p class="mt-1.5 text-xs text-[var(--atlas-color-text-muted)]">Used in workspace headers and internal references.</p>
                </label>
                <label class="block">
                    <span class="text-sm font-medium text-[var(--atlas-color-text-secondary)]">Timezone</span>
                    <input wire:model="general.timezone" type="text" class="mt-1.5 w-full rounded-[var(--atlas-radius-sm)] border-[var(--atlas-color-border-default)] text-sm shadow-sm focus:border-[var(--atlas-color-action-primary)] focus:ring-[var(--atlas-color-action-primary)]" />
                    <p class="mt-1.5 text-xs text-[var(--atlas-color-text-muted)]">Determines scheduling, reminders, and timestamps.</p>
                </label>
                <label class="block">
                    <span class="text-sm font-medium text-[var(--atlas-color-text-secondary)]">Currency</span>
                    <input wire:model="general.currency" type="text" class="mt-1.5 w-full rounded-[var(--atlas-radius-sm)] border-[var(--atlas-color-border-default)] text-sm shadow-sm focus:border-[var(--atlas-color-action-primary)] focus:ring-[var(--atlas-color-action-primary)]" />
                </label>
                <label class="block">
                    <span class="text-sm font-medium text-[var(--atlas-color-text-secondary)]">Country</span>
                    <input wire:model="general.country" type="text" class="mt-1.5 w-full rounded-[var(--atlas-radius-sm)] border-[var(--atlas-color-border-default)] text-sm shadow-sm focus:border-[var(--atlas-color-action-primary)] focus:ring-[var(--atlas-color-action-primary)]" />
                </label>
                <label class="block">
                    <span class="text-sm font-medium text-[var(--atlas-color-text-secondary)]">Date format</span>
                    <input wire:model="general.date_format" type="text" class="mt-1.5 w-full rounded-[var(--atlas-radius-sm)] border-[var(--atlas-color-border-default)] text-sm shadow-sm focus:border-[var(--atlas-color-action-primary)] focus:ring-[var(--atlas-color-action-primary)]" />
                </label>
                <label class="block">
                    <span class="text-sm font-medium text-[var(--atlas-color-text-secondary)]">Time format</span>
                    <input wire:model="general.time_format" type="text" class="mt-1.5 w-full rounded-[var(--atlas-radius-sm)] border-[var(--atlas-color-border-default)] text-sm shadow-sm focus:border-[var(--atlas-color-action-primary)] focus:ring-[var(--atlas-color-action-primary)]" />
                </label>
                <label class="block md:col-span-2">
                    <span class="text-sm font-medium text-[var(--atlas-color-text-secondary)]">Default language</span>
                    <input wire:model="general.language" type="text" class="mt-1.5 w-full rounded-[var(--atlas-radius-sm)] border-[var(--atlas-color-border-default)] text-sm shadow-sm focus:border-[var(--atlas-color-action-primary)] focus:ring-[var(--atlas-color-action-primary)]" />
                </label>
            </div>
        </x-filament::section>

        <x-filament::section heading="Branding" description="Set the visual defaults for the current workspace.">
            <div class="grid gap-5 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-medium text-[var(--atlas-color-text-secondary)]">Primary brand preference</span>
                    <input wire:model="branding.primary_brand_preference" type="text" class="mt-1.5 w-full rounded-[var(--atlas-radius-sm)] border-[var(--atlas-color-border-default)] text-sm shadow-sm focus:border-[var(--atlas-color-action-primary)] focus:ring-[var(--atlas-color-action-primary)]" />
                    <p class="mt-1.5 text-xs text-[var(--atlas-color-text-muted)]">Used to guide future workspace theming and exported assets.</p>
                </label>
            </div>
        </x-filament::section>

        <x-filament::section heading="Notifications" description="Decide how operational updates should reach your team by default.">
            <div class="grid gap-4 md:grid-cols-2">
                <label class="flex items-start gap-3 rounded-[var(--atlas-radius-lg)] border border-[var(--atlas-color-border-default)] bg-[var(--atlas-color-background-muted)] px-4 py-4">
                    <input wire:model="notifications.email_notifications_enabled" type="checkbox" class="mt-1 rounded border-[var(--atlas-color-border-default)] text-[var(--atlas-color-action-primary)] focus:ring-[var(--atlas-color-action-primary)]" />
                    <div>
                        <div class="text-sm font-medium text-[var(--atlas-color-text-primary)]">Email notifications</div>
                        <p class="mt-1 text-xs text-[var(--atlas-color-text-muted)]">Send important workflow and approval events by email.</p>
                    </div>
                </label>
                <label class="flex items-start gap-3 rounded-[var(--atlas-radius-lg)] border border-[var(--atlas-color-border-default)] bg-[var(--atlas-color-background-muted)] px-4 py-4">
                    <input wire:model="notifications.database_notifications_enabled" type="checkbox" class="mt-1 rounded border-[var(--atlas-color-border-default)] text-[var(--atlas-color-action-primary)] focus:ring-[var(--atlas-color-action-primary)]" />
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
