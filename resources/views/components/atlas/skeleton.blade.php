@props([
    'height' => '1rem',
])

<div {{ $attributes->class(['atlas-skeleton'])->style(['height' => $height]) }}></div>
