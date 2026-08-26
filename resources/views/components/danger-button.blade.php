<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white font-semibold text-sm rounded-lg shadow-xs focus:outline-none focus:ring-2 focus:ring-red-500/40 transition-all duration-150 disabled:opacity-40 disabled:pointer-events-none cursor-pointer']) }}>
    {{ $slot }}
</button>
