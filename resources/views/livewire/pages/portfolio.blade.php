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

<div class="bg-surface text-on-surface min-h-screen transition-colors duration-200">
    <x-slot name="title">Portofolio & Studi Kasus Sistem - FlashDev</x-slot>
    <x-slot name="description">Lihat hasil karya pembuatan website dan aplikasi web custom yang telah kami selesaikan untuk berbagai industri.</x-slot>

    <!-- Header Banner -->
    <section class="py-14 border-b border-outline-variant bg-surface-container-lowest">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl text-center space-y-3">
            <x-badge variant="primary">Showcase Proyek</x-badge>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-on-surface">Portofolio & Rekam Jejak Sistem</h1>
            <p class="text-sm text-on-surface-variant max-w-xl mx-auto leading-relaxed">
                Jelajahi implementasi nyata sistem enterprise, aplikasi SaaS, dan platform e-commerce yang telah kami selesaikan.
            </p>
        </div>
    </section>

    <!-- Category Filter & Grid -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Filter Chips -->
            <div class="flex flex-wrap items-center justify-center gap-2">
                @foreach ($categories as $cat)
                    <button
                        wire:click="selectCategory('{{ $cat }}')"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer {{ $selectedCategory === $cat ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container border border-outline' }}"
                    >
                        {{ $cat }}
                    </button>
                @endforeach
            </div>

            <!-- Projects Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse ($projects as $project)
                    <div class="bg-surface-container-lowest rounded-xl overflow-hidden border border-outline-variant flex flex-col justify-between hover:border-outline transition-all">
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
                                    <span>Kunjungi Live Site</span>
                                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-on-surface-variant text-xs">
                        Belum ada proyek dalam kategori ini.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
