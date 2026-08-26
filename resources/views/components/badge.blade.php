@props(['variant' => 'neutral'])

@php
$variants = [
    'neutral' => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
    'success' => 'bg-state-success-container text-state-on-success-container border-transparent',
    'warning' => 'bg-state-warning-container text-state-on-warning-container border-transparent',
    'error' => 'bg-state-error-container text-state-on-error-container border-transparent',
    'primary' => 'bg-primary-container text-on-primary-container border-transparent',
];
$classes = $variants[$variant] ?? $variants['neutral'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-semibold tracking-wide border {$classes}"]) }}>
    {{ $slot }}
</span>
