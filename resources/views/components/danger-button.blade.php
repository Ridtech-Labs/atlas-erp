<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex min-h-10 items-center justify-center gap-2 rounded-[var(--atlas-radius-sm)] border border-[var(--atlas-color-status-danger)] bg-[var(--atlas-color-status-danger)] px-4 py-2.5 text-xs font-semibold uppercase tracking-[0.12em] text-white transition hover:bg-[#b91c1c] focus:outline-none']) }}>
    {{ $slot }}
</button>
