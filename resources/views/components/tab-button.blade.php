@props(['active' => false])

@php
$classes = ($active ?? false)
    ? 'text-primary border-primary font-semibold'
    : 'text-on-surface-variant border-transparent hover:text-on-surface hover:border-outline-variant font-medium';
@endphp

<button type="button" {{ $attributes->merge(['class' => "inline-flex items-center gap-2 whitespace-nowrap border-b-2 px-1 py-2.5 text-sm transition-colors cursor-pointer {$classes}"]) }}>
    {{ $slot }}
</button>
