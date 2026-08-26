<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-amber-50 dark:bg-amber-500/10 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30 text-[11px] font-semibold uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[13px]">dashboard</span>
                    Ringkasan Akun
                </span>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white mt-1">
                    {{ __('Dashboard') }}
                </h1>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-[#F8FAFC] dark:bg-[#111722] min-h-[calc(100vh-140px)] transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-[#161F2E] overflow-hidden shadow-2xs rounded-xl border border-slate-200 dark:border-slate-800 p-6 space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/40 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">verified_user</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Selamat Datang, {{ Auth::user()->name }}!</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Anda berhasil login ke sistem FlashDev Portal.</p>
                    </div>
                </div>
                
                @if(Auth::user()->is_admin)
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <span class="text-xs text-slate-600 dark:text-slate-400">Akses panel administrator untuk mengelola konten website.</span>
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 active:bg-amber-800 dark:bg-amber-500 dark:hover:bg-amber-400 text-white dark:text-slate-950 font-semibold text-xs transition-colors shadow-2xs">
                            <span class="material-symbols-outlined text-[15px]">admin_panel_settings</span>
                            <span>Buka Admin Control Center</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
