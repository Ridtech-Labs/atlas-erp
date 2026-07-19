<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        <x-filament::section heading="General">
            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-medium">Company display name</span>
                    <input wire:model="general.name" type="text" class="mt-1 w-full rounded-lg border-gray-300" />
                </label>
                <label class="block">
                    <span class="text-sm font-medium">Timezone</span>
                    <input wire:model="general.timezone" type="text" class="mt-1 w-full rounded-lg border-gray-300" />
                </label>
                <label class="block">
                    <span class="text-sm font-medium">Currency</span>
                    <input wire:model="general.currency" type="text" class="mt-1 w-full rounded-lg border-gray-300" />
                </label>
                <label class="block">
                    <span class="text-sm font-medium">Country</span>
                    <input wire:model="general.country" type="text" class="mt-1 w-full rounded-lg border-gray-300" />
                </label>
                <label class="block">
                    <span class="text-sm font-medium">Date format</span>
                    <input wire:model="general.date_format" type="text" class="mt-1 w-full rounded-lg border-gray-300" />
                </label>
                <label class="block">
                    <span class="text-sm font-medium">Time format</span>
                    <input wire:model="general.time_format" type="text" class="mt-1 w-full rounded-lg border-gray-300" />
                </label>
                <label class="block">
                    <span class="text-sm font-medium">Default language</span>
                    <input wire:model="general.language" type="text" class="mt-1 w-full rounded-lg border-gray-300" />
                </label>
            </div>
        </x-filament::section>

        <x-filament::section heading="Branding">
            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-medium">Primary brand preference</span>
                    <input wire:model="branding.primary_brand_preference" type="text" class="mt-1 w-full rounded-lg border-gray-300" />
                </label>
            </div>
        </x-filament::section>

        <x-filament::section heading="Notifications">
            <div class="space-y-3">
                <label class="flex items-center gap-3">
                    <input wire:model="notifications.email_notifications_enabled" type="checkbox" />
                    <span class="text-sm font-medium">Email notifications enabled</span>
                </label>
                <label class="flex items-center gap-3">
                    <input wire:model="notifications.database_notifications_enabled" type="checkbox" />
                    <span class="text-sm font-medium">Database notifications enabled</span>
                </label>
            </div>
        </x-filament::section>

        <x-filament::button type="submit">
            Save settings
        </x-filament::button>
    </form>
</x-filament-panels::page>
