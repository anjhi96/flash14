<?php

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    // Category state
    public ?int $categoryId = null;
    public string $categoryName = '';
    public string $categorySlug = '';
    public string $categoryDescription = '';
    public bool $showCategoryModal = false;

    // Tag state
    public ?int $tagId = null;
    public string $tagName = '';
    public bool $showTagModal = false;

    public ?string $successMessage = null;

    public function updatedCategoryName(string $value): void
    {
        if (! $this->categoryId) {
            $this->categorySlug = Str::slug($value);
        }
    }

    public function openCategoryModal(?int $id = null): void
    {
        $this->resetValidation();
        $this->successMessage = null;

        if ($id) {
            $c = Category::findOrFail($id);
            $this->categoryId = $c->id;
            $this->categoryName = $c->name;
            $this->categorySlug = $c->slug;
            $this->categoryDescription = $c->description ?? '';
        } else {
            $this->reset(['categoryId', 'categoryName', 'categorySlug', 'categoryDescription']);
        }

        $this->showCategoryModal = true;
    }

    public function saveCategory(): void
    {
        $validated = $this->validate([
            'categoryName' => 'required|string|max:255',
            'categorySlug' => ['required', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($this->categoryId)],
            'categoryDescription' => 'nullable|string|max:500',
        ], [
            'categoryName.required' => 'Nama kategori wajib diisi.',
            'categorySlug.unique' => 'Slug ini sudah dipakai kategori lain.',
        ]);

        Category::updateOrCreate(['id' => $this->categoryId], [
            'name' => $validated['categoryName'],
            'slug' => Str::slug($validated['categorySlug']),
            'description' => $validated['categoryDescription'] ?: null,
        ]);

        $this->showCategoryModal = false;
        $this->successMessage = 'Kategori berhasil disimpan.';
    }

    public function deleteCategory(int $id): void
    {
        Category::findOrFail($id)->delete();
        $this->successMessage = 'Kategori berhasil dihapus.';
    }

    public function openTagModal(?int $id = null): void
    {
        $this->resetValidation();
        $this->successMessage = null;

        if ($id) {
            $t = Tag::findOrFail($id);
            $this->tagId = $t->id;
            $this->tagName = $t->name;
        } else {
            $this->reset(['tagId', 'tagName']);
        }

        $this->showTagModal = true;
    }

    public function saveTag(): void
    {
        $validated = $this->validate([
            'tagName' => 'required|string|max:255',
        ], [
            'tagName.required' => 'Nama tag wajib diisi.',
        ]);

        $slug = Str::slug($validated['tagName']);

        $slugTaken = Tag::where('slug', $slug)
            ->when($this->tagId, fn ($q) => $q->where('id', '!=', $this->tagId))
            ->exists();

        if ($slugTaken) {
            $this->addError('tagName', 'Tag dengan nama ini sudah ada.');

            return;
        }

        Tag::updateOrCreate(['id' => $this->tagId], [
            'name' => $validated['tagName'],
            'slug' => $slug,
        ]);

        $this->showTagModal = false;
        $this->successMessage = 'Tag berhasil disimpan.';
    }

    public function deleteTag(int $id): void
    {
        Tag::findOrFail($id)->delete();
        $this->successMessage = 'Tag berhasil dihapus.';
    }

    public function with(): array
    {
        return [
            'categories' => Category::withCount('posts')->orderBy('name')->get(),
            'tags' => Tag::withCount('posts')->orderBy('name')->get(),
        ];
    }
}; ?>

