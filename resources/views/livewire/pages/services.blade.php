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

<div class="bg-gray-900 text-white min-h-screen">
    <x-slot name="title">Layanan & Paket Harga Pembuatan Website - FlashDev</x-slot>
    <x-slot name="description">Jelajahi paket pembuatan website perusahaan, e-commerce, custom SaaS, serta optimasi performa web dari FlashDev.</x-slot>
    <!-- Header Banner -->
    <section class="py-20 border-b border-gray-800 bg-gray-950/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center max-w-3xl">
            <span class="px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 text-xs font-semibold uppercase tracking-wider">
                Layanan & Paket Agensi
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold mt-4 mb-4">
                Solusi Pembuatan Software & Website Professional
            </h1>
            <p class="text-gray-400 text-lg">
                Kami menyediakan ekosistem pengembangan software lengkap yang disesuaikan dengan skala dan tujuan bisnis Anda.
            </p>
        </div>
    </section>

    <!-- Services Detailed List -->
    <section class="py-20 border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            @foreach ($services as $index => $service)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center bg-gray-800/40 p-8 sm:p-12 rounded-3xl border border-gray-700/50">
                    <div class="lg:col-span-7 space-y-4">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-500/20 text-indigo-400 border border-indigo-500/40 text-xl font-bold">
                            0{{ $index + 1 }}
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-white">{{ $service->title }}</h2>
                        <p class="text-indigo-300 font-medium text-sm">{{ $service->short_description }}</p>
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $service->description }}</p>
                        <div class="pt-4">
                            <a href="{{ route('contact') }}" wire:navigate class="inline-flex items-center text-sm font-semibold text-indigo-400 hover:text-indigo-300">
                                Konsultasikan Layanan Ini
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                    <div class="lg:col-span-5 bg-gray-900/80 p-6 rounded-2xl border border-gray-700/60 space-y-3">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Apa Yang Anda Dapatkan:</h4>
                        <ul class="space-y-2.5 text-xs sm:text-sm text-gray-300">
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Desain kustom & 100% Responsif di Smartphone</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Optimasi Kecepatan Load & SEO On-Page</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Kode Bersih, Aman, & Mudah Di-maintenance</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Garansi Perbaikan Bug & Dukungan 3 Bulan</span>
                            </li>
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Transparent Pricing Package Tiers -->
    <section class="py-20 border-b border-gray-800 bg-gray-950/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
                <h2 class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Paket Estimasi</h2>
                <h3 class="text-3xl sm:text-4xl font-extrabold text-white">Paket Harga Pembuatan Website</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Starter Tier -->
                <div class="bg-gray-800/60 rounded-3xl p-8 border border-gray-700/60 flex flex-col justify-between space-y-8">
                    <div class="space-y-4">
                        <span class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Landing Page / Profile</span>
                        <h4 class="text-2xl font-bold text-white">Starter Web</h4>
                        <div class="text-3xl font-extrabold text-white">Rp 3.500.000<span class="text-xs text-gray-400 font-normal"> / proyek</span></div>
                        <p class="text-xs text-gray-400">Cocok untuk UMKM & profesional yang membutuhkan company profile responsif cepat rilis.</p>
                        <ul class="space-y-3 pt-4 text-xs text-gray-300">
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Hingga 5 Halaman</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Desain Modern & Responsif</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Integrasi Form Kontak WA</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> SEO Basic & Speed Tuning</li>
                        </ul>
                    </div>
                    <a href="{{ route('contact') }}" wire:navigate class="w-full py-3 rounded-xl bg-gray-700 hover:bg-gray-600 text-center font-bold text-sm text-white transition-colors">
                        Pilih Paket Starter
                    </a>
                </div>

                <!-- Business Tier (Featured) -->
                <div class="bg-gradient-to-b from-indigo-900/60 to-gray-800/80 rounded-3xl p-8 border-2 border-indigo-500 relative flex flex-col justify-between space-y-8 shadow-2xl">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-indigo-500 text-white text-xs font-bold rounded-full uppercase tracking-wider">
                        Paling Populer
                    </div>
                    <div class="space-y-4 pt-2">
                        <span class="text-xs font-bold text-indigo-300 uppercase tracking-wider">E-Commerce & Dynamic Web</span>
                        <h4 class="text-2xl font-bold text-white">Business Store</h4>
                        <div class="text-3xl font-extrabold text-white">Rp 7.500.000<span class="text-xs text-indigo-200 font-normal"> / proyek</span></div>
                        <p class="text-xs text-indigo-200">Solusi toko online lengkap dengan manajemen produk, inventaris, & Payment Gateway.</p>
                        <ul class="space-y-3 pt-4 text-xs text-gray-200">
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Katalog Produk Tanpa Batas</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Payment Gateway (QRIS, VA, CC)</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Hitung Ongkir Otomatis</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Dashboard Admin Pengelolaan</li>
                        </ul>
                    </div>
                    <a href="{{ route('contact') }}" wire:navigate class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-center font-bold text-sm text-white shadow-lg transition-all duration-300">
                        Pilih Paket Business
                    </a>
                </div>

                <!-- Custom Enterprise Tier -->
                <div class="bg-gray-800/60 rounded-3xl p-8 border border-gray-700/60 flex flex-col justify-between space-y-8">
                    <div class="space-y-4">
                        <span class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Custom SaaS / Enterprise</span>
                        <h4 class="text-2xl font-bold text-white">Custom Web App</h4>
                        <div class="text-3xl font-extrabold text-white">Custom Rate</div>
                        <p class="text-xs text-gray-400">Pengembangan aplikasi web berbasis spesifikasi kompleks (ERP, CRM, LMS, HRIS).</p>
                        <ul class="space-y-3 pt-4 text-xs text-gray-300">
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Spesifikasi & Arsitektur Custom</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Integrasi Rest API & Third-party</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Multi-role Permission & Security</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> SLA Maintenance & Backup Routine</li>
                        </ul>
                    </div>
                    <a href="{{ route('contact') }}" wire:navigate class="w-full py-3 rounded-xl bg-gray-700 hover:bg-gray-600 text-center font-bold text-sm text-white transition-colors">
                        Konsultasi Enterprise
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
