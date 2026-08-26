@props(['variant' => 'neutral'])

@php
$variants = [
    'neutral' => 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high',
    'danger' => 'text-state-error hover:bg-state-error-container',
];
$classes = $variants[$variant] ?? $variants['neutral'];
@endphp

<button {{ $attributes->merge(['type' => 'button', 'class' => "inline-flex items-center justify-center rounded-md px-2.5 py-1.5 text-xs font-semibold transition-colors cursor-pointer disabled:opacity-40 disabled:pointer-events-none {$classes}"]) }}>
    {{ $slot }}
</button>
