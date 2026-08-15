@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-amber-500 dark:border-amber-400 text-sm font-bold leading-5 text-slate-900 dark:text-amber-400 focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-slate-600 dark:text-gray-400 hover:text-slate-900 dark:hover:text-amber-400 hover:border-amber-400 focus:outline-none transition duration-150 ease-in-out';



@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
