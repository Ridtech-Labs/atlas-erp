<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="max-w-3xl">
                <h2 class="text-lg font-semibold text-stone-900">Workspace settings</h2>
                <p class="mt-2 text-sm leading-6 text-stone-600">
                    Shape how {{ auth()->user()?->tenant?->name ?? 'your company' }} appears to internal teams. These controls affect branding, localisation, and notification defaults.
                </p>
            </div>
        </section>

        <x-filament::section heading="Company profile" description="Keep business-facing details accurate across the workspace.">
            <div class="grid gap-5 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-medium text-stone-800">Company display name</span>
                    <input wire:model="general.name" type="text" class="mt-1.5 w-full rounded-2xl border-stone-300 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500" />
                    <p class="mt-1.5 text-xs text-stone-500">Used in workspace headers and internal references.</p>
                </label>
                <label class="block">
                    <span class="text-sm font-medium text-stone-800">Timezone</span>
                    <input wire:model="general.timezone" type="text" class="mt-1.5 w-full rounded-2xl border-stone-300 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500" />
                    <p class="mt-1.5 text-xs text-stone-500">Determines scheduling, reminders, and timestamps.</p>
                </label>
                <label class="block">
                    <span class="text-sm font-medium text-stone-800">Currency</span>
                    <input wire:model="general.currency" type="text" class="mt-1.5 w-full rounded-2xl border-stone-300 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500" />
                </label>
                <label class="block">
                    <span class="text-sm font-medium text-stone-800">Country</span>
                    <input wire:model="general.country" type="text" class="mt-1.5 w-full rounded-2xl border-stone-300 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500" />
                </label>
                <label class="block">
                    <span class="text-sm font-medium text-stone-800">Date format</span>
                    <input wire:model="general.date_format" type="text" class="mt-1.5 w-full rounded-2xl border-stone-300 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500" />
                </label>
                <label class="block">
                    <span class="text-sm font-medium text-stone-800">Time format</span>
                    <input wire:model="general.time_format" type="text" class="mt-1.5 w-full rounded-2xl border-stone-300 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500" />
                </label>
                <label class="block md:col-span-2">
                    <span class="text-sm font-medium text-stone-800">Default language</span>
                    <input wire:model="general.language" type="text" class="mt-1.5 w-full rounded-2xl border-stone-300 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500" />
                </label>
            </div>
        </x-filament::section>

        <x-filament::section heading="Branding" description="Set the visual defaults for the current workspace.">
            <div class="grid gap-5 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-medium text-stone-800">Primary brand preference</span>
                    <input wire:model="branding.primary_brand_preference" type="text" class="mt-1.5 w-full rounded-2xl border-stone-300 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500" />
                    <p class="mt-1.5 text-xs text-stone-500">Used to guide future workspace theming and exported assets.</p>
                </label>
            </div>
        </x-filament::section>

        <x-filament::section heading="Notifications" description="Decide how operational updates should reach your team by default.">
            <div class="grid gap-4 md:grid-cols-2">
                <label class="flex items-start gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-4">
                    <input wire:model="notifications.email_notifications_enabled" type="checkbox" class="mt-1 rounded border-stone-300 text-amber-600 focus:ring-amber-500" />
                    <div>
                        <div class="text-sm font-medium text-stone-800">Email notifications</div>
                        <p class="mt-1 text-xs text-stone-500">Send important workflow and approval events by email.</p>
                    </div>
                </label>
                <label class="flex items-start gap-3 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-4">
                    <input wire:model="notifications.database_notifications_enabled" type="checkbox" class="mt-1 rounded border-stone-300 text-amber-600 focus:ring-amber-500" />
                    <div>
                        <div class="text-sm font-medium text-stone-800">In-app notifications</div>
                        <p class="mt-1 text-xs text-stone-500">Show alerts and reminders directly inside Atlas ERP.</p>
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
