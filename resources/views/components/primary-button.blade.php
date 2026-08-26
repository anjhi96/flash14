<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-hover text-on-primary font-semibold text-sm rounded-lg shadow-xs focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-1 transition-all duration-150 disabled:opacity-50 disabled:pointer-events-none cursor-pointer']) }}>
    {{ $slot }}
</button>
