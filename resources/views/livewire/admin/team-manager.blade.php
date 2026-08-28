<?php

use App\Models\TeamMember;
use Livewire\Volt\Component;

new class extends Component {
    public ?int $teamMemberId = null;
    public string $teamName = '';
    public string $teamPosition = '';
    public string $teamPhoto = '';
    public string $teamBio = '';
    public string $teamLinkedin = '';
    public int $teamOrder = 0;
    public bool $showTeamModal = false;
    public ?string $successMessage = null;

    public function openTeamModal(?int $id = null): void
    {
        $this->resetValidation();
        $this->successMessage = null;

        if ($id) {
            $m = TeamMember::findOrFail($id);
            $this->teamMemberId = $m->id;
            $this->teamName = $m->name;
            $this->teamPosition = $m->position;
            $this->teamPhoto = $m->photo ?? '';
            $this->teamBio = $m->bio ?? '';
            $this->teamLinkedin = $m->linkedin_url ?? '';
            $this->teamOrder = $m->order;
        } else {
            $this->reset(['teamMemberId', 'teamName', 'teamPosition', 'teamPhoto', 'teamBio', 'teamLinkedin']);
            $this->teamOrder = TeamMember::count() + 1;
        }
        $this->showTeamModal = true;
    }

    public function saveTeamMember(): void
    {
        $this->validate([
            'teamName' => 'required|string|max:255',
            'teamPosition' => 'required|string|max:255',
            'teamPhoto' => 'nullable|url|max:500',
            'teamBio' => 'nullable|string',
            'teamLinkedin' => 'nullable|url|max:500',
            'teamOrder' => 'required|integer',
        ]);

        TeamMember::updateOrCreate(
            ['id' => $this->teamMemberId],
            [
                'name' => $this->teamName,
                'position' => $this->teamPosition,
                'photo' => $this->teamPhoto,
                'bio' => $this->teamBio,
                'linkedin_url' => $this->teamLinkedin,
                'order' => $this->teamOrder,
            ]
        );

        $this->showTeamModal = false;
        $this->successMessage = 'Anggota tim berhasil disimpan.';
    }

    public function deleteTeamMember(int $id): void
    {
        TeamMember::findOrFail($id)->delete();
        $this->successMessage = 'Anggota tim berhasil dihapus.';
    }

    public function with(): array
    {
        return [
            'teamMembers' => TeamMember::ordered()->get(),
        ];
    }
}; ?>

<div class="space-y-4">
    @if ($successMessage)
        <x-alert variant="success">{{ $successMessage }}</x-alert>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-on-surface">Anggota Tim Agensi</h3>
            <p class="text-xs text-on-surface-variant">Struktur engineer dan personel kunci.</p>
        </div>
        <x-primary-button type="button" wire:click="openTeamModal()">
            <span class="material-symbols-outlined text-[16px]">person_add</span>
            Tambah Anggota Tim
        </x-primary-button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($teamMembers as $m)
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4 text-center space-y-3 flex flex-col justify-between hover:border-outline transition-all">
                <div class="space-y-2.5">
                    <div class="w-16 h-16 rounded-full overflow-hidden mx-auto bg-surface-container border-2 border-primary/60">
                        @if ($m->photo)
                            <img src="{{ $m->photo }}" alt="{{ $m->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center font-bold text-on-surface-variant text-base">{{ substr($m->name, 0, 1) }}</div>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-on-surface text-xs">{{ $m->name }}</h4>
                        <p class="text-[11px] text-primary font-semibold mt-0.5">{{ $m->position }}</p>
                    </div>
                    <p class="text-[11px] text-on-surface-variant line-clamp-2">{{ $m->bio }}</p>
                </div>

                <div class="pt-3 border-t border-outline-variant flex justify-center space-x-1">
                    <x-link-button wire:click="openTeamModal({{ $m->id }})">Edit</x-link-button>
                    <x-link-button variant="danger" wire:click="deleteTeamMember({{ $m->id }})" wire:confirm="Hapus anggota tim ini?">Hapus</x-link-button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- MODAL: TEAM MEMBER FORM -->
    @if ($showTeamModal)
        <div x-data class="fixed inset-0 bg-on-surface/60 z-50 flex items-center justify-center p-4" wire:click.self="$set('showTeamModal', false)" x-on:keydown.escape.window="$wire.set('showTeamModal', false)">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 max-w-lg w-full space-y-4 shadow-xl text-on-surface">
                <div class="flex items-center justify-between border-b border-outline-variant pb-3">
                    <h3 class="text-base font-bold text-on-surface">{{ $teamMemberId ? 'Edit Anggota Tim' : 'Tambah Anggota Tim' }}</h3>
                    <button wire:click="$set('showTeamModal', false)" class="text-on-surface-variant hover:text-on-surface font-bold text-xl cursor-pointer" aria-label="Tutup">&times;</button>
                </div>
                <form wire:submit="saveTeamMember" class="space-y-3 text-xs">
                    <div>
                        <x-input-label value="Nama Lengkap *" />
                        <x-text-input type="text" wire:model="teamName" />
                        @error('teamName') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="Jabatan / Posisi *" />
                        <x-text-input type="text" wire:model="teamPosition" placeholder="Lead Software Engineer" />
                        @error('teamPosition') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="URL Foto Profil" />
                        <x-text-input type="text" wire:model="teamPhoto" placeholder="https://..." />
                    </div>
                    <div>
                        <x-input-label value="Biografi Singkat" />
                        <x-textarea-input wire:model="teamBio" :rows="3" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-input-label value="URL LinkedIn" />
                            <x-text-input type="text" wire:model="teamLinkedin" placeholder="https://linkedin.com/in/..." />
                        </div>
                        <div>
                            <x-input-label value="Urutan Tampilan" />
                            <x-text-input type="number" wire:model="teamOrder" />
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 pt-3 border-t border-outline-variant">
                        <x-secondary-button type="button" wire:click="$set('showTeamModal', false)">Batal</x-secondary-button>
                        <x-primary-button type="submit">Simpan Anggota Tim</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
