<x-app-layout>
    <x-slot name="header">
        <x-page-header eyebrow="Ringkasan Akun" icon="dashboard" :title="__('Dashboard')" />
    </x-slot>

    <div class="py-8 bg-surface min-h-[calc(100vh-140px)] transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-surface-container-lowest overflow-hidden rounded-xl border border-outline-variant p-6 space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-state-success-container text-state-on-success-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">verified_user</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-on-surface">Selamat Datang, {{ Auth::user()->name }}!</h2>
                        <p class="text-xs text-on-surface-variant">Anda berhasil login ke sistem FlashDev Portal.</p>
                    </div>
                </div>

                @if(Auth::user()->is_admin)
                    <div class="pt-4 border-t border-outline-variant flex items-center justify-between">
                        <span class="text-xs text-on-surface-variant">Akses panel administrator untuk mengelola konten website.</span>
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-primary hover:bg-primary-hover text-on-primary font-semibold text-xs transition-colors shadow-2xs">
                            <span class="material-symbols-outlined text-[15px]">admin_panel_settings</span>
                            <span>Buka Admin Control Center</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
