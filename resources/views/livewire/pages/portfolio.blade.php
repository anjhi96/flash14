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

<div class="bg-[#F8FAFC] dark:bg-[#0B0F17] text-slate-900 dark:text-slate-100 min-h-screen transition-colors duration-200">
    <x-slot name="title">Portofolio & Studi Kasus Sistem - FlashDev</x-slot>
    <x-slot name="description">Lihat hasil karya pembuatan website dan aplikasi web custom yang telah kami selesaikan untuk berbagai industri.</x-slot>

    <!-- Header Banner -->
    <section class="py-14 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-[#111722]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl text-center space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-amber-50 dark:bg-amber-500/10 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30 text-xs font-semibold uppercase tracking-wider">
                <span class="material-symbols-outlined text-[14px]">folder_special</span>
                Showcase Proyek
            </span>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-slate-900 dark:text-white">Portofolio & Rekam Jejak Sistem</h1>
            <p class="text-sm text-slate-600 dark:text-slate-400 max-w-xl mx-auto leading-relaxed">
                Jelajahi implementasi nyata sistem enterprise, aplikasi SaaS, dan platform e-commerce yang telah kami selesaikan.
            </p>
        </div>
    </section>

    <!-- Category Filter & Grid -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- M3 Filter Chips -->
            <div class="flex flex-wrap items-center justify-center gap-2">
                @foreach ($categories as $cat)
                    <button 
                        wire:click="selectCategory('{{ $cat }}')"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer {{ $selectedCategory === $cat ? 'bg-amber-500 text-slate-950 shadow-xs' : 'bg-white dark:bg-[#111722] text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800' }}"
                    >
                        {{ $cat }}
                    </button>
                @endforeach
            </div>

            <!-- Projects Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse ($projects as $project)
                    <div class="bg-white dark:bg-[#111722] rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 flex flex-col justify-between hover:border-slate-300 dark:hover:border-slate-700 transition-all shadow-2xs">
                        <div>
                            <div class="relative overflow-hidden aspect-video bg-slate-100 dark:bg-[#161F2E]">
                                @if ($project->thumbnail)
                                    <img src="{{ $project->thumbnail }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-xs text-slate-400">No Image</div>
                                @endif
                                <div class="absolute top-2.5 left-2.5">
                                    <span class="px-2.5 py-0.5 bg-slate-900/80 backdrop-blur-xs text-white text-[10px] font-semibold rounded-md border border-slate-700">
                                        {{ $project->category }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-4 space-y-1.5">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ $project->title }}</h3>
                                <p class="text-xs text-amber-700 dark:text-amber-400 font-semibold">Klien: {{ $project->client }}</p>
                                <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2">{{ $project->description }}</p>
                            </div>
                        </div>
                        @if ($project->project_url)
                            <div class="p-4 pt-0">
                                <a href="{{ $project->project_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 pt-2 border-t border-slate-200 dark:border-slate-800 w-full">
                                    <span>Kunjungi Live Site</span>
                                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-slate-500 text-xs">
                        Belum ada proyek dalam kategori ini.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