<div class="space-y-4">
    @if ($successMessage)
        <x-alert variant="success">{{ $successMessage }}</x-alert>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Categories Panel -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden">
            <div class="px-4 py-3 border-b border-outline-variant flex items-center justify-between">
                <h3 class="text-sm font-bold text-on-surface">Kategori</h3>
                <x-primary-button type="button" wire:click="openCategoryModal()">
                    <span class="material-symbols-outlined text-[16px]">add</span>
                    Tambah
                </x-primary-button>
            </div>
            <div class="divide-y divide-outline-variant text-xs">
                @forelse ($categories as $cat)
                    <div class="px-4 py-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="font-bold text-on-surface truncate">{{ $cat->name }}</div>
                            <div class="text-on-surface-variant">{{ $cat->posts_count }} artikel &middot; <code class="text-primary">{{ $cat->slug }}</code></div>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <x-link-button wire:click="openCategoryModal({{ $cat->id }})">Edit</x-link-button>
                            <x-link-button variant="danger" wire:click="deleteCategory({{ $cat->id }})" wire:confirm="Hapus kategori ini? Semua artikel dalam kategori ini akan ikut terhapus!">Hapus</x-link-button>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-6 text-center text-on-surface-variant">Belum ada kategori.</div>
                @endforelse
            </div>
        </div>

        <!-- Tags Panel -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden">
            <div class="px-4 py-3 border-b border-outline-variant flex items-center justify-between">
                <h3 class="text-sm font-bold text-on-surface">Tag</h3>
                <x-primary-button type="button" wire:click="openTagModal()">
                    <span class="material-symbols-outlined text-[16px]">add</span>
                    Tambah
                </x-primary-button>
            </div>
            <div class="divide-y divide-outline-variant text-xs">
                @forelse ($tags as $tag)
                    <div class="px-4 py-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="font-bold text-on-surface truncate">{{ $tag->name }}</div>
                            <div class="text-on-surface-variant">{{ $tag->posts_count }} artikel</div>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <x-link-button wire:click="openTagModal({{ $tag->id }})">Edit</x-link-button>
                            <x-link-button variant="danger" wire:click="deleteTag({{ $tag->id }})" wire:confirm="Hapus tag ini?">Hapus</x-link-button>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-6 text-center text-on-surface-variant">Belum ada tag.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- MODAL: CATEGORY FORM -->
    @if ($showCategoryModal)
        <div x-data class="fixed inset-0 bg-on-surface/60 z-50 flex items-center justify-center p-4" wire:click.self="$set('showCategoryModal', false)" x-on:keydown.escape.window="$wire.set('showCategoryModal', false)">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 max-w-md w-full space-y-4 shadow-xl text-on-surface">
                <div class="flex items-center justify-between border-b border-outline-variant pb-3">
                    <h3 class="text-base font-bold text-on-surface">{{ $categoryId ? 'Edit Kategori' : 'Tambah Kategori' }}</h3>
                    <button wire:click="$set('showCategoryModal', false)" class="text-on-surface-variant hover:text-on-surface font-bold text-xl cursor-pointer" aria-label="Tutup">&times;</button>
                </div>
                <form wire:submit="saveCategory" class="space-y-3 text-xs">
                    <div>
                        <x-input-label value="Nama Kategori *" />
                        <x-text-input type="text" wire:model="categoryName" />
                        @error('categoryName') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="Slug *" />
                        <x-text-input type="text" wire:model="categorySlug" />
                        @error('categorySlug') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="Deskripsi" />
                        <x-textarea-input wire:model="categoryDescription" :rows="2" />
                    </div>
                    <div class="flex justify-end space-x-2 pt-3 border-t border-outline-variant">
                        <x-secondary-button type="button" wire:click="$set('showCategoryModal', false)">Batal</x-secondary-button>
                        <x-primary-button type="submit">Simpan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL: TAG FORM -->
    @if ($showTagModal)
        <div x-data class="fixed inset-0 bg-on-surface/60 z-50 flex items-center justify-center p-4" wire:click.self="$set('showTagModal', false)" x-on:keydown.escape.window="$wire.set('showTagModal', false)">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 max-w-sm w-full space-y-4 shadow-xl text-on-surface">
                <div class="flex items-center justify-between border-b border-outline-variant pb-3">
                    <h3 class="text-base font-bold text-on-surface">{{ $tagId ? 'Edit Tag' : 'Tambah Tag' }}</h3>
                    <button wire:click="$set('showTagModal', false)" class="text-on-surface-variant hover:text-on-surface font-bold text-xl cursor-pointer" aria-label="Tutup">&times;</button>
                </div>
                <form wire:submit="saveTag" class="space-y-3 text-xs">
                    <div>
                        <x-input-label value="Nama Tag *" />
                        <x-text-input type="text" wire:model="tagName" />
                        @error('tagName') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end space-x-2 pt-3 border-t border-outline-variant">
                        <x-secondary-button type="button" wire:click="$set('showTagModal', false)">Batal</x-secondary-button>
                        <x-primary-button type="submit">Simpan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
