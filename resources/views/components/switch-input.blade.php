@props(['checked' => false])

<button type="button" {{ $attributes->merge(['class' => 'relative h-6 w-11 shrink-0 cursor-pointer rounded-full transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 ' . ($checked ? 'bg-primary' : 'bg-surface-container-highest')]) }}>
    <span class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow-sm transition-transform duration-200 {{ $checked ? 'translate-x-5' : 'translate-x-0' }}"></span>
</button>
