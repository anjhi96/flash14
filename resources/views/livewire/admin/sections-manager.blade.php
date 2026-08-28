<?php

use App\Models\PageSection;
use Livewire\Volt\Component;

new class extends Component {
    public string $editingTechStack = '';
    public bool $showTechStackEditor = false;
    public ?string $successMessage = null;

    public function toggleSection(string $key): void
    {
        $section = PageSection::where('section_key', $key)->first();
        if ($section) {
            $section->is_enabled = ! $section->is_enabled;
            $section->save();
        }
        $this->successMessage = 'Pengaturan section berhasil diperbarui.';
    }

    public function openTechStackEditor(): void
    {
        $section = PageSection::where('section_key', 'tech_stack')->first();
        $items = $section?->settings['items'] ?? [];
        $this->editingTechStack = implode("\n", $items);
        $this->showTechStackEditor = true;
    }

    public function saveTechStack(): void
    {
        $section = PageSection::firstOrCreate(
            ['section_key' => 'tech_stack'],
            ['page' => 'home', 'section_name' => 'Tech Stack Banner', 'is_enabled' => true, 'order' => 3]
        );
        $items = array_values(array_filter(array_map('trim', explode("\n", $this->editingTechStack))));
        $settings = $section->settings ?? [];
        $settings['items'] = $items;
        $section->settings = $settings;
        $section->save();
        $this->showTechStackEditor = false;
        $this->successMessage = 'Daftar Tech Stack berhasil diperbarui.';
    }

    public function with(): array
    {
        return [
            'pageSections' => PageSection::forPage('home'),
        ];
    }
}; ?>

<div class="space-y-4">
    @if ($successMessage)
        <x-alert variant="success">{{ $successMessage }}</x-alert>
    @endif

    <div>
        <h2 class="text-lg font-bold text-on-surface">Pengaturan Modul Section Beranda</h2>
        <p class="text-xs text-on-surface-variant">Aktifkan atau non-aktifkan bagian halaman beranda tanpa menyentuh kode program.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($pageSections as $section)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex items-center justify-between gap-4 transition-all">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full shrink-0 {{ $section->is_enabled ? 'bg-state-success' : 'bg-state-error' }}"></span>
                        <h4 class="text-xs font-bold text-on-surface truncate">{{ $section->section_name }}</h4>
                    </div>
                    <p class="text-[11px] text-on-surface-variant mt-0.5 ml-4">
                        Key: <code class="bg-surface-container px-1 py-0.5 rounded text-primary font-mono">{{ $section->section_key }}</code>
                        &middot; Urutan ke-{{ $section->order }}
                    </p>
                </div>
                <div class="flex items-center gap-2.5 shrink-0">
                    @if ($section->section_key === 'tech_stack')
                        <x-link-button wire:click="openTechStackEditor">Edit Items</x-link-button>
                    @endif
                    <x-switch-input
                        :checked="$section->is_enabled"
                        wire:click="toggleSection('{{ $section->section_key }}')"
                        title="{{ $section->is_enabled ? 'Klik untuk non-aktifkan' : 'Klik untuk aktifkan' }}"
                    />
                </div>
            </div>
        @endforeach
    </div>

    <!-- Tech Stack Editor Modal -->
    @if ($showTechStackEditor)
        <div x-data class="fixed inset-0 z-50 flex items-center justify-center bg-on-surface/60" wire:click.self="$set('showTechStackEditor', false)" x-on:keydown.escape.window="$wire.set('showTechStackEditor', false)">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-md mx-4 shadow-xl space-y-4 text-on-surface">
                <div class="flex items-center justify-between border-b border-outline-variant pb-3">
                    <h3 class="text-base font-bold text-on-surface">Edit Daftar Tech Stack</h3>
                    <button wire:click="$set('showTechStackEditor', false)" class="text-on-surface-variant hover:text-on-surface text-xl font-bold leading-none cursor-pointer" aria-label="Tutup">&times;</button>
                </div>
                <p class="text-xs text-on-surface-variant">Tuliskan satu nama teknologi per baris. Daftar ini akan ditampilkan di banner beranda.</p>
                <div>
                    <x-input-label value="Teknologi (1 Per Baris)" class="uppercase tracking-wider" />
                    <x-textarea-input
                        wire:model="editingTechStack"
                        :rows="7"
                        class="font-mono leading-relaxed"
                        placeholder="Laravel&#10;Livewire&#10;Tailwind CSS&#10;Vue.js&#10;PostgreSQL"
                    />
                </div>
                <div class="flex gap-2.5 pt-1 border-t border-outline-variant">
                    <x-secondary-button class="flex-1" wire:click="$set('showTechStackEditor', false)">Batal</x-secondary-button>
                    <x-primary-button class="flex-1" wire:click="saveTechStack">Simpan</x-primary-button>
                </div>
            </div>
        </div>
    @endif
</div>
