<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex min-h-10 items-center justify-center gap-2 rounded-[var(--atlas-radius-sm)] border border-[var(--atlas-color-border-default)] bg-white px-4 py-2.5 text-xs font-semibold uppercase tracking-[0.12em] text-[var(--atlas-color-text-secondary)] shadow-sm transition hover:bg-[var(--atlas-color-background-muted)] focus:outline-none disabled:opacity-25']) }}>
    {{ $slot }}
</button>
