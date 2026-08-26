<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-surface-container-lowest border border-outline hover:bg-surface-container text-on-surface font-semibold text-sm rounded-lg shadow-2xs focus:outline-none focus:ring-2 focus:ring-primary/40 transition-all duration-150 disabled:opacity-40 disabled:pointer-events-none cursor-pointer']) }}>
    {{ $slot }}
</button>
