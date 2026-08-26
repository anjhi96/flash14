@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-3.5 py-2.5 bg-white dark:bg-[#111722] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 text-sm rounded-lg shadow-2xs placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:border-amber-500 dark:focus:border-amber-400 focus:ring-2 focus:ring-amber-500/20 focus:outline-none transition-all duration-150 disabled:bg-slate-100 dark:disabled:bg-slate-800 disabled:opacity-60']) }}>
