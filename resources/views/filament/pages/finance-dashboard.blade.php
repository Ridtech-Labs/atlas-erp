<x-filament-panels::page>
    @php($report = $this->report())

    @if (filled($report))
        <section class="atlas-card p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-[var(--atlas-color-text-primary)]">Finance overview</h2>
                    <p class="mt-1 text-sm text-[var(--atlas-color-text-secondary)]">Realized revenue uses paid or closed Billing Records and their payment dates.</p>
                </div>
                <a href="{{ \App\Core\Administration\Filament\Resources\BillingRecords\BillingRecordResource::getUrl('index') }}" class="fi-btn fi-btn-size-md fi-btn-color-primary">View Billing Records</a>
            </div>
        </section>

        <section class="atlas-card p-5">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                <label class="text-sm font-medium text-[var(--atlas-color-text-secondary)]">Client
                    <select wire:model.live="filters.client_id" class="fi-input mt-1 w-full">
                        <option value="">All clients</option>
                        @foreach ($this->clients() as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-sm font-medium text-[var(--atlas-color-text-secondary)]">Status
                    <select wire:model.live="filters.status" class="fi-input mt-1 w-full">
                        <option value="">All statuses</option>
                        @foreach ($this->statuses() as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-sm font-medium text-[var(--atlas-color-text-secondary)]">Currency
                    <select wire:model.live="filters.currency" class="fi-input mt-1 w-full">
                        <option value="">All currencies</option>
                        @foreach ($this->currencies() as $currency)
                            <option value="{{ $currency }}">{{ $currency }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-sm font-medium text-[var(--atlas-color-text-secondary)]">Reporting from
                    <input wire:model.live="filters.from" type="date" class="fi-input mt-1 w-full">
                </label>
                <label class="text-sm font-medium text-[var(--atlas-color-text-secondary)]">Reporting until
                    <input wire:model.live="filters.until" type="date" class="fi-input mt-1 w-full">
                </label>
            </div>
            <p class="mt-4 text-sm text-[var(--atlas-color-text-muted)]">Reporting dates use payment dates for paid or closed records and issue dates for unpaid records. Revenue MTD, Revenue YTD, and Paid This Month always use their fixed current-period definitions.</p>
        </section>

        <div class="grid gap-4 xl:grid-cols-4">
            @foreach ([
                ['label' => 'Revenue MTD', 'totals' => $report['revenue_mtd'], 'detail' => 'Paid or closed this month'],
                ['label' => 'Revenue YTD', 'totals' => $report['revenue_ytd'], 'detail' => 'Paid or closed this year'],
                ['label' => 'Issued / Unpaid', 'totals' => $report['issued_unpaid'], 'detail' => 'VAT receipt issued, awaiting payment in the reporting period'],
            ] as $metric)
                <x-atlas-card class="p-5">
                    <div class="text-sm font-medium text-[var(--atlas-color-text-secondary)]">{{ $metric['label'] }}</div>
                    <div class="mt-3 space-y-1 text-2xl font-bold text-[var(--atlas-color-text-primary)]">
                        @forelse ($metric['totals'] as $currency => $total)
                            <div>{{ $currency }} {{ $total }}</div>
                        @empty
                            <div>—</div>
                        @endforelse
                    </div>
                    <div class="mt-2 text-sm text-[var(--atlas-color-text-muted)]">{{ $metric['detail'] }}</div>
                </x-atlas-card>
            @endforeach
            <x-atlas-card class="p-5">
                <div class="text-sm font-medium text-[var(--atlas-color-text-secondary)]">Paid This Month</div>
                <div class="mt-3 text-2xl font-bold text-[var(--atlas-color-text-primary)]">{{ $report['paid_this_month'] }}</div>
                <div class="mt-2 text-sm text-[var(--atlas-color-text-muted)]">Paid or closed Billing Records</div>
            </x-atlas-card>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.45fr_1fr]">
            <x-atlas-card class="overflow-hidden">
                <div class="border-b border-[var(--atlas-color-border)] px-6 py-5"><h2 class="text-lg font-semibold text-[var(--atlas-color-text-primary)]">Recent Billing Records</h2></div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-[var(--atlas-color-surface-muted)] text-[var(--atlas-color-text-muted)]"><tr><th class="px-6 py-3">Record</th><th class="px-4 py-3">Client</th><th class="px-4 py-3">VAT receipt</th><th class="px-4 py-3">Amount</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Reporting date</th></tr></thead>
                        <tbody>
                            @forelse ($report['recent_records'] as $record)
                                <tr class="border-t border-[var(--atlas-color-border)]"><td class="px-6 py-4 font-medium">{{ $record->record_number }}</td><td class="px-4 py-4">{{ $record->client->display_name }}</td><td class="px-4 py-4">{{ $record->external_receipt_reference ?? '—' }}</td><td class="px-4 py-4">{{ $record->currency }} {{ number_format((float) ($record->receipt_amount ?? $record->batch_amount), 2) }}</td><td class="px-4 py-4">{{ $record->status->label() }}</td><td class="px-4 py-4">{{ ($record->paid_at ?? $record->issued_at)?->format('d M Y') ?? '—' }}</td></tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-10 text-center text-[var(--atlas-color-text-muted)]">No Billing Records match these filters.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-atlas-card>
            <div class="space-y-6">
                <x-atlas-card class="p-6">
                    <h2 class="text-lg font-semibold text-[var(--atlas-color-text-primary)]">Billing Records by Status</h2>
                    <dl class="mt-4 space-y-3">
                        @foreach ($this->statuses() as $status)
                            <div class="flex items-center justify-between"><dt class="text-sm text-[var(--atlas-color-text-secondary)]">{{ $status->label() }}</dt><dd class="font-semibold text-[var(--atlas-color-text-primary)]">{{ $report['status_counts'][$status->value] }}</dd></div>
                        @endforeach
                    </dl>
                </x-atlas-card>
                <x-atlas-card class="p-6">
                    <h2 class="text-lg font-semibold text-[var(--atlas-color-text-primary)]">Top Clients by Paid Revenue</h2>
                    <p class="mt-1 text-sm text-[var(--atlas-color-text-muted)]">Current year, grouped by currency.</p>
                    <div class="mt-4 space-y-3">
                        @forelse ($report['top_clients'] as $client)
                            <div class="flex items-center justify-between gap-4"><span class="text-sm text-[var(--atlas-color-text-secondary)]">{{ $client['client_name'] }}</span><span class="font-semibold text-[var(--atlas-color-text-primary)]">{{ $client['currency'] }} {{ number_format($client['total'], 2) }}</span></div>
                        @empty
                            <p class="text-sm text-[var(--atlas-color-text-muted)]">No paid revenue for this period.</p>
                        @endforelse
                    </div>
                </x-atlas-card>
            </div>
        </div>
    @endif
</x-filament-panels::page>
