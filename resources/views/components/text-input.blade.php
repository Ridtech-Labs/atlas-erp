@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-[var(--atlas-radius-sm)] border-[var(--atlas-color-border-default)] bg-white text-sm text-[var(--atlas-color-text-primary)] shadow-sm focus:border-[var(--atlas-color-action-primary)] focus:ring-[var(--atlas-color-action-primary)]']) }}>
