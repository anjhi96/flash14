<?php

use App\Models\NewsSource;
use App\Services\NewsFetcherService;
use Livewire\Volt\Component;

new class extends Component {
    public ?int $sourceId = null;
    public string $sourceName = '';
    public string $sourceUrl = '';
    public string $sourceType = 'rss';
    public string $sourceCategory = '';
    public bool $sourceIsActive = true;
    public bool $showSourceModal = false;
    public ?string $successMessage = null;

    public bool $showTestFetchModal = false;
    public string $testFetchSourceName = '';
    public array $testFetchResults = [];
    public bool $testFetchFailed = false;

    public function openSourceModal(?int $id = null): void
    {
        $this->resetValidation();
        $this->successMessage = null;

        if ($id) {
            $s = NewsSource::findOrFail($id);
            $this->sourceId = $s->id;
            $this->sourceName = $s->name;
            $this->sourceUrl = $s->url;
            $this->sourceType = $s->type;
            $this->sourceCategory = $s->category ?? '';
            $this->sourceIsActive = $s->is_active;
        } else {
            $this->reset(['sourceId', 'sourceName', 'sourceUrl', 'sourceCategory']);
            $this->sourceType = 'rss';
            $this->sourceIsActive = true;
        }

        $this->showSourceModal = true;
    }

    public function saveSource(): void
    {
        $validated = $this->validate([
            'sourceName' => 'required|string|max:255',
            'sourceUrl' => 'required|url|max:500',
            'sourceType' => 'required|in:rss,api',
            'sourceCategory' => 'nullable|string|max:100',
            'sourceIsActive' => 'boolean',
        ], [
            'sourceName.required' => 'Nama sumber wajib diisi.',
            'sourceUrl.required' => 'URL wajib diisi.',
            'sourceUrl.url' => 'URL tidak valid.',
        ]);

        NewsSource::updateOrCreate(
            ['id' => $this->sourceId],
            [
                'name' => $validated['sourceName'],
                'url' => $validated['sourceUrl'],
                'type' => $validated['sourceType'],
                'category' => $validated['sourceCategory'] ?: null,
                'is_active' => $validated['sourceIsActive'] ?? false,
            ]
        );

        $this->showSourceModal = false;
        $this->successMessage = 'Sumber berita berhasil disimpan.';
    }

    public function toggleSourceActive(int $id): void
    {
        $s = NewsSource::findOrFail($id);
        $s->is_active = ! $s->is_active;
        $s->save();
        $this->successMessage = 'Status sumber berita berhasil diperbarui.';
    }

    public function deleteSource(int $id): void
    {
        NewsSource::findOrFail($id)->delete();
        $this->successMessage = 'Sumber berita berhasil dihapus.';
    }

    /**
     * Fetch a URL fresh (bypasses cache) and preview the latest items —
     * nothing is persisted, this is purely a validity/preview check.
     */
    public function testFetch(string $url, string $name): void
    {
        $this->testFetchSourceName = $name;
        $this->testFetchResults = app(NewsFetcherService::class)->testFetch($url);
        $this->testFetchFailed = $this->testFetchResults === [];
        $this->showTestFetchModal = true;
    }

    public function with(): array
    {
        return [
            'sources' => NewsSource::orderBy('name')->get(),
        ];
    }
}; ?>

