<?php

use App\Models\Service;
use Illuminate\Support\Str;
use Livewire\Volt\Component;

new class extends Component {
    public ?int $serviceId = null;
    public string $serviceTitle = '';
    public string $serviceSlug = '';
    public string $serviceIcon = 'code-bracket';
    public string $serviceShortDescription = '';
    public string $serviceDescription = '';
    public int $serviceOrder = 0;
    public bool $serviceIsActive = true;
    public bool $showServiceModal = false;
    public ?string $successMessage = null;

    public function openServiceModal(?int $id = null): void
    {
        $this->resetValidation();
        $this->successMessage = null;

        if ($id) {
            $s = Service::findOrFail($id);
            $this->serviceId = $s->id;
            $this->serviceTitle = $s->title;
            $this->serviceSlug = $s->slug;
            $this->serviceIcon = $s->icon ?? 'code-bracket';
            $this->serviceShortDescription = $s->short_description;
            $this->serviceDescription = $s->description;
            $this->serviceOrder = $s->order;
            $this->serviceIsActive = $s->is_active;
        } else {
            $this->reset(['serviceId', 'serviceTitle', 'serviceSlug', 'serviceShortDescription', 'serviceDescription']);
            $this->serviceIcon = 'code-bracket';
            $this->serviceOrder = Service::count() + 1;
            $this->serviceIsActive = true;
        }
        $this->showServiceModal = true;
    }

    public function saveService(): void
    {
        $this->validate([
            'serviceTitle' => 'required|string|max:255',
            'serviceIcon' => 'nullable|string|max:100',
            'serviceShortDescription' => 'required|string|max:255',
            'serviceDescription' => 'required|string',
            'serviceOrder' => 'required|integer',
            'serviceIsActive' => 'required|boolean',
        ]);

        $slug = Str::slug($this->serviceTitle);

        Service::updateOrCreate(
            ['id' => $this->serviceId],
            [
                'title' => $this->serviceTitle,
                'slug' => $slug,
                'icon' => $this->serviceIcon ?: 'code-bracket',
                'short_description' => $this->serviceShortDescription,
                'description' => $this->serviceDescription,
                'order' => $this->serviceOrder,
                'is_active' => $this->serviceIsActive,
            ]
        );

        $this->showServiceModal = false;
        $this->successMessage = 'Layanan berhasil disimpan.';
    }

    public function toggleServiceActive(int $id): void
    {
        $s = Service::findOrFail($id);
        $s->is_active = ! $s->is_active;
        $s->save();
        $this->successMessage = 'Status layanan berhasil diperbarui.';
    }

    public function deleteService(int $id): void
    {
        Service::findOrFail($id)->delete();
        $this->successMessage = 'Layanan berhasil dihapus.';
    }

    public function with(): array
    {
        return [
            'services' => Service::ordered()->get(),
        ];
    }
}; ?>

<div class="space-y-4">
    @if ($successMessage)
        <x-alert variant="success">{{ $successMessage }}</x-alert>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-on-surface">Daftar Layanan</h3>
            <p class="text-xs text-on-surface-variant">Kelola paket solusi dan layanan software agensi.</p>
        </div>
        <x-primary-button type="button" wire:click="openServiceModal()">
            <span class="material-symbols-outlined text-[16px]">add</span>
            Tambah Layanan
        </x-primary-button>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-surface-container text-on-surface-variant uppercase tracking-wider border-b border-outline-variant font-bold">
                <tr>
                    <th class="py-3 px-4 w-16">Urutan</th>
                    <th class="py-3 px-4">Judul Layanan</th>
                    <th class="py-3 px-4">Deskripsi Singkat</th>
                    <th class="py-3 px-4 w-28">Status</th>
                    <th class="py-3 px-4 w-36 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @foreach ($services as $s)
                    <tr class="hover:bg-surface-container transition-colors">
                        <td class="py-3 px-4 font-mono font-semibold text-on-surface-variant">{{ $s->order }}</td>
                        <td class="py-3 px-4 font-bold text-on-surface">{{ $s->title }}</td>
                        <td class="py-3 px-4 text-on-surface-variant max-w-sm truncate">{{ $s->short_description }}</td>
                        <td class="py-3 px-4">
                            <button type="button" wire:click="toggleServiceActive({{ $s->id }})" class="cursor-pointer">
                                <x-badge :variant="$s->is_active ? 'success' : 'neutral'">
                                    {{ $s->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                                </x-badge>
                            </button>
                        </td>
                        <td class="py-3 px-4 text-right space-x-1">
                            <x-link-button wire:click="openServiceModal({{ $s->id }})">Edit</x-link-button>
                            <x-link-button variant="danger" wire:click="deleteService({{ $s->id }})" wire:confirm="Hapus layanan ini?">Hapus</x-link-button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- MODAL: SERVICE FORM -->
    @if ($showServiceModal)
        <div x-data class="fixed inset-0 bg-on-surface/60 z-50 flex items-center justify-center p-4" wire:click.self="$set('showServiceModal', false)" x-on:keydown.escape.window="$wire.set('showServiceModal', false)">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 max-w-lg w-full space-y-4 shadow-xl text-on-surface">
                <div class="flex items-center justify-between border-b border-outline-variant pb-3">
                    <h3 class="text-base font-bold text-on-surface">{{ $serviceId ? 'Edit Layanan' : 'Tambah Layanan Baru' }}</h3>
                    <button wire:click="$set('showServiceModal', false)" class="text-on-surface-variant hover:text-on-surface font-bold text-xl cursor-pointer" aria-label="Tutup">&times;</button>
                </div>
                <form wire:submit="saveService" class="space-y-3.5 text-xs">
                    <div>
                        <x-input-label value="Judul Layanan *" />
                        <x-text-input type="text" wire:model="serviceTitle" />
                        @error('serviceTitle') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="Nama Icon (contoh: code-bracket, cloud, database)" />
                        <x-text-input type="text" wire:model="serviceIcon" />
                    </div>
                    <div>
                        <x-input-label value="Deskripsi Singkat *" />
                        <x-text-input type="text" wire:model="serviceShortDescription" />
                        @error('serviceShortDescription') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="Deskripsi Lengkap *" />
                        <x-textarea-input wire:model="serviceDescription" :rows="4" />
                        @error('serviceDescription') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-3 items-center">
                        <div>
                            <x-input-label value="Urutan Tampilan *" />
                            <x-text-input type="number" wire:model="serviceOrder" />
                        </div>
                        <div class="flex items-center pt-4">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" wire:model="serviceIsActive" class="rounded border-outline text-primary focus:ring-primary">
                                <span class="font-semibold text-xs text-on-surface-variant">Status Aktif</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 pt-3 border-t border-outline-variant">
                        <x-secondary-button type="button" wire:click="$set('showServiceModal', false)">Batal</x-secondary-button>
                        <x-primary-button type="submit">Simpan Layanan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
