@props([
    'compact' => false,
    'muted' => false,
])

@php
    $classes = $muted
        ? 'atlas-muted-card'
        : ($compact ? 'atlas-card-compact' : 'atlas-card');
@endphp

<section {{ $attributes->class([$classes]) }}>
    {{ $slot }}
</section>
