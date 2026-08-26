@props(['variant' => 'info', 'dismissible' => true])

@php
$variants = [
    'success' => ['bg-state-success-container', 'text-state-on-success-container', 'check_circle'],
    'error' => ['bg-state-error-container', 'text-state-on-error-container', 'error'],
    'warning' => ['bg-state-warning-container', 'text-state-on-warning-container', 'warning'],
    'info' => ['bg-state-info-container', 'text-state-on-info-container', 'info'],
];
[$bg, $text, $icon] = $variants[$variant] ?? $variants['info'];
@endphp

<div data-alert {{ $attributes->merge(['class' => "flex items-center justify-between gap-3 rounded-lg px-4 py-3 text-sm font-medium {$bg} {$text}"]) }}>
    <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>
        <span>{{ $slot }}</span>
    </div>
    @if ($dismissible)
        <button type="button" onclick="this.closest('[data-alert]').remove()" class="shrink-0 rounded-md p-1 opacity-70 hover:opacity-100 cursor-pointer" aria-label="Tutup">&times;</button>
    @endif
</div>
