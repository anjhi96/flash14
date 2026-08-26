<x-app-layout>
    <x-slot name="header">
        <div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-amber-50 dark:bg-amber-500/10 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30 text-[11px] font-semibold uppercase tracking-wider">
                <span class="material-symbols-outlined text-[13px]">person</span>
                Pengaturan Pengguna
            </span>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 dark:text-white mt-1">
                {{ __('Profile & Keamanan') }}
            </h1>
        </div>
    </x-slot>

    <div class="py-8 bg-[#F8FAFC] dark:bg-[#111722] min-h-[calc(100vh-140px)] transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 sm:p-8 bg-white dark:bg-[#161F2E] border border-slate-200 dark:border-slate-800 shadow-2xs rounded-xl">
                <div class="max-w-xl text-slate-900 dark:text-slate-100">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white dark:bg-[#161F2E] border border-slate-200 dark:border-slate-800 shadow-2xs rounded-xl">
                <div class="max-w-xl text-slate-900 dark:text-slate-100">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white dark:bg-[#161F2E] border border-slate-200 dark:border-slate-800 shadow-2xs rounded-xl">
                <div class="max-w-xl text-slate-900 dark:text-slate-100">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