<div class="space-y-4">
    @if ($successMessage)
        <x-alert variant="success">{{ $successMessage }}</x-alert>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-on-surface">Sumber Berita</h3>
            <p class="text-xs text-on-surface-variant">Kelola feed RSS/API yang di-fetch otomatis oleh sistem auto-blog.</p>
        </div>
        <x-primary-button type="button" wire:click="openSourceModal()">
            <span class="material-symbols-outlined text-[16px]">add</span>
            Tambah Sumber
        </x-primary-button>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-surface-container text-on-surface-variant uppercase tracking-wider border-b border-outline-variant font-bold">
                <tr>
                    <th class="py-3 px-4">Nama</th>
                    <th class="py-3 px-4">URL</th>
                    <th class="py-3 px-4 w-28">Kategori</th>
                    <th class="py-3 px-4 w-28">Status</th>
                    <th class="py-3 px-4 w-32">Terakhir Fetch</th>
                    <th class="py-3 px-4 w-48 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse ($sources as $source)
                    <tr class="hover:bg-surface-container transition-colors">
                        <td class="py-3 px-4 font-bold text-on-surface">{{ $source->name }}</td>
                        <td class="py-3 px-4 text-on-surface-variant max-w-xs truncate" title="{{ $source->url }}">{{ $source->url }}</td>
                        <td class="py-3 px-4">
                            @if ($source->category)
                                <x-badge>{{ $source->category }}</x-badge>
                            @else
                                <span class="text-on-surface-variant">—</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <button type="button" wire:click="toggleSourceActive({{ $source->id }})" class="cursor-pointer">
                                <x-badge :variant="$source->is_active ? 'success' : 'neutral'">
                                    {{ $source->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                                </x-badge>
                            </button>
                        </td>
                        <td class="py-3 px-4 text-on-surface-variant">
                            {{ $source->last_fetched_at?->diffForHumans() ?? 'Belum pernah' }}
                        </td>
                        <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
                            <x-link-button wire:click="testFetch('{{ $source->url }}', '{{ $source->name }}')">Test Fetch</x-link-button>
                            <x-link-button wire:click="openSourceModal({{ $source->id }})">Edit</x-link-button>
                            <x-link-button variant="danger" wire:click="deleteSource({{ $source->id }})" wire:confirm="Hapus sumber berita ini?">Hapus</x-link-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-on-surface-variant">Belum ada sumber berita. Tambahkan feed RSS pertama Anda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- MODAL: SOURCE FORM -->
    @if ($showSourceModal)
        <div x-data class="fixed inset-0 bg-on-surface/60 z-50 flex items-center justify-center p-4" wire:click.self="$set('showSourceModal', false)" x-on:keydown.escape.window="$wire.set('showSourceModal', false)">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 max-w-lg w-full space-y-4 shadow-xl text-on-surface">
                <div class="flex items-center justify-between border-b border-outline-variant pb-3">
                    <h3 class="text-base font-bold text-on-surface">{{ $sourceId ? 'Edit Sumber Berita' : 'Tambah Sumber Berita' }}</h3>
                    <button wire:click="$set('showSourceModal', false)" class="text-on-surface-variant hover:text-on-surface font-bold text-xl cursor-pointer" aria-label="Tutup">&times;</button>
                </div>
                <form wire:submit="saveSource" class="space-y-3.5 text-xs">
                    <div>
                        <x-input-label value="Nama Sumber *" />
                        <x-text-input type="text" wire:model="sourceName" placeholder="Contoh: TechCrunch" />
                        @error('sourceName') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="URL Feed *" />
                        <x-text-input type="text" wire:model="sourceUrl" placeholder="https://contoh.com/feed/" />
                        @error('sourceUrl') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-input-label value="Tipe *" />
                            <x-select-input wire:model="sourceType">
                                <option value="rss">RSS</option>
                                <option value="api">API (belum didukung)</option>
                            </x-select-input>
                        </div>
                        <div>
                            <x-input-label value="Kategori" />
                            <x-text-input type="text" wire:model="sourceCategory" placeholder="tech / business / security" />
                            @error('sourceCategory') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" wire:model="sourceIsActive" class="rounded border-outline text-primary focus:ring-primary">
                        <span class="font-semibold text-xs text-on-surface-variant">Status Aktif</span>
                    </label>
                    <div class="flex justify-end space-x-2 pt-3 border-t border-outline-variant">
                        <x-secondary-button type="button" wire:click="$set('showSourceModal', false)">Batal</x-secondary-button>
                        <x-primary-button type="submit">Simpan Sumber</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL: TEST FETCH PREVIEW -->
    @if ($showTestFetchModal)
        <div x-data class="fixed inset-0 bg-on-surface/60 z-50 flex items-center justify-center p-4" wire:click.self="$set('showTestFetchModal', false)" x-on:keydown.escape.window="$wire.set('showTestFetchModal', false)">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 max-w-lg w-full max-h-[80vh] overflow-y-auto space-y-4 shadow-xl text-on-surface">
                <div class="flex items-center justify-between border-b border-outline-variant pb-3">
                    <h3 class="text-base font-bold text-on-surface">Pratinjau: {{ $testFetchSourceName }}</h3>
                    <button wire:click="$set('showTestFetchModal', false)" class="text-on-surface-variant hover:text-on-surface font-bold text-xl cursor-pointer" aria-label="Tutup">&times;</button>
                </div>

                @if ($testFetchFailed)
                    <x-alert variant="error" :dismissible="false">
                        Gagal mengambil atau mem-parsing feed ini. Periksa kembali URL-nya.
                    </x-alert>
                @else
                    <div class="space-y-3 text-xs">
                        @foreach ($testFetchResults as $item)
                            <div class="p-3 rounded-lg border border-outline-variant bg-surface-container-lowest">
                                <p class="font-bold text-on-surface">{{ $item['title'] }}</p>
                                <p class="text-on-surface-variant mt-1 line-clamp-2">{{ $item['summary'] }}</p>
                                <a href="{{ $item['link'] }}" target="_blank" rel="noopener noreferrer" class="text-primary hover:underline text-[11px] mt-1 inline-block">{{ $item['link'] }}</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
