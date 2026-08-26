<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-[#161F2E] border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold text-sm rounded-lg shadow-2xs focus:outline-none focus:ring-2 focus:ring-amber-500/40 transition-all duration-150 disabled:opacity-40 disabled:pointer-events-none cursor-pointer']) }}>
    {{ $slot }}
</button>
