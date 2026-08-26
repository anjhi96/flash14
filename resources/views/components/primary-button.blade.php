<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 active:bg-amber-800 dark:bg-amber-500 dark:hover:bg-amber-400 dark:active:bg-amber-600 text-white dark:text-slate-950 font-semibold text-sm rounded-lg shadow-xs focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:ring-offset-1 dark:focus:ring-offset-slate-900 transition-all duration-150 disabled:opacity-50 disabled:pointer-events-none cursor-pointer']) }}>
    {{ $slot }}
</button>
