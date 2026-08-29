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

<div class="bg-surface text-on-surface min-h-screen transition-colors duration-200">
    <x-slot name="title">Layanan & Solusi Rekayasa Software - FlashDev</x-slot>
    <x-slot name="description">Jelajahi paket solusi pembuatan website enterprise, e-commerce, custom SaaS, serta optimasi performa web dari FlashDev.</x-slot>

    <!-- Header Banner -->
    <section class="py-14 border-b border-outline-variant bg-surface-container-lowest">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl text-center space-y-3">
            <x-badge variant="primary">Solusi & Layanan Spesialis</x-badge>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-on-surface">
                Rekayasa Perangkat Lunak & Sistem Web
            </h1>
            <p class="text-sm text-on-surface-variant max-w-xl mx-auto leading-relaxed">
                Kami menyediakan ekosistem pengembangan perangkat lunak lengkap yang disesuaikan dengan skala dan tujuan strategis bisnis Anda.
            </p>
        </div>
    </section>

    <!-- Services Detailed List -->
    <section class="py-14 border-b border-outline-variant">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @foreach ($services as $index => $service)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center bg-surface-container-lowest p-6 sm:p-8 rounded-xl border border-outline-variant hover:border-outline transition-all">
                    <div class="lg:col-span-7 space-y-3">
                        <div class="text-xs font-mono font-bold text-primary">0{{ $index + 1 }}</div>
                        <h2 class="text-xl sm:text-2xl font-bold text-on-surface">{{ $service->title }}</h2>
                        <p class="text-primary font-semibold text-xs">{{ $service->short_description }}</p>
                        <p class="text-on-surface-variant text-xs leading-relaxed">{{ $service->description }}</p>
                        @if ($service->starting_price)
                            <div class="flex items-baseline gap-1.5 pt-2">
                                <span class="text-xs text-on-surface-variant font-medium">Mulai dari</span>
                                <span class="text-lg sm:text-xl font-extrabold text-primary font-mono">{{ $service->starting_price }}</span>
                            </div>
                        @endif
                        <div class="pt-2">
                            <a href="{{ route('contact') }}" wire:navigate class="inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline">
                                <span>Konsultasikan Kebutuhan Ini</span>
                                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                    <div class="lg:col-span-5 bg-surface-container p-5 rounded-lg border border-outline-variant space-y-2.5">
                        <h3 class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Cakupan Layanan:</h3>
                        <ul class="space-y-2 text-xs text-on-surface-variant">
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-state-success">check_circle</span>
                                <span>Arsitektur modular, aman, & responsif mobile</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-state-success">check_circle</span>
                                <span>Optimasi kinerja basis data & kecepatan muat sistem</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-state-success">check_circle</span>
                                <span>Dokumentasi teknis & garansi pemeliharaan</span>
                            </li>
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Consultation CTA -->
    <section class="py-14 bg-surface-container-lowest">
        <div class="max-w-3xl mx-auto px-4 text-center space-y-4">
            <h2 class="text-2xl font-bold text-on-surface">Memerlukan Spesifikasi Khusus?</h2>
            <p class="text-xs sm:text-sm text-on-surface-variant max-w-xl mx-auto">
                Tim engineer kami siap menyusun cetak biru (*blueprint*) dan estimasi teknis yang disesuaikan dengan arsitektur perusahaan Anda.
            </p>
            <div class="pt-2">
                <a href="{{ route('contact') }}" wire:navigate class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary hover:bg-primary-hover text-on-primary font-semibold text-sm transition-colors shadow-xs">
                    <span>Mulai Diskusi Teknis</span>
                    <span class="material-symbols-outlined text-[18px]">chat</span>
                </a>
            </div>
        </div>
    </section>
</div>
