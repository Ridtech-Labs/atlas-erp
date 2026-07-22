<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex min-h-10 items-center justify-center gap-2 rounded-[var(--atlas-radius-sm)] border border-[var(--atlas-color-action-primary)] bg-[var(--atlas-color-action-primary)] px-4 py-2.5 text-xs font-semibold uppercase tracking-[0.12em] text-white transition hover:bg-[var(--atlas-color-action-primary-hover)] focus:outline-none']) }}>
    {{ $slot }}
</button>
