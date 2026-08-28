<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public string $categorySlug = '';
    public string $tagSlug = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function selectCategory(string $slug): void
    {
        $this->categorySlug = $this->categorySlug === $slug ? '' : $slug;
        $this->resetPage();
    }

    public function selectTag(string $slug): void
    {
        $this->tagSlug = $this->tagSlug === $slug ? '' : $slug;
        $this->resetPage();
    }

    /**
     * Called from Alpine (`$wire.call(...)`) with the post slugs currently
     * stored in the visitor's localStorage bookmark list.
     *
     * @param  array<int, string>  $slugs
     * @return array<int, array<string, mixed>>
     */
    public function getBookmarkedPosts(array $slugs): array
    {
        if ($slugs === []) {
            return [];
        }

        return Post::published()
            ->whereIn('slug', $slugs)
            ->with('category')
            ->get()
            ->map(fn (Post $p) => [
                'title' => $p->title,
                'slug' => $p->slug,
                'cover_image' => $p->cover_image,
                'category' => $p->category?->name,
                'url' => route('blog.show', $p->slug),
            ])
            ->values()
            ->toArray();
    }

    public function with(): array
    {
        $isFiltering = $this->search !== '' || $this->categorySlug !== '' || $this->tagSlug !== '';
        $featured = $isFiltering ? null : Post::published()->featured()->latest('published_at')->first();

        $posts = Post::published()
            ->with(['category', 'author'])
            ->when($featured, fn (Builder $q) => $q->where('id', '!=', $featured->id))
            ->when($this->search, fn (Builder $q) => $q->search($this->search))
            ->when($this->categorySlug, fn (Builder $q) => $q->byCategory($this->categorySlug))
            ->when($this->tagSlug, fn (Builder $q) => $q->whereHas('tags', fn (Builder $t) => $t->where('slug', $this->tagSlug)))
            ->latest('published_at')
            ->paginate(9);

        return [
            'featured' => $featured,
            'posts' => $posts,
            'categories' => Category::active()->orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ];
    }
}; ?>

