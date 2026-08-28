<?php

use App\Models\Project;
use Illuminate\Support\Str;
use Livewire\Volt\Component;

new class extends Component {
    public ?int $projectId = null;
    public string $projectTitle = '';
    public string $projectSlug = '';
    public string $projectClient = '';
    public string $projectCategory = 'Web Dev';
    public string $projectDescription = '';
    public string $projectThumbnail = '';
    public string $projectUrl = '';
    public bool $projectIsFeatured = false;
    public int $projectOrder = 0;
    public bool $showProjectModal = false;
    public ?string $successMessage = null;

    public function openProjectModal(?int $id = null): void
    {
        $this->resetValidation();
        $this->successMessage = null;

        if ($id) {
            $p = Project::findOrFail($id);
            $this->projectId = $p->id;
            $this->projectTitle = $p->title;
            $this->projectSlug = $p->slug;
            $this->projectClient = $p->client ?? '';
            $this->projectCategory = $p->category ?? 'Web Dev';
            $this->projectDescription = $p->description ?? '';
            $this->projectThumbnail = $p->thumbnail ?? '';
            $this->projectUrl = $p->project_url ?? '';
            $this->projectIsFeatured = $p->is_featured;
            $this->projectOrder = $p->order;
        } else {
            $this->reset(['projectId', 'projectTitle', 'projectSlug', 'projectClient', 'projectDescription', 'projectThumbnail', 'projectUrl']);
            $this->projectCategory = 'E-Commerce';
            $this->projectIsFeatured = false;
            $this->projectOrder = Project::count() + 1;
        }
        $this->showProjectModal = true;
    }

    public function saveProject(): void
    {
        $this->validate([
            'projectTitle' => 'required|string|max:255',
            'projectCategory' => 'required|string|max:100',
            'projectDescription' => 'required|string',
            'projectClient' => 'nullable|string|max:255',
            'projectThumbnail' => 'nullable|url|max:500',
            'projectUrl' => 'nullable|url|max:500',
            'projectOrder' => 'required|integer',
            'projectIsFeatured' => 'required|boolean',
        ]);

        $slug = Str::slug($this->projectTitle);

        Project::updateOrCreate(
            ['id' => $this->projectId],
            [
                'title' => $this->projectTitle,
                'slug' => $slug,
                'client' => $this->projectClient,
                'category' => $this->projectCategory,
                'description' => $this->projectDescription,
                'thumbnail' => $this->projectThumbnail,
                'project_url' => $this->projectUrl,
                'is_featured' => $this->projectIsFeatured,
                'order' => $this->projectOrder,
            ]
        );

        $this->showProjectModal = false;
        $this->successMessage = 'Proyek portofolio berhasil disimpan.';
    }

    public function toggleProjectFeatured(int $id): void
    {
        $p = Project::findOrFail($id);
        $p->is_featured = ! $p->is_featured;
        $p->save();
        $this->successMessage = 'Status featured proyek berhasil diperbarui.';
    }

    public function deleteProject(int $id): void
    {
        Project::findOrFail($id)->delete();
        $this->successMessage = 'Proyek berhasil dihapus.';
    }

    public function with(): array
    {
        return [
            'projects' => Project::ordered()->get(),
        ];
    }
}; ?>

