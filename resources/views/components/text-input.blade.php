@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-3.5 py-2.5 bg-surface-container-lowest border border-outline text-on-surface text-sm rounded-lg shadow-2xs placeholder:text-on-surface-variant/70 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition-all duration-150 disabled:bg-surface-container disabled:opacity-60']) }}>
