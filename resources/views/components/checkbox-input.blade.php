@props(['disabled' => false])

<input type="checkbox" @disabled($disabled) {{ $attributes->merge(['class' => 'rounded border-outline text-primary focus:ring-primary disabled:opacity-60']) }}>
