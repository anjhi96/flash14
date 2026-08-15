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

<div class="bg-[#F8FAFC] dark:bg-[#0B0F17] text-slate-900 dark:text-white min-h-screen transition-colors duration-300">
    <x-slot name="title">Layanan & Paket Harga Pembuatan Website - FlashDev</x-slot>
    <x-slot name="description">Jelajahi paket pembuatan website perusahaan, e-commerce, custom SaaS, serta optimasi performa web dari FlashDev.</x-slot>
    <!-- Header Banner -->
    <section class="py-20 border-b border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#080C13] text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl space-y-4">
            <span class="px-3.5 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-700 dark:text-amber-400 text-xs font-bold uppercase tracking-wider">
                Layanan & Paket Agensi
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold mt-4 mb-4 text-slate-900 dark:text-white">
                Solusi Pembuatan Software & Website Professional
            </h1>
            <p class="text-slate-600 dark:text-gray-400 text-lg">
                Kami menyediakan ekosistem pengembangan software lengkap yang disesuaikan dengan skala dan tujuan bisnis Anda.
            </p>
        </div>
    </section>

    <!-- Services Detailed List -->
    <section class="py-20 border-b border-slate-200/80 dark:border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            @foreach ($services as $index => $service)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center bg-white dark:bg-[#131A26] p-8 sm:p-12 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm hover:border-amber-400 dark:hover:border-amber-500/30 hover:shadow-md transition-all duration-300">
                    <div class="lg:col-span-7 space-y-4">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30 text-xl font-extrabold">
                            0{{ $index + 1 }}
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">{{ $service->title }}</h2>
                        <p class="text-amber-700 dark:text-amber-400/90 font-semibold text-sm">{{ $service->short_description }}</p>
                        <p class="text-slate-600 dark:text-gray-300 text-sm leading-relaxed">{{ $service->description }}</p>
                        <div class="pt-4">
                            <a href="{{ route('contact') }}" wire:navigate class="inline-flex items-center text-sm font-extrabold text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300">
                                Konsultasikan Layanan Ini
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                    <div class="lg:col-span-5 bg-[#F8FAFC] dark:bg-[#0B0F17] p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 space-y-3">
                        <h4 class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-widest">Apa Yang Anda Dapatkan:</h4>
                        <ul class="space-y-2.5 text-xs sm:text-sm text-slate-700 dark:text-gray-300">
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Desain kustom & 100% Responsif di Smartphone</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Optimasi Kecepatan & SEO Dasar</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Garansi Maintenance & Support Teknis</span>
                            </li>
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- FAQ / Consultation CTA -->
    <section class="py-20 bg-white dark:bg-[#080C13]">
        <div class="max-w-4xl mx-auto px-4 text-center space-y-6">
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">Butuh Solusi Kustom untuk Bisnis Anda?</h2>
            <p class="text-slate-600 dark:text-gray-400">Tim kami siap merancang arsitektur perangkat lunak yang disesuaikan secara khusus dengan alur bisnis perusahaan Anda.</p>
            <a href="{{ route('contact') }}" wire:navigate class="inline-block px-8 py-4 rounded-xl bg-amber-500 hover:bg-amber-600 dark:bg-gradient-to-r dark:from-amber-500 dark:via-amber-400 dark:to-yellow-500 text-slate-950 font-extrabold shadow-md shadow-amber-500/25 hover:scale-105 transition-all duration-300">
                Diskusi dengan Insinyur Software Kami
            </a>
        </div>
    </section>
</div>
