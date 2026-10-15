<x-filament-widgets::widget>
    @if ($overview !== null)
        <div class="space-y-6">
            <x-atlas.section-header
                title="Inventory Overview"
                description="Current company stock position and the latest immutable ledger activity."
            />

            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    'active_items' => ['Total Active Items', 'primary'],
                    'in_stock_items' => ['In Stock Items', 'success'],
                    'low_stock_items' => ['Low Stock Items', 'warning'],
                    'out_of_stock_items' => ['Out of Stock Items', 'danger'],
                ] as $key => [$label, $color])
                    <x-atlas-card class="p-4">
                        <div class="text-xs font-semibold uppercase tracking-wide text-[var(--atlas-color-text-muted)]">{{ $label }}</div>
                        <div @class([
                            'mt-2 text-3xl font-bold',
                            'text-[var(--atlas-color-primary)]' => $color === 'primary',
                            'text-emerald-700' => $color === 'success',
                            'text-amber-700' => $color === 'warning',
                            'text-red-700' => $color === 'danger',
                        ])>{{ $overview['summary'][$key] }}</div>
                    </x-atlas-card>
                @endforeach
            </div>

            <div class="grid gap-6 xl:grid-cols-2">
                <x-atlas-card class="overflow-hidden">
                    <div class="border-b border-stone-200 px-5 py-4">
                        <h3 class="font-semibold text-stone-950">Low-stock attention</h3>
                        <p class="mt-1 text-sm text-stone-500">Active items at or below their minimum level.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-stone-50 text-xs uppercase tracking-wide text-stone-500">
                                <tr><th class="px-5 py-3">Item code</th><th class="px-5 py-3">Item name</th><th class="px-5 py-3">Category</th><th class="px-5 py-3 text-right">On hand</th><th class="px-5 py-3 text-right">Minimum</th><th class="px-5 py-3">Unit</th><th class="px-5 py-3">Status</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($overview['attention_items'] as $item)
                                    @php($status = $item->stockStatus())
                                    <tr class="border-t border-stone-100">
                                        <td class="px-5 py-3"><a href="{{ \App\Core\Administration\Filament\Resources\InventoryItems\InventoryItemResource::getUrl('view', ['record' => $item]) }}" class="font-semibold text-[var(--atlas-color-primary)] hover:underline">{{ $item->code }}</a></td>
                                        <td class="px-5 py-3 text-stone-600">{{ $item->name }}</td>
                                        <td class="px-5 py-3 text-stone-600">{{ $item->category ?: 'Uncategorized' }}</td>
                                        <td class="px-5 py-3 text-right font-medium">{{ number_format((float) $item->quantity_on_hand, 3) }}</td>
                                        <td class="px-5 py-3 text-right text-stone-600">{{ number_format((float) $item->minimum_stock_level, 3) }}</td>
                                        <td class="px-5 py-3 text-stone-600">{{ $item->unit_of_measure->value }}</td>
                                        <td class="px-5 py-3"><span @class(['inline-flex rounded-full px-2 py-1 text-xs font-semibold', 'bg-red-50 text-red-700' => $status->value === 'out_of_stock', 'bg-amber-50 text-amber-700' => $status->value === 'low_stock'])>{{ $status->label() }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="px-5 py-8 text-center text-stone-500">No active Inventory items need attention.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-atlas-card>

                <x-atlas-card class="overflow-hidden">
                    <div class="border-b border-stone-200 px-5 py-4">
                        <h3 class="font-semibold text-stone-950">Recent stock movements</h3>
                        <p class="mt-1 text-sm text-stone-500">The 10 latest immutable quantity changes.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-stone-50 text-xs uppercase tracking-wide text-stone-500">
                                <tr><th class="px-5 py-3">Date / time</th><th class="px-5 py-3">Item</th><th class="px-5 py-3">Movement</th><th class="px-5 py-3 text-right">Change</th><th class="px-5 py-3 text-right">Balance</th><th class="px-5 py-3">Reference</th><th class="px-5 py-3">Performed by</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($overview['recent_movements'] as $movement)
                                    <tr class="border-t border-stone-100">
                                        <td class="whitespace-nowrap px-5 py-3 text-stone-600">{{ $movement->occurred_at?->format('d M Y, H:i') }}</td>
                                        <td class="px-5 py-3"><div class="font-semibold">{{ $movement->item?->code }}</div><div class="mt-0.5 text-stone-600">{{ $movement->item?->name }}</div></td>
                                        <td class="px-5 py-3">{{ $movement->movement_type->label() }}</td>
                                        <td @class(['whitespace-nowrap px-5 py-3 text-right font-semibold', 'text-emerald-700' => $movement->movement_type->increasesStock(), 'text-red-700' => ! $movement->movement_type->increasesStock()])>{{ $movement->movement_type->increasesStock() ? '+' : '-' }}{{ number_format((float) $movement->quantity, 3) }}</td>
                                        <td class="whitespace-nowrap px-5 py-3 text-right">{{ number_format((float) $movement->quantity_after, 3) }}</td>
                                        <td class="px-5 py-3 text-stone-600">{{ $movement->reference ?: '—' }}</td>
                                        <td class="px-5 py-3 text-stone-600">{{ $movement->performer ? trim("{$movement->performer->first_name} {$movement->performer->last_name}") : 'System' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="px-5 py-8 text-center text-stone-500">No stock movements have been recorded for this company.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-atlas-card>
            </div>
        </div>
    @endif
</x-filament-widgets::widget>
