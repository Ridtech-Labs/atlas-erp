@props([
    'status' => 'info',
])

<span {{ $attributes->class(['atlas-status-badge'])->merge(['data-status' => $status]) }}>
    {{ $slot }}
</span>
