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

<div class="bg-gray-900 text-white min-h-screen">
    <x-slot name="title">FlashDev - Agensi Pembuatan Software & Website Professional</x-slot>
    <x-slot name="description">Kami membantu perusahaan dan UMKM membangun aplikasi web custom, sistem e-commerce, dan platform SaaS dengan performa tinggi.</x-slot>

    <!-- Hero Section -->
    @if (($sections['hero'] ?? null)?->is_enabled ?? true)
    <section class="relative overflow-hidden pt-20 pb-28 border-b border-gray-800">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-900/30 via-gray-900 to-gray-900 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto space-y-6">
                <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 text-xs font-semibold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                    <span>Agensi Pembuatan Software & Website Professional</span>
                </div>
                <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight leading-tight">
                    Wujudkan Website & Software Usaha Anda dengan <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400">Performa Tinggi</span>
                </h1>
                <p class="text-lg sm:text-xl text-gray-300 leading-relaxed">
                    Kami membantu perusahaan dan UMKM membangun aplikasi web custom, sistem e-commerce, dan platform SaaS dengan arsitektur bersih, cepat, dan teruji.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    <a href="{{ route('contact') }}" wire:navigate class="w-full sm:w-auto px-8 py-4 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold shadow-lg shadow-indigo-500/25 transition-all duration-300 hover:scale-[1.02]">
                        Minta Penawaran / Konsultasi
                    </a>
                    <a href="{{ route('portfolio') }}" wire:navigate class="w-full sm:w-auto px-8 py-4 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-200 font-semibold border border-gray-700 transition-all duration-300">
                        Lihat Portofolio Kami
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Metrics Banner -->
    @if (($sections['metrics'] ?? null)?->is_enabled ?? true)
    <section class="py-12 bg-gray-950/60 border-b border-gray-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="space-y-1">
                    <div class="text-3xl sm:text-4xl font-extrabold text-indigo-400">50+</div>
                    <div class="text-xs sm:text-sm text-gray-400">Proyek Selesai</div>
                </div>
                <div class="space-y-1">
                    <div class="text-3xl sm:text-4xl font-extrabold text-purple-400">99.9%</div>
                    <div class="text-xs sm:text-sm text-gray-400">SLA & Uptime Server</div>
                </div>
                <div class="space-y-1">
                    <div class="text-3xl sm:text-4xl font-extrabold text-pink-400">100%</div>
                    <div class="text-xs sm:text-sm text-gray-400">Kepuasan Klien</div>
                </div>
                <div class="space-y-1">
                    <div class="text-3xl sm:text-4xl font-extrabold text-cyan-400">24/7</div>
                    <div class="text-xs sm:text-sm text-gray-400">Dukungan Teknis</div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Tech Stack Highlights -->
    @if (($sections['tech_stack'] ?? null)?->is_enabled ?? true)
    <section class="py-12 border-b border-gray-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-xs font-semibold uppercase tracking-widest text-gray-400 mb-8">
                Teknologi Modern yang Kami Gunakan
            </p>
            <div class="flex flex-wrap items-center justify-center gap-8 sm:gap-12 opacity-80 grayscale hover:grayscale-0 transition-all duration-500">
                @foreach (($sections['tech_stack'] ?? null)?->settings['items'] ?? ['Laravel', 'Livewire', 'Tailwind CSS', 'React / Vue.js', 'MySQL', 'Docker'] as $tech)
                    <span class="px-4 py-2 bg-gray-800/80 rounded-lg text-gray-200 text-sm font-semibold border border-gray-700">{{ $tech }}</span>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Services Overview -->
    @if (($sections['services'] ?? null)?->is_enabled ?? true)
    <section class="py-20 border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
                <h2 class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Solusi Layanan</h2>
                <h3 class="text-3xl sm:text-4xl font-extrabold text-white">Layanan Spesialis Kami</h3>
                <p class="text-gray-400">Solusi pengembangan lengkap untuk mentransformasi ide Anda menjadi produk digital unggulan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($services as $service)
                    <div class="bg-gray-800/60 backdrop-blur border border-gray-700/60 rounded-2xl p-8 hover:border-indigo-500/50 transition-all duration-300 hover:-translate-y-1 shadow-lg group">
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-3">{{ $service->title }}</h4>
                        <p class="text-gray-400 text-sm leading-relaxed mb-6">{{ $service->short_description }}</p>
                        <a href="{{ route('services') }}" wire:navigate class="inline-flex items-center text-sm font-semibold text-indigo-400 group-hover:text-indigo-300">
                            Pelajari Selengkapnya
                            <svg class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Featured Projects Section -->
    @if (($sections['featured_projects'] ?? null)?->is_enabled ?? true)
    <section class="py-20 border-b border-gray-800 bg-gray-950/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-4">
                <div>
                    <h2 class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Portofolio Terpilih</h2>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Karya Terbaru Kami</h3>
                </div>
                <a href="{{ route('portfolio') }}" wire:navigate class="inline-flex items-center text-sm font-semibold text-indigo-400 hover:text-indigo-300">
                    Lihat Semua Proyek
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($featuredProjects as $project)
                    <div class="bg-gray-800/80 rounded-2xl overflow-hidden border border-gray-700/60 hover:border-indigo-500/50 transition-all duration-300 group flex flex-col">
                        <div class="relative overflow-hidden aspect-video bg-gray-900">
                            @if ($project->thumbnail)
                                <img src="{{ $project->thumbnail }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gray-800 flex items-center justify-center text-gray-500">No Image</div>
                            @endif
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 bg-gray-900/80 backdrop-blur text-indigo-300 text-xs font-semibold rounded-full border border-indigo-500/30">
                                    {{ $project->category }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <h4 class="text-lg font-bold text-white group-hover:text-indigo-400 transition-colors">{{ $project->title }}</h4>
                                <p class="text-xs text-indigo-300 font-medium mt-1">Klien: {{ $project->client }}</p>
                                <p class="text-gray-400 text-sm mt-2 line-clamp-3">{{ $project->description }}</p>
                            </div>
                            @if ($project->project_url)
                                <a href="{{ $project->project_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-xs font-semibold text-gray-300 hover:text-white pt-2 border-t border-gray-700/60">
                                    Kunjungi Live Site
                                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Development Process -->
    @if (($sections['process'] ?? null)?->is_enabled ?? true)
    <section class="py-20 border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
                <h2 class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Alur Pengerjaan</h2>
                <h3 class="text-3xl sm:text-4xl font-extrabold text-white">4 Langkah Menuju Rilis</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
                <div class="bg-gray-800/40 border border-gray-700/40 rounded-xl p-6 relative">
                    <div class="text-4xl font-extrabold text-indigo-500/40 mb-4">01</div>
                    <h4 class="text-lg font-bold text-white mb-2">Discovery & Plan</h4>
                    <p class="text-xs text-gray-400">Analisis kebutuhan bisnis, penentuan alur kerja, dan penyusunan spesifikasi teknis.</p>
                </div>
                <div class="bg-gray-800/40 border border-gray-700/40 rounded-xl p-6 relative">
                    <div class="text-4xl font-extrabold text-indigo-500/40 mb-4">02</div>
                    <h4 class="text-lg font-bold text-white mb-2">UI/UX Design</h4>
                    <p class="text-xs text-gray-400">Perancangan mockup wireframe interaktif & desain visual antarmuka modern.</p>
                </div>
                <div class="bg-gray-800/40 border border-gray-700/40 rounded-xl p-6 relative">
                    <div class="text-4xl font-extrabold text-indigo-500/40 mb-4">03</div>
                    <h4 class="text-lg font-bold text-white mb-2">Development</h4>
                    <p class="text-xs text-gray-400">Pengodean sistem backend & frontend dengan arsitektur bersih, cepat, dan aman.</p>
                </div>
                <div class="bg-gray-800/40 border border-gray-700/40 rounded-xl p-6 relative">
                    <div class="text-4xl font-extrabold text-indigo-500/40 mb-4">04</div>
                    <h4 class="text-lg font-bold text-white mb-2">QA & Deployment</h4>
                    <p class="text-xs text-gray-400">Pengujian otomatis, optimasi performa, rilis ke server produksi & serah terima.</p>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    @if (($sections['cta'] ?? null)?->is_enabled ?? true)
    <section class="py-20 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-indigo-900 via-purple-900 to-indigo-950 rounded-3xl p-10 sm:p-16 border border-indigo-500/30 text-center space-y-6 shadow-2xl relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-72 h-72 bg-indigo-500/20 rounded-full blur-3xl"></div>
                <h3 class="text-3xl sm:text-5xl font-extrabold text-white">Siap Memulai Proyek Website Anda?</h3>
                <p class="text-indigo-200 max-w-2xl mx-auto text-base sm:text-lg">
                    Diskusikan ide & kebutuhan bisnis Anda bersama tim pengembang berpengalaman kami secara gratis.
                </p>
                <div class="pt-4">
                    <a href="{{ route('contact') }}" wire:navigate class="inline-flex items-center px-8 py-4 rounded-xl bg-white text-indigo-950 font-bold hover:bg-gray-100 transition-all duration-300 shadow-xl hover:scale-105">
                        Hubungi Tim Kami Sekarang
                        <svg class="w-5 h-5 ml-2 text-indigo-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif
</div>
