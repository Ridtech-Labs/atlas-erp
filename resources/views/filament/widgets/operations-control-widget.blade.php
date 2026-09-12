<x-filament-widgets::widget>
    @if ($visible)
        <div class="space-y-6">
            <x-atlas.section-header title="Operations Control" description="Company-wide work, evidence, and Fleet commitments requiring attention." />
            <div class="grid gap-3 md:grid-cols-3 xl:grid-cols-7">
                @foreach (['total' => 'Total Jobs', 'draft' => 'Draft', 'pending_approval' => 'Pending Approval', 'approved_ready' => 'Approved / Ready', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $key => $label)
                    <x-atlas-card class="p-4"><div class="text-xs font-semibold uppercase tracking-wide text-[var(--atlas-color-text-muted)]">{{ $label }}</div><div class="mt-2 text-2xl font-bold">{{ $control['jobs'][$key] }}</div></x-atlas-card>
                @endforeach
            </div>
            <div class="grid gap-6 xl:grid-cols-3">
                <x-atlas-card class="p-5"><h3 class="font-semibold">Operational Type</h3><dl class="mt-4 space-y-3 text-sm"><div class="flex justify-between"><dt>Heavy Machinery</dt><dd class="font-bold">{{ $control['types']['heavy_machinery'] }}</dd></div><div class="flex justify-between"><dt>Trucking / Haulage</dt><dd class="font-bold">{{ $control['types']['trucking'] }}</dd></div></dl></x-atlas-card>
                <x-atlas-card class="p-5 xl:col-span-2"><h3 class="font-semibold">Actionable Backlog</h3><div class="mt-4 grid gap-3 md:grid-cols-2">@foreach (['jobs_pending_approval' => 'Jobs Pending Approval', 'job_cards_pending_review' => 'Job Card Work Entries Pending Accounts Review', 'waybills_pending_verification' => 'Waybills Pending Verification', 'heavy_evidence_awaiting_finance' => 'Heavy Machinery Evidence Awaiting Finance', 'waybills_awaiting_finance' => 'Verified Waybills Awaiting Finance', 'draft_billing_batches' => 'Draft Billing Batches', 'prepared_without_record' => 'Prepared Batches Without Billing Records'] as $key => $label)<div class="flex justify-between rounded-xl bg-[var(--atlas-color-background-muted)] px-3 py-2 text-sm"><span>{{ $label }}</span><strong>{{ $control['backlog'][$key] }}</strong></div>@endforeach</div></x-atlas-card>
            </div>
            <x-atlas-card class="p-5"><h3 class="font-semibold">Fleet Commitment</h3><div class="mt-4 grid gap-3 md:grid-cols-4">@foreach (['available' => 'Available', 'assigned' => 'Assigned', 'dispatched' => 'Dispatched', 'out_of_service' => 'Out of Service'] as $key => $label)<div class="rounded-xl bg-[var(--atlas-color-background-muted)] p-3"><div class="text-sm text-[var(--atlas-color-text-secondary)]">{{ $label }}</div><div class="mt-1 text-2xl font-bold">{{ $control['fleet'][$key] }}</div></div>@endforeach</div></x-atlas-card>
        </div>
    @endif
</x-filament-widgets::widget>
