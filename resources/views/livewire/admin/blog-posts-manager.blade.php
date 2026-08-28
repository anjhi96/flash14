<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\TeamMember;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    // Filters
    public string $search = '';
    public string $filterCategory = '';
    public string $filterStatus = '';

    // Editor state
    public ?int $postId = null;
    public string $postTitle = '';
    public string $postSlug = '';
    public ?int $postCategoryId = null;
    public ?int $postAuthorId = null;
    public string $postExcerpt = '';
    public string $postBody = '';
    public string $postCoverImage = '';
    public string $postStatus = 'draft';
    public string $postPublishedAt = '';
    public string $postMetaTitle = '';
    public string $postMetaDescription = '';
    public bool $postIsFeatured = false;
    public array $postTags = [];
    public bool $showPostModal = false;
    public bool $showPreview = false;
    public ?string $successMessage = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterCategory(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatedPostTitle(string $value): void
    {
        // Only auto-sync the slug for brand-new, not-yet-saved posts.
        if (! $this->postId) {
            $this->postSlug = Str::slug($value);
        }
    }

    public function openPostModal(?int $id = null): void
    {
        $this->resetValidation();
        $this->successMessage = null;
        $this->showPreview = false;

        if ($id) {
            $p = Post::with('tags')->findOrFail($id);
            $this->postId = $p->id;
            $this->postTitle = $p->title;
            $this->postSlug = $p->slug;
            $this->postCategoryId = $p->category_id;
            $this->postAuthorId = $p->author_id;
            $this->postExcerpt = $p->excerpt ?? '';
            $this->postBody = $p->body;
            $this->postCoverImage = $p->cover_image ?? '';
            $this->postStatus = $p->status;
            $this->postPublishedAt = $p->published_at?->format('Y-m-d\TH:i') ?? '';
            $this->postMetaTitle = $p->meta_title ?? '';
            $this->postMetaDescription = $p->meta_description ?? '';
            $this->postIsFeatured = $p->is_featured;
            $this->postTags = $p->tags->pluck('id')->map(fn ($tagId) => (string) $tagId)->toArray();
        } else {
            $this->reset([
                'postId', 'postTitle', 'postSlug', 'postExcerpt', 'postBody',
                'postCoverImage', 'postMetaTitle', 'postMetaDescription', 'postTags', 'postPublishedAt',
            ]);
            $this->postCategoryId = Category::orderBy('name')->value('id');
            $this->postAuthorId = TeamMember::ordered()->value('id');
            $this->postStatus = 'draft';
            $this->postIsFeatured = false;
        }

        $this->showPostModal = true;
    }

    public function savePost(): void
    {
        $validated = $this->validate([
            'postTitle' => 'required|string|max:255',
            'postSlug' => ['required', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($this->postId)],
            'postCategoryId' => 'required|exists:categories,id',
            'postAuthorId' => 'required|exists:team_members,id',
            'postExcerpt' => 'nullable|string|max:500',
            'postBody' => 'required|string',
            'postCoverImage' => 'nullable|url|max:500',
            'postStatus' => 'required|in:draft,published,scheduled',
            'postPublishedAt' => 'nullable|date',
            'postMetaTitle' => 'nullable|string|max:255',
            'postMetaDescription' => 'nullable|string|max:500',
            'postIsFeatured' => 'boolean',
            'postTags' => 'array',
            'postTags.*' => 'exists:tags,id',
        ], [
            'postTitle.required' => 'Judul artikel wajib diisi.',
            'postSlug.required' => 'Slug wajib diisi.',
            'postSlug.unique' => 'Slug ini sudah digunakan artikel lain.',
            'postCategoryId.required' => 'Kategori wajib dipilih.',
            'postAuthorId.required' => 'Penulis wajib dipilih.',
            'postBody.required' => 'Konten artikel wajib diisi.',
            'postCoverImage.url' => 'URL gambar sampul tidak valid.',
        ]);

        $publishedAt = $validated['postPublishedAt'] ? Carbon::parse($validated['postPublishedAt']) : null;
        if ($validated['postStatus'] === 'published' && ! $publishedAt) {
            $publishedAt = now();
        }

        $post = Post::updateOrCreate(
            ['id' => $this->postId],
            [
                'author_id' => $validated['postAuthorId'],
                'category_id' => $validated['postCategoryId'],
                'title' => $validated['postTitle'],
                'slug' => Str::slug($validated['postSlug']),
                'excerpt' => $validated['postExcerpt'] ?: null,
                'body' => $validated['postBody'],
                'cover_image' => $validated['postCoverImage'] ?: null,
                'status' => $validated['postStatus'],
                'published_at' => $publishedAt,
                'reading_time' => Post::calculateReadingTime($validated['postBody']),
                'meta_title' => $validated['postMetaTitle'] ?: null,
                'meta_description' => $validated['postMetaDescription'] ?: null,
                'is_featured' => $validated['postIsFeatured'] ?? false,
            ]
        );

        $post->tags()->sync($this->postTags);

        $this->showPostModal = false;
        $this->successMessage = 'Artikel berhasil disimpan.';
    }

    public function togglePublish(int $id): void
    {
        $post = Post::findOrFail($id);

        if ($post->status === 'published') {
            $post->status = 'draft';
        } else {
            $post->status = 'published';
            $post->published_at ??= now();
        }

        $post->save();
        $this->successMessage = 'Status artikel berhasil diperbarui.';
    }

    public function deletePost(int $id): void
    {
        Post::findOrFail($id)->delete();
        $this->successMessage = 'Artikel berhasil dihapus.';
    }

    /**
     * Live Markdown preview of the body currently being edited.
     */
    public function previewHtml(): string
    {
        return (new Post(['body' => $this->postBody]))->renderedBody();
    }

    public function with(): array
    {
        $posts = Post::query()
            ->with(['category', 'author'])
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->when($this->filterCategory, fn ($q) => $q->byCategory($this->filterCategory))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest('created_at')
            ->paginate(10);

        return [
            'posts' => $posts,
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
            'authors' => TeamMember::ordered()->get(),
        ];
    }
}; ?>