<div class="bg-surface text-on-surface min-h-screen transition-colors duration-200">
    <x-slot name="title">Blog & Tech Insights - FlashDev</x-slot>
    <x-slot name="description">Wawasan teknis seputar arsitektur web, design system, keamanan aplikasi, dan studi kasus proyek dari tim FlashDev.</x-slot>

    <!-- Header Banner -->
    <section class="py-14 border-b border-outline-variant bg-surface-container-lowest">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl text-center space-y-3">
            <x-badge variant="primary">Blog & Tech Insights</x-badge>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-on-surface">Wawasan Teknis dari Tim FlashDev</h1>
            <p class="text-sm text-on-surface-variant max-w-xl mx-auto leading-relaxed">
                Catatan arsitektur, design system, keamanan aplikasi, dan studi kasus proyek nyata — ditulis oleh engineer dan desainer yang mengerjakannya langsung.
            </p>
        </div>
    </section>

    <!-- Featured Hero (only on the unfiltered default view) -->
    @if ($featured)
        <section class="py-10 border-b border-outline-variant">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <a href="{{ route('blog.show', $featured->slug) }}" wire:navigate class="group grid grid-cols-1 lg:grid-cols-2 gap-6 items-center bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden hover:border-outline transition-all">
                    <div class="aspect-video lg:aspect-auto lg:h-full bg-surface-container overflow-hidden">
                        @if ($featured->cover_image)
                            <img src="{{ $featured->cover_image }}" alt="{{ $featured->title }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="p-6 lg:pr-8 space-y-3">
                        <div class="flex items-center gap-2">
                            <x-badge variant="primary">Artikel Unggulan</x-badge>
                            <x-badge>{{ $featured->category?->name }}</x-badge>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-on-surface group-hover:text-primary transition-colors">{{ $featured->title }}</h2>
                        <p class="text-sm text-on-surface-variant line-clamp-2">{{ $featured->excerpt }}</p>
                        <div class="flex items-center gap-3 text-xs text-on-surface-variant pt-1">
                            <span>{{ $featured->author?->name }}</span>
                            <span>&middot;</span>
                            <span>{{ $featured->reading_time }} menit baca</span>
                            <span>&middot;</span>
                            <span>{{ number_format($featured->views_count) }}x dilihat</span>
                        </div>
                    </div>
                </a>
            </div>
        </section>
    @endif

    <!-- Search, Filters & Grid -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="w-full md:max-w-xs">
                    <x-text-input type="search" wire:model.live.debounce.400ms="search" placeholder="Cari artikel..." />
                </div>

                <div
                    x-data="{
                        open: false,
                        items: [],
                        loading: false,
                        load() {
                            this.loading = true;
                            const slugs = JSON.parse(localStorage.getItem('flashdev_bookmarks') || '[]');
                            $wire.call('getBookmarkedPosts', slugs).then((data) => { this.items = data; this.loading = false; });
                        }
                    }"
                >
                    <button type="button" @click="open = true; load()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-surface-container-lowest hover:bg-surface-container text-xs font-semibold text-on-surface border border-outline shadow-2xs transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">bookmarks</span>
                        Artikel Tersimpan
                    </button>

                    <div x-show="open" style="display: none;" class="fixed inset-0 z-50 flex justify-end">
                        <div class="absolute inset-0 bg-on-surface/60" @click="open = false"
                             x-show="open" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>
                        <div class="relative w-full max-w-sm bg-surface-container-lowest h-full overflow-y-auto p-5 space-y-4 shadow-xl"
                             x-show="open" x-transition:enter="ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0">
                            <div class="flex items-center justify-between border-b border-outline-variant pb-3">
                                <h3 class="text-base font-bold text-on-surface">Artikel Tersimpan</h3>
                                <button type="button" @click="open = false" class="text-on-surface-variant hover:text-on-surface text-xl font-bold leading-none cursor-pointer" aria-label="Tutup">&times;</button>
                            </div>
                            <p class="text-xs text-on-surface-variant" x-show="!loading && items.length === 0">
                                Belum ada artikel yang disimpan. Klik ikon bookmark pada artikel untuk menyimpannya di sini.
                            </p>
                            <template x-for="item in items" :key="item.slug">
                                <a :href="item.url" wire:navigate class="flex gap-3 group py-2">
                                    <img :src="item.cover_image" alt="" class="w-16 h-16 rounded-lg object-cover bg-surface-container shrink-0">
                                    <div class="min-w-0">
                                        <p class="text-[10px] font-semibold text-primary uppercase tracking-wider" x-text="item.category"></p>
                                        <p class="text-xs font-bold text-on-surface line-clamp-2 group-hover:text-primary transition-colors" x-text="item.title"></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Filter Pills -->
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" wire:click="selectCategory('')" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer {{ $categorySlug === '' ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container border border-outline' }}">
                    Semua Kategori
                </button>
                @foreach ($categories as $cat)
                    <button type="button" wire:click="selectCategory('{{ $cat->slug }}')" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer {{ $categorySlug === $cat->slug ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container border border-outline' }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            <!-- Tag Filter Pills -->
            @if ($tags->isNotEmpty())
                <div class="flex flex-wrap items-center gap-2">
                    @foreach ($tags as $tag)
                        <button type="button" wire:click="selectTag('{{ $tag->slug }}')" class="px-2.5 py-1 rounded-md text-[11px] font-semibold transition-all cursor-pointer {{ $tagSlug === $tag->slug ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }}">
                            #{{ $tag->name }}
                        </button>
                    @endforeach
                </div>
            @endif

            <!-- Articles Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse ($posts as $post)
                    <div class="bg-surface-container-lowest rounded-xl overflow-hidden border border-outline-variant flex flex-col justify-between hover:border-outline transition-all">
                        <a href="{{ route('blog.show', $post->slug) }}" wire:navigate>
                            <div class="aspect-video bg-surface-container overflow-hidden">
                                @if ($post->cover_image)
                                    <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                        </a>
                        <div class="p-4 space-y-2 flex-1">
                            <x-badge>{{ $post->category?->name }}</x-badge>
                            <a href="{{ route('blog.show', $post->slug) }}" wire:navigate>
                                <h3 class="text-sm font-bold text-on-surface hover:text-primary transition-colors line-clamp-2">{{ $post->title }}</h3>
                            </a>
                            <p class="text-xs text-on-surface-variant line-clamp-2">{{ $post->excerpt }}</p>
                        </div>
                        <div class="px-4 pb-4 pt-2 border-t border-outline-variant mt-2 flex items-center justify-between text-[11px] text-on-surface-variant">
                            <div class="flex items-center gap-2">
                                <span>{{ $post->reading_time }} min</span>
                                <span>&middot;</span>
                                <span>{{ number_format($post->views_count) }} views</span>
                                <span>&middot;</span>
                                <span>{{ number_format($post->likes_count) }} likes</span>
                            </div>
                            <button
                                type="button"
                                x-data="{ bookmarked: false }"
                                x-init="bookmarked = (JSON.parse(localStorage.getItem('flashdev_bookmarks') || '[]')).includes('{{ $post->slug }}')"
                                @click="
                                    let list = JSON.parse(localStorage.getItem('flashdev_bookmarks') || '[]');
                                    list = bookmarked ? list.filter(s => s !== '{{ $post->slug }}') : [...list, '{{ $post->slug }}'];
                                    localStorage.setItem('flashdev_bookmarks', JSON.stringify(list));
                                    bookmarked = !bookmarked;
                                "
                                class="text-on-surface-variant hover:text-primary transition-colors cursor-pointer"
                                title="Simpan artikel"
                            >
                                <span class="material-symbols-outlined text-[18px]" x-text="bookmarked ? 'bookmark' : 'bookmark_border'"></span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-on-surface-variant text-xs">
                        Tidak ada artikel yang cocok dengan pencarian atau filter ini.
                    </div>
                @endforelse
            </div>

            <div>{{ $posts->links() }}</div>
        </div>
    </section>
</div>
