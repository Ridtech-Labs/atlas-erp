<x-filament-widgets::widget>
    <x-atlas-card class="p-6">
        <x-atlas.section-header
            title="Recent Clients"
            description="The latest customer accounts added or updated in the current company workspace."
            action-label="View Clients"
            :action-url="$canViewClients ? \App\Core\Administration\Filament\Resources\Clients\ClientResource::getUrl('index') : null"
        />

        <div class="mt-6">
            @if (! $canViewClients)
                <x-atlas.empty-state
                    title="Client visibility is restricted"
                    description="This role does not currently include access to client records."
                />
            @elseif ($clients->isEmpty())
                <x-atlas.empty-state
                    title="No clients yet"
                    description="Create the first client workspace to start tracking accounts, contacts, and job activity."
                >
                    @if (\App\Core\Administration\Filament\Resources\Clients\ClientResource::canCreate())
                        <x-atlas.button tag="a" :href="\App\Core\Administration\Filament\Resources\Clients\ClientResource::getUrl('create')">
                            Create first client
                        </x-atlas.button>
                    @endif
                </x-atlas.empty-state>
            @else
                <div class="overflow-x-auto">
                    <div class="min-w-full divide-y divide-[var(--atlas-color-border-muted)]">
                        @foreach ($clients as $client)
                            <a
                                href="{{ \App\Core\Administration\Filament\Resources\Clients\ClientResource::getUrl('view', ['record' => $client]) }}"
                                class="flex min-w-[42rem] items-start justify-between gap-4 py-4 transition first:pt-0 last:pb-0 hover:bg-[var(--atlas-color-background-muted)]/50"
                            >
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="truncate text-sm font-semibold text-[var(--atlas-color-text-primary)]">{{ $client->display_name }}</p>
                                        <x-atlas.badge :status="$client->status->color()">{{ $client->status->label() }}</x-atlas.badge>
                                    </div>

                                    <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-[var(--atlas-color-text-muted)]">
                                        <span class="font-mono">{{ $client->client_code }}</span>
                                        <span>{{ $client->city ?: 'No city set' }}</span>
                                        <span>{{ $client->country ?: 'No country set' }}</span>
                                    </div>
                                </div>

                                <div class="shrink-0 text-right text-xs text-[var(--atlas-color-text-secondary)]">
                                    <div>{{ $client->jobs_count }} jobs</div>
                                    <div class="mt-1">{{ $client->contacts_count }} contacts</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </x-atlas-card>
</x-filament-widgets::widget>
