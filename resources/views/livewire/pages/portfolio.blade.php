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

<div class="bg-gray-900 text-white min-h-screen">
    <x-slot name="title">Portofolio & Case Studies Proyek Web - FlashDev</x-slot>
    <x-slot name="description">Lihat hasil karya pembuatan website dan aplikasi web custom yang telah kami selesaikan untuk berbagai industri.</x-slot>
    <!-- Header Banner -->
    <section class="py-20 border-b border-gray-800 bg-gray-950/60 text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl space-y-4">
            <span class="px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 text-xs font-semibold uppercase tracking-wider">
                Showcase & Case Studies
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold">Portofolio Karya Kami</h1>
            <p class="text-gray-400 text-lg">
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
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 {{ $selectedCategory === $cat ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'bg-gray-800/80 text-gray-300 hover:bg-gray-700 hover:text-white border border-gray-700/60' }}"
                    >
                        {{ $cat }}
                    </button>
                @endforeach
            </div>

            <!-- Projects Grid -->
            @if ($projects->isEmpty())
                <div class="text-center py-16 bg-gray-800/30 rounded-2xl border border-gray-800">
                    <p class="text-gray-400 text-base">Belum ada proyek dalam kategori "{{ $selectedCategory }}".</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($projects as $project)
                        <div class="bg-gray-800/80 rounded-2xl overflow-hidden border border-gray-700/60 hover:border-indigo-500/50 transition-all duration-300 group flex flex-col hover:-translate-y-1 shadow-lg">
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
                                <div class="space-y-2">
                                    <h3 class="text-xl font-bold text-white group-hover:text-indigo-400 transition-colors">{{ $project->title }}</h3>
                                    <p class="text-xs text-indigo-300 font-medium">Klien: {{ $project->client ?? 'Confidential' }}</p>
                                    <p class="text-gray-400 text-sm leading-relaxed line-clamp-3">{{ $project->description }}</p>
                                </div>

                                @if ($project->project_url)
                                    <a href="{{ $project->project_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-xs font-semibold text-indigo-400 hover:text-indigo-300 pt-3 border-t border-gray-700/60">
                                        Kunjungi Website
                                        <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>
