<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-state-error hover:opacity-90 text-white font-semibold text-sm rounded-lg shadow-xs focus:outline-none focus:ring-2 focus:ring-state-error/40 transition-all duration-150 disabled:opacity-40 disabled:pointer-events-none cursor-pointer']) }}>
    {{ $slot }}
</button>