<div class="space-y-4">
    @if ($successMessage)
        <x-alert variant="success">{{ $successMessage }}</x-alert>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-on-surface">Portofolio Proyek</h3>
            <p class="text-xs text-on-surface-variant">Koleksi karya & studi kasus sistem klien.</p>
        </div>
        <x-primary-button type="button" wire:click="openProjectModal()">
            <span class="material-symbols-outlined text-[16px]">add</span>
            Tambah Proyek
        </x-primary-button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach ($projects as $p)
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden flex flex-col justify-between hover:border-outline transition-all">
                <div>
                    <div class="aspect-video bg-surface-container relative overflow-hidden">
                        @if ($p->thumbnail)
                            <img src="{{ $p->thumbnail }}" alt="{{ $p->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-xs text-on-surface-variant">No Image</div>
                        @endif
                        <div class="absolute top-2.5 left-2.5">
                            <span class="px-2.5 py-1 bg-black/70 backdrop-blur-xs text-white text-[10px] font-semibold rounded-md">
                                {{ $p->category }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4 space-y-1.5">
                        <h4 class="font-bold text-on-surface text-sm">{{ $p->title }}</h4>
                        <p class="text-xs text-primary font-semibold">Klien: {{ $p->client ?? 'N/A' }}</p>
                        <p class="text-xs text-on-surface-variant line-clamp-2">{{ $p->description }}</p>
                    </div>
                </div>

                <div class="p-4 pt-3 flex items-center justify-between border-t border-outline-variant mt-3">
                    <button type="button" wire:click="toggleProjectFeatured({{ $p->id }})" class="cursor-pointer">
                        <x-badge :variant="$p->is_featured ? 'primary' : 'neutral'">
                            <span class="material-symbols-outlined text-[12px]">star</span>
                            {{ $p->is_featured ? 'FEATURED' : 'STANDAR' }}
                        </x-badge>
                    </button>

                    <div class="space-x-1">
                        <x-link-button wire:click="openProjectModal({{ $p->id }})">Edit</x-link-button>
                        <x-link-button variant="danger" wire:click="deleteProject({{ $p->id }})" wire:confirm="Hapus proyek ini?">Hapus</x-link-button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- MODAL: PROJECT FORM -->
    @if ($showProjectModal)
        <div x-data class="fixed inset-0 bg-on-surface/60 z-50 flex items-center justify-center p-4" wire:click.self="$set('showProjectModal', false)" x-on:keydown.escape.window="$wire.set('showProjectModal', false)">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 max-w-lg w-full space-y-4 shadow-xl text-on-surface">
                <div class="flex items-center justify-between border-b border-outline-variant pb-3">
                    <h3 class="text-base font-bold text-on-surface">{{ $projectId ? 'Edit Proyek' : 'Tambah Proyek Baru' }}</h3>
                    <button wire:click="$set('showProjectModal', false)" class="text-on-surface-variant hover:text-on-surface font-bold text-xl cursor-pointer" aria-label="Tutup">&times;</button>
                </div>
                <form wire:submit="saveProject" class="space-y-3 text-xs">
                    <div>
                        <x-input-label value="Judul Proyek *" />
                        <x-text-input type="text" wire:model="projectTitle" />
                        @error('projectTitle') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-input-label value="Nama Klien" />
                            <x-text-input type="text" wire:model="projectClient" />
                        </div>
                        <div>
                            <x-input-label value="Kategori *" />
                            <x-select-input wire:model="projectCategory">
                                <option value="E-Commerce">E-Commerce</option>
                                <option value="Custom Web App">Custom Web App</option>
                                <option value="Company Profile">Company Profile</option>
                                <option value="SaaS App">SaaS App</option>
                            </x-select-input>
                        </div>
                    </div>
                    <div>
                        <x-input-label value="Deskripsi Proyek *" />
                        <x-textarea-input wire:model="projectDescription" :rows="3" />
                        @error('projectDescription') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="URL Gambar Thumbnail" />
                        <x-text-input type="text" wire:model="projectThumbnail" placeholder="https://..." />
                        @error('projectThumbnail') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="URL Live Website" />
                        <x-text-input type="text" wire:model="projectUrl" placeholder="https://..." />
                    </div>
                    <div class="grid grid-cols-2 gap-3 items-center">
                        <div>
                            <x-input-label value="Urutan" />
                            <x-text-input type="number" wire:model="projectOrder" />
                        </div>
                        <div class="flex items-center pt-4">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" wire:model="projectIsFeatured" class="rounded border-outline text-primary focus:ring-primary">
                                <span class="font-semibold text-xs text-on-surface-variant">Featured di Home</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 pt-3 border-t border-outline-variant">
                        <x-secondary-button type="button" wire:click="$set('showProjectModal', false)">Batal</x-secondary-button>
                        <x-primary-button type="submit">Simpan Proyek</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
