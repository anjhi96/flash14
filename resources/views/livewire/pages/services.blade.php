<?php

use App\Models\Service;
use Livewire\Volt\Component;

new class extends Component {
    public function with(): array
    {
        return [
            'services' => Service::where('is_active', true)->orderBy('order')->get(),
        ];
    }
}; ?>

<div class="bg-[#F8FAFC] dark:bg-[#0B0F17] text-slate-900 dark:text-slate-100 min-h-screen transition-colors duration-200">
    <x-slot name="title">Layanan & Solusi Rekayasa Software - FlashDev</x-slot>
    <x-slot name="description">Jelajahi paket solusi pembuatan website enterprise, e-commerce, custom SaaS, serta optimasi performa web dari FlashDev.</x-slot>

    <!-- Header Banner -->
    <section class="py-14 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-[#111722]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl text-center space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-amber-50 dark:bg-amber-500/10 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30 text-xs font-semibold uppercase tracking-wider">
                <span class="material-symbols-outlined text-[14px]">terminal</span>
                Solusi & Layanan Spesialis
            </span>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-slate-900 dark:text-white">
                Rekayasa Perangkat Lunak & Sistem Web
            </h1>
            <p class="text-sm text-slate-600 dark:text-slate-400 max-w-xl mx-auto leading-relaxed">
                Kami menyediakan ekosistem pengembangan perangkat lunak lengkap yang disesuaikan dengan skala dan tujuan strategis bisnis Anda.
            </p>
        </div>
    </section>

    <!-- Services Detailed List -->
    <section class="py-14 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @foreach ($services as $index => $service)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center bg-white dark:bg-[#111722] p-6 sm:p-8 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs hover:border-slate-300 dark:hover:border-slate-700 transition-all">
                    <div class="lg:col-span-7 space-y-3">
                        <div class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30 text-xs font-mono font-bold">
                            0{{ $index + 1 }}
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">{{ $service->title }}</h2>
                        <p class="text-amber-700 dark:text-amber-400 font-semibold text-xs">{{ $service->short_description }}</p>
                        <p class="text-slate-600 dark:text-slate-400 text-xs leading-relaxed">{{ $service->description }}</p>
                        <div class="pt-2">
                            <a href="{{ route('contact') }}" wire:navigate class="inline-flex items-center gap-1 text-xs font-semibold text-amber-700 dark:text-amber-400 hover:underline">
                                <span>Konsultasikan Kebutuhan Ini</span>
                                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                    <div class="lg:col-span-5 bg-slate-50 dark:bg-[#161F2E] p-5 rounded-lg border border-slate-200 dark:border-slate-800 space-y-2.5">
                        <h3 class="text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Cakupan Layanan:</h3>
                        <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-400">
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-emerald-600 dark:text-emerald-400">check_circle</span>
                                <span>Arsitektur modular, aman, & responsif mobile</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-emerald-600 dark:text-emerald-400">check_circle</span>
                                <span>Optimasi kinerja basis data & kecepatan muat sistem</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-emerald-600 dark:text-emerald-400">check_circle</span>
                                <span>Dokumentasi teknis & garansi pemeliharaan</span>
                            </li>
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Consultation CTA -->
    <section class="py-14 bg-white dark:bg-[#111722]">
        <div class="max-w-3xl mx-auto px-4 text-center space-y-4">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Memerlukan Spesifikasi Khusus?</h2>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-xl mx-auto">
                Tim engineer kami siap menyusun cetak biru (*blueprint*) dan estimasi teknis yang disesuaikan dengan arsitektur perusahaan Anda.
            </p>
            <div class="pt-2">
                <a href="{{ route('contact') }}" wire:navigate class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 font-semibold text-sm transition-colors shadow-xs">
                    <span>Mulai Diskusi Teknis</span>
                    <span class="material-symbols-outlined text-[18px]">chat</span>
                </a>
            </div>
        </div>
    </section>
</div>
