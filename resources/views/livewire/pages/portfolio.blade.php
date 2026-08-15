<?php

use App\Models\Project;
use Livewire\Volt\Component;

new class extends Component {
    public string $selectedCategory = 'All';

    public function selectCategory(string $category): void
    {
        $this->selectedCategory = $category;
    }

    public function with(): array
    {
        $query = Project::orderBy('order');

        if ($this->selectedCategory !== 'All') {
            $query->where('category', $this->selectedCategory);
        }

        return [
            'projects' => $query->get(),
            'categories' => ['All', 'E-Commerce', 'Custom Web App', 'Company Profile', 'SaaS App'],
        ];
    }
}; ?>

<div class="bg-[#F8FAFC] dark:bg-[#0B0F17] text-slate-900 dark:text-white min-h-screen transition-colors duration-300">
    <x-slot name="title">Portofolio & Case Studies Proyek Web - FlashDev</x-slot>
    <x-slot name="description">Lihat hasil karya pembuatan website dan aplikasi web custom yang telah kami selesaikan untuk berbagai industri.</x-slot>
    <!-- Header Banner -->
    <section class="py-20 border-b border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#080C13] text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl space-y-4">
            <span class="px-3.5 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-700 dark:text-amber-400 text-xs font-bold uppercase tracking-wider">
                Showcase & Case Studies
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 dark:text-white">Portofolio Karya Kami</h1>
            <p class="text-slate-600 dark:text-gray-400 text-lg">
                Jelajahi berbagai proyek aplikasi web, sistem e-commerce, dan solusi software custom yang telah kami selesaikan untuk klien kami.
            </p>
        </div>
    </section>

    <!-- Category Filter & Grid -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <!-- Category Filter Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-3">
                @foreach ($categories as $cat)
                    <button 
                        wire:click="selectCategory('{{ $cat }}')"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 {{ $selectedCategory === $cat ? 'bg-slate-900 dark:bg-amber-500 text-amber-400 dark:text-slate-950 shadow-md font-bold' : 'bg-white dark:bg-[#131A26] text-slate-700 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-amber-400 border border-slate-200 dark:border-slate-800' }}"
                    >
                        {{ $cat }}
                    </button>
                @endforeach
            </div>

            <!-- Projects Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($projects as $project)
                    <div class="bg-white dark:bg-[#131A26] rounded-2xl overflow-hidden border border-slate-200/90 dark:border-slate-800 hover:border-amber-400 dark:hover:border-amber-500/40 hover:shadow-md transition-all duration-300 group flex flex-col">
                        <div class="relative overflow-hidden aspect-video bg-slate-100 dark:bg-slate-900">
                            @if ($project->thumbnail)
                                <img src="{{ $project->thumbnail }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-slate-100 dark:bg-slate-900 flex items-center justify-center text-slate-400 dark:text-gray-500">No Image</div>
                            @endif
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 bg-white/95 dark:bg-[#0B0F17]/90 backdrop-blur text-slate-900 dark:text-amber-300 text-xs font-bold rounded-full border border-slate-200 dark:border-amber-500/30 shadow-xs">
                                    {{ $project->category }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <h4 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ $project->title }}</h4>
                                <p class="text-xs text-amber-600 dark:text-amber-400/80 font-semibold mt-1">Klien: {{ $project->client }}</p>
                                <p class="text-slate-600 dark:text-gray-400 text-sm mt-2 line-clamp-3">{{ $project->description }}</p>
                            </div>
                            @if ($project->project_url)
                                <a href="{{ $project->project_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-xs font-bold text-slate-700 dark:text-gray-300 hover:text-slate-900 dark:hover:text-white pt-2 border-t border-slate-100 dark:border-slate-800">
                                    Kunjungi Live Site
                                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-slate-500 dark:text-gray-400">
                        Belum ada proyek dalam kategori ini.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
