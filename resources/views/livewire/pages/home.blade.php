<?php

use App\Models\Service;
use App\Models\Project;
use App\Models\PageSection;
use Livewire\Volt\Component;

new class extends Component {
    public function with(): array
    {
        $sections = PageSection::forPage('home');
        return [
            'services'        => Service::where('is_active', true)->orderBy('order')->get(),
            'featuredProjects' => Project::where('is_featured', true)->orderBy('order')->take(3)->get(),
            'sections'        => $sections,
        ];
    }
}; ?>

<div class="bg-surface text-on-surface min-h-screen transition-colors duration-200">
    <x-slot name="title">FlashDev - Agensi Pembuatan Software & Website Professional</x-slot>
    <x-slot name="description">Kami membantu perusahaan dan institusi membangun aplikasi web custom, sistem informasi enterprise, dan platform SaaS dengan performa tinggi.</x-slot>

    <!-- Hero Section -->
    @if (($sections['hero'] ?? null)?->is_enabled ?? true)
    <section class="relative overflow-hidden pt-16 pb-20 border-b border-outline-variant bg-surface-container-lowest">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto space-y-6">
                
                <h1 class="text-3xl sm:text-5xl font-bold tracking-tight leading-tight text-on-surface">
                    Bangun Sistem & Website Perusahaan dengan <span class="text-primary">Presisi Tinggi</span>
                </h1>
                <p class="text-base sm:text-lg text-on-surface-variant leading-relaxed max-w-2xl mx-auto">
                    Kami membantu organisasi dan pelaku usaha membangun aplikasi web kustom, sistem automasi, serta arsitektur software yang cepat, tangguh, dan terukur.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                    <a href="{{ route('contact') }}" wire:navigate class="w-full sm:w-auto px-6 py-3 rounded-lg bg-primary hover:bg-primary-hover text-on-primary font-semibold text-sm shadow-xs transition-colors text-center">
                        Konsultasikan Kebutuhan
                    </a>
                    <a href="{{ route('portfolio') }}" wire:navigate class="w-full sm:w-auto px-6 py-3 rounded-lg bg-surface-container-lowest hover:bg-surface-container text-on-surface font-semibold text-sm border border-outline shadow-2xs transition-colors text-center">
                        Eksplorasi Portofolio
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Metrics Banner -->
    @if (($sections['metrics'] ?? null)?->is_enabled ?? true)
    <section class="py-8 bg-surface-container-low border-b border-outline-variant">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="space-y-1">
                    <div class="text-2xl sm:text-3xl font-bold text-primary">50+</div>
                    <div class="text-xs text-on-surface-variant font-medium">Proyek Sukses Terkirim</div>
                </div>
                <div class="space-y-1">
                    <div class="text-2xl sm:text-3xl font-bold text-on-surface">99.9%</div>
                    <div class="text-xs text-on-surface-variant font-medium">Uptime & Reliability</div>
                </div>
                <div class="space-y-1">
                    <div class="text-2xl sm:text-3xl font-bold text-primary">100%</div>
                    <div class="text-xs text-on-surface-variant font-medium">Quality Assurance</div>
                </div>
                <div class="space-y-1">
                    <div class="text-2xl sm:text-3xl font-bold text-on-surface">24/7</div>
                    <div class="text-xs text-on-surface-variant font-medium">Dukungan Pemeliharaan</div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Tech Stack Highlights -->
    @if (($sections['tech_stack'] ?? null)?->is_enabled ?? true)
    <section class="py-8 border-b border-outline-variant bg-surface-container-lowest">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-xs font-semibold uppercase tracking-wider text-on-surface-variant mb-4">
                Teknologi yang Didukung
            </p>
            <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3">
                @foreach (($sections['tech_stack'] ?? null)?->settings['items'] ?? ['Laravel', 'Livewire', 'Tailwind CSS', 'Vue.js / React', 'PostgreSQL', 'Docker', 'REST API'] as $tech)
                    <x-badge>{{ $tech }}</x-badge>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Services Overview -->
    @if (($sections['services'] ?? null)?->is_enabled ?? true)
    <section class="py-16 border-b border-outline-variant bg-surface">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mb-10 space-y-2">
                <span class="text-xs font-bold text-primary uppercase tracking-wider">Layanan Spesialis</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-on-surface">Solusi Rekayasa Perangkat Lunak</h2>
                <p class="text-sm text-on-surface-variant">Pengembangan terstruktur dengan arsitektur standar industri.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($services as $service)
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 hover:border-outline transition-all flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <h3 class="text-base font-bold text-on-surface">{{ $service->title }}</h3>
                            <p class="text-xs text-on-surface-variant leading-relaxed">{{ $service->short_description }}</p>
                            @if ($service->starting_price)
                                <x-badge variant="primary">Mulai {{ $service->starting_price }}</x-badge>
                            @endif
                        </div>
                        <div class="pt-2 border-t border-outline-variant">
                            <a href="{{ route('services') }}" wire:navigate class="inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline">
                                Detail Layanan
                                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Featured Projects Section -->
    @if (($sections['featured_projects'] ?? null)?->is_enabled ?? true)
    <section class="py-16 border-b border-outline-variant bg-surface-container-lowest">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-primary uppercase tracking-wider">Portofolio Pilihan</span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-on-surface">Implementasi & Studi Kasus</h2>
                </div>
                <a href="{{ route('portfolio') }}" wire:navigate class="inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline">
                    Semua Portofolio
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach ($featuredProjects as $project)
                    <div class="bg-surface rounded-xl overflow-hidden border border-outline-variant flex flex-col justify-between hover:border-outline transition-all">
                        <div>
                            <div class="relative overflow-hidden aspect-video bg-surface-container">
                                @if ($project->thumbnail)
                                    <img src="{{ $project->thumbnail }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-xs text-on-surface-variant">No Image</div>
                                @endif
                                <div class="absolute top-2.5 left-2.5">
                                    <span class="px-2.5 py-0.5 bg-black/70 backdrop-blur-xs text-white text-[10px] font-semibold rounded-md">
                                        {{ $project->category }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-4 space-y-1.5">
                                <h3 class="text-sm font-bold text-on-surface">{{ $project->title }}</h3>
                                <p class="text-xs text-primary font-semibold">Klien: {{ $project->client }}</p>
                                <p class="text-xs text-on-surface-variant line-clamp-2">{{ $project->description }}</p>
                            </div>
                        </div>
                        @if ($project->project_url)
                            <div class="p-4 pt-0">
                                <a href="{{ $project->project_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-xs font-semibold text-on-surface-variant hover:text-primary pt-2 border-t border-outline-variant w-full">
                                    <span>Kunjungi Live URL</span>
                                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Development Process -->
    @if (($sections['process'] ?? null)?->is_enabled ?? true)
    <section class="py-16 border-b border-outline-variant bg-surface">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mb-10 space-y-1 text-center sm:text-left">
                <span class="text-xs font-bold text-primary uppercase tracking-wider">Metodologi Kerja</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-on-surface">Alur Rekayasa Perangkat Lunak</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 space-y-2">
                    <div class="text-xs font-mono font-bold text-primary">01 / REQUIREMENT</div>
                    <h3 class="text-sm font-bold text-on-surface">Discovery & Scoping</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">Analisis mendalam kebutuhan proses bisnis, penyusunan arsitektur sistem, dan estimasi waktu.</p>
                </div>
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 space-y-2">
                    <div class="text-xs font-mono font-bold text-primary">02 / PROTOTYPE</div>
                    <h3 class="text-sm font-bold text-on-surface">UI/UX & Data Model</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">Perancangan antarmuka fungsional dan pemodelan database untuk kepastian alur operasional.</p>
                </div>
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 space-y-2">
                    <div class="text-xs font-mono font-bold text-primary">03 / IMPLEMENT</div>
                    <h3 class="text-sm font-bold text-on-surface">Clean Development</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">Implementasi kode bersih berstandar tinggi dengan pengujian keamanan dan performa.</p>
                </div>
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 space-y-2">
                    <div class="text-xs font-mono font-bold text-primary">04 / DEPLOY</div>
                    <h3 class="text-sm font-bold text-on-surface">QA & Cloud Launch</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">Penyebaran ke infrastruktur cloud produksi, dokumentasi sistem, dan garansi pemeliharaan.</p>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    @if (($sections['cta'] ?? null)?->is_enabled ?? true)
    <section class="py-14 bg-surface-container-lowest">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900 rounded-2xl p-8 sm:p-12 border border-slate-800 text-center space-y-4 text-white">
                <h2 class="text-2xl sm:text-3xl font-bold">Siap Mengembangkan Proyek Digital Anda?</h2>
                <p class="text-slate-300 max-w-xl mx-auto text-xs sm:text-sm leading-relaxed">
                    Konsultasikan arsitektur sistem dan estimasi proyek Anda bersama tim engineer FlashDev.
                </p>
                <div class="pt-2">
                    <a href="{{ route('contact') }}" wire:navigate class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary hover:bg-primary-hover text-on-primary font-semibold text-sm transition-colors shadow-xs">
                        <span>Mulai Konsultasi Gratis</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif
</div>