<div class="space-y-4">
    @if ($successMessage)
        <x-alert variant="success">{{ $successMessage }}</x-alert>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-lg font-bold text-on-surface">Daftar Artikel</h3>
            <p class="text-xs text-on-surface-variant">Kelola artikel blog & tech insights FlashDev.</p>
        </div>
        <x-primary-button type="button" wire:click="openPostModal()">
            <span class="material-symbols-outlined text-[16px]">add</span>
            Tulis Artikel
        </x-primary-button>
    </div>

    <!-- Filters -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <x-text-input type="search" wire:model.live.debounce.400ms="search" placeholder="Cari judul atau excerpt..." />
        <x-select-input wire:model.live="filterCategory">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
            @endforeach
        </x-select-input>
        <x-select-input wire:model.live="filterStatus">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="published">Published</option>
            <option value="scheduled">Scheduled</option>
        </x-select-input>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-surface-container text-on-surface-variant uppercase tracking-wider border-b border-outline-variant font-bold">
                <tr>
                    <th class="py-3 px-4">Judul</th>
                    <th class="py-3 px-4 w-40">Kategori</th>
                    <th class="py-3 px-4 w-28">Status</th>
                    <th class="py-3 px-4 w-20 text-right">Views</th>
                    <th class="py-3 px-4 w-20 text-right">Likes</th>
                    <th class="py-3 px-4 w-36 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse ($posts as $post)
                    <tr class="hover:bg-surface-container transition-colors">
                        <td class="py-3 px-4">
                            <div class="font-bold text-on-surface">{{ $post->title }}</div>
                            <div class="text-on-surface-variant">oleh {{ $post->author?->name ?? '—' }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <x-badge>{{ $post->category?->name ?? '—' }}</x-badge>
                        </td>
                        <td class="py-3 px-4">
                            @if ($post->status === 'scheduled')
                                <x-badge variant="primary">SCHEDULED</x-badge>
                            @else
                                <button type="button" wire:click="togglePublish({{ $post->id }})" class="cursor-pointer">
                                    <x-badge :variant="$post->status === 'published' ? 'success' : 'neutral'">
                                        {{ strtoupper($post->status) }}
                                    </x-badge>
                                </button>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right font-mono text-on-surface-variant">{{ number_format($post->views_count) }}</td>
                        <td class="py-3 px-4 text-right font-mono text-on-surface-variant">{{ number_format($post->likes_count) }}</td>
                        <td class="py-3 px-4 text-right space-x-1">
                            <x-link-button wire:click="openPostModal({{ $post->id }})">Edit</x-link-button>
                            <x-link-button variant="danger" wire:click="deletePost({{ $post->id }})" wire:confirm="Hapus artikel ini?">Hapus</x-link-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-on-surface-variant">Belum ada artikel yang cocok dengan filter ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $posts->links() }}</div>

    <!-- MODAL: POST EDITOR -->
    @if ($showPostModal)
        <div x-data class="fixed inset-0 bg-on-surface/60 z-50 flex items-center justify-center p-4" wire:click.self="$set('showPostModal', false)" x-on:keydown.escape.window="$wire.set('showPostModal', false)">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 max-w-3xl w-full max-h-[90vh] overflow-y-auto space-y-4 shadow-xl text-on-surface">
                <div class="flex items-center justify-between border-b border-outline-variant pb-3">
                    <h3 class="text-base font-bold text-on-surface">{{ $postId ? 'Edit Artikel' : 'Tulis Artikel Baru' }}</h3>
                    <button wire:click="$set('showPostModal', false)" class="text-on-surface-variant hover:text-on-surface font-bold text-xl cursor-pointer" aria-label="Tutup">&times;</button>
                </div>

                <form wire:submit="savePost" class="space-y-3.5 text-xs">
                    <div>
                        <x-input-label value="Judul Artikel *" />
                        <x-text-input type="text" wire:model="postTitle" />
                        @error('postTitle') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label value="Slug *" />
                        <x-text-input type="text" wire:model="postSlug" />
                        @error('postSlug') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-input-label value="Kategori *" />
                            <x-select-input wire:model="postCategoryId">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </x-select-input>
                            @error('postCategoryId') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <x-input-label value="Penulis *" />
                            <x-select-input wire:model="postAuthorId">
                                @foreach ($authors as $author)
                                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                                @endforeach
                            </x-select-input>
                            @error('postAuthorId') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <x-input-label value="Tag" />
                        <div class="flex flex-wrap gap-3 p-3 rounded-lg border border-outline bg-surface-container-lowest">
                            @foreach ($tags as $tag)
                                <label class="flex items-center gap-1.5 cursor-pointer">
                                    <input type="checkbox" wire:model="postTags" value="{{ $tag->id }}" class="rounded border-outline text-primary focus:ring-primary">
                                    <span class="text-on-surface-variant">{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <x-input-label value="Excerpt (Ringkasan Singkat)" />
                        <x-textarea-input wire:model="postExcerpt" :rows="2" />
                        @error('postExcerpt') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <x-input-label value="Konten Artikel (Markdown) *" class="mb-0" />
                            <button type="button" wire:click="$toggle('showPreview')" class="text-[11px] font-semibold text-primary hover:underline cursor-pointer">
                                {{ $showPreview ? 'Kembali ke Editor' : 'Pratinjau' }}
                            </button>
                        </div>

                        @if ($showPreview)
                            <div class="blog-prose max-h-80 overflow-y-auto rounded-lg border border-outline bg-surface-container-lowest p-4">
                                {!! $this->previewHtml() !!}
                            </div>
                        @else
                            <x-textarea-input wire:model="postBody" :rows="12" class="font-mono" placeholder="## Judul Bagian&#10;&#10;Tulis konten dalam format Markdown..." />
                        @endif
                        @error('postBody') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label value="URL Gambar Sampul (Cover Image)" />
                        <x-text-input type="text" wire:model="postCoverImage" placeholder="https://..." />
                        @error('postCoverImage') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-input-label value="Meta Title (SEO)" />
                            <x-text-input type="text" wire:model="postMetaTitle" />
                        </div>
                        <div>
                            <x-input-label value="Meta Description (SEO)" />
                            <x-text-input type="text" wire:model="postMetaDescription" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 items-end">
                        <div>
                            <x-input-label value="Status *" />
                            <x-select-input wire:model="postStatus">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="scheduled">Scheduled</option>
                            </x-select-input>
                        </div>
                        <div>
                            <x-input-label value="Tanggal Publikasi" />
                            <x-text-input type="datetime-local" wire:model="postPublishedAt" />
                        </div>
                    </div>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <x-switch-input :checked="$postIsFeatured" wire:click="$toggle('postIsFeatured')" />
                        <span class="font-semibold text-xs text-on-surface-variant">Jadikan Artikel Unggulan (Featured)</span>
                    </label>

                    <div class="flex justify-end space-x-2 pt-3 border-t border-outline-variant">
                        <x-secondary-button type="button" wire:click="$set('showPostModal', false)">Batal</x-secondary-button>
                        <x-primary-button type="submit">Simpan Artikel</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
