<?php

use App\Models\Post;
use Livewire\Volt\Component;

new class extends Component {
    public Post $post;

    public function mount(string $slug): void
    {
        $this->post = Post::published()
            ->where('slug', $slug)
            ->with(['category', 'author', 'tags'])
            ->firstOrFail();

        $this->post->increment('views_count');
    }

    public function incrementLike(): void
    {
        $this->post->increment('likes_count');
    }

    public function with(): array
    {
        $related = Post::published()
            ->where('id', '!=', $this->post->id)
            ->where('category_id', $this->post->category_id)
            ->latest('published_at')
            ->take(3)
            ->get();

        if ($related->count() < 3) {
            $extra = Post::published()
                ->where('id', '!=', $this->post->id)
                ->whereNotIn('id', $related->pluck('id'))
                ->latest('published_at')
                ->take(3 - $related->count())
                ->get();

            $related = $related->concat($extra);
        }

        return [
            'related' => $related,
            'toc' => $this->post->tableOfContents(),
        ];
    }
}; ?>

<div class="bg-surface text-on-surface min-h-screen transition-colors duration-200">
    <x-slot name="title">{{ $post->meta_title ?: $post->title }} - FlashDev Blog</x-slot>
    <x-slot name="description">{{ $post->meta_description ?: $post->excerpt }}</x-slot>

    <!-- highlight.js (this page only) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>
        function flashdevHighlightCode() {
            if (window.hljs) {
                document.querySelectorAll('pre code:not(.hljs)').forEach((el) => hljs.highlightElement(el));
            }
        }
        document.addEventListener('DOMContentLoaded', flashdevHighlightCode);
        document.addEventListener('livewire:navigated', flashdevHighlightCode);
        flashdevHighlightCode();
    </script>

    <!-- Header -->
    <section class="py-12 border-b border-outline-variant bg-surface-container-lowest">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="flex items-center gap-2">
                <x-badge variant="primary">{{ $post->category?->name }}</x-badge>
                @foreach ($post->tags as $tag)
                    <x-badge>#{{ $tag->name }}</x-badge>
                @endforeach
            </div>
            <h1 class="text-2xl sm:text-4xl font-bold tracking-tight text-on-surface leading-tight">{{ $post->title }}</h1>
            <div class="flex flex-wrap items-center gap-3 text-xs text-on-surface-variant">
                <span>{{ $post->published_at?->translatedFormat('d F Y') }}</span>
                <span>&middot;</span>
                <span>{{ $post->reading_time }} menit baca</span>
                <span>&middot;</span>
                <span>{{ number_format($post->views_count) }}x dilihat</span>
            </div>

            @if ($post->author)
                <div class="flex items-center gap-3 bg-surface-container-lowest border border-outline-variant rounded-xl p-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden bg-surface-container shrink-0">
                        @if ($post->author->photo)
                            <img src="{{ $post->author->photo }}" alt="{{ $post->author->name }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-bold text-on-surface">{{ $post->author->name }}</p>
                        <p class="text-xs text-primary font-semibold">{{ $post->author->position }}</p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Cover Image -->
    @if ($post->cover_image)
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-px">
            <div class="aspect-video rounded-xl overflow-hidden bg-surface-container mt-8">
                <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
            </div>
        </div>
    @endif

    <!-- Article Body -->
    <article class="py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (count($toc) > 1)
                <nav class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 mb-8 text-xs">
                    <p class="font-bold text-on-surface uppercase tracking-wider text-[11px] mb-2">Daftar Isi</p>
                    <ul class="space-y-1.5">
                        @foreach ($toc as $item)
                            <li class="{{ $item['level'] === 3 ? 'ml-4' : '' }}">
                                <a href="#{{ $item['id'] }}" class="text-on-surface-variant hover:text-primary transition-colors">{{ $item['text'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif

            <div class="blog-prose">
                {!! $post->renderedBody() !!}
            </div>

            <!-- Interaction Bar -->
            <div class="flex flex-wrap items-center justify-between gap-4 mt-10 pt-6 border-t border-outline-variant">
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        x-data="{ liked: false }"
                        x-init="liked = (JSON.parse(localStorage.getItem('flashdev_liked_posts') || '[]')).includes({{ $post->id }})"
                        @click="
                            if (!liked) {
                                $wire.incrementLike();
                                let list = JSON.parse(localStorage.getItem('flashdev_liked_posts') || '[]');
                                list.push({{ $post->id }});
                                localStorage.setItem('flashdev_liked_posts', JSON.stringify(list));
                                liked = true;
                            }
                        "
                        :class="liked ? 'bg-primary text-on-primary border-primary' : 'bg-surface-container-lowest text-on-surface-variant border-outline hover:border-primary/40'"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border font-semibold text-sm transition-all cursor-pointer"
                    >
                        <span class="material-symbols-outlined text-[18px]" :class="{ 'animate-like-pulse': liked }" x-text="liked ? 'favorite' : 'favorite_border'"></span>
                        <span x-text="liked ? 'Disukai' : 'Suka'"></span>
                        <span>({{ number_format($post->likes_count) }})</span>
                    </button>

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
                        :class="bookmarked ? 'bg-primary text-on-primary border-primary' : 'bg-surface-container-lowest text-on-surface-variant border-outline hover:border-primary/40'"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border font-semibold text-sm transition-all cursor-pointer"
                    >
                        <span class="material-symbols-outlined text-[18px]" x-text="bookmarked ? 'bookmark' : 'bookmark_border'"></span>
                        <span x-text="bookmarked ? 'Tersimpan' : 'Simpan'"></span>
                    </button>
                </div>

                <div class="flex items-center gap-2">
                    @php $shareUrl = route('blog.show', $post->slug); @endphp
                    <a href="https://wa.me/?text={{ urlencode($post->title.' '.$shareUrl) }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-lg bg-surface-container-lowest border border-outline flex items-center justify-center text-on-surface-variant hover:text-primary hover:border-primary/40 transition-colors" title="Bagikan ke WhatsApp">
                        <span class="material-symbols-outlined text-[18px]">chat</span>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-lg bg-surface-container-lowest border border-outline flex items-center justify-center text-on-surface-variant hover:text-primary hover:border-primary/40 transition-colors text-xs font-bold" title="Bagikan ke LinkedIn">
                        in
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-lg bg-surface-container-lowest border border-outline flex items-center justify-center text-on-surface-variant hover:text-primary hover:border-primary/40 transition-colors text-xs font-bold" title="Bagikan ke X">
                        X
                    </a>
                    <button
                        type="button"
                        x-data="{ copied: false }"
                        @click="navigator.clipboard.writeText('{{ $shareUrl }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="w-9 h-9 rounded-lg bg-surface-container-lowest border border-outline flex items-center justify-center text-on-surface-variant hover:text-primary hover:border-primary/40 transition-colors cursor-pointer"
                        title="Salin Link"
                    >
                        <span class="material-symbols-outlined text-[18px]" x-text="copied ? 'check' : 'link'"></span>
                    </button>
                </div>
            </div>

            <!-- Lead Generation CTA -->
            <div class="bg-slate-900 rounded-2xl p-8 sm:p-10 border border-slate-800 text-center space-y-4 text-white my-10">
                <h2 class="text-xl sm:text-2xl font-bold">Butuh Solusi Serupa untuk Bisnis Anda?</h2>
                <p class="text-slate-300 max-w-xl mx-auto text-xs sm:text-sm leading-relaxed">
                    Tim engineer FlashDev siap membantu merancang dan membangun sistem yang sesuai kebutuhan spesifik Anda.
                </p>
                <a href="{{ route('contact') }}" wire:navigate class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary hover:bg-primary-hover text-on-primary font-semibold text-sm transition-colors shadow-xs">
                    <span>Konsultasikan Proyek Anda</span>
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>

            <!-- Related Posts -->
            @if ($related->isNotEmpty())
                <div class="pt-6 border-t border-outline-variant">
                    <h3 class="text-base font-bold text-on-surface mb-4">Artikel Terkait</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach ($related as $r)
                            <a href="{{ route('blog.show', $r->slug) }}" wire:navigate class="group block bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden hover:border-outline transition-all">
                                <div class="aspect-video bg-surface-container overflow-hidden">
                                    @if ($r->cover_image)
                                        <img src="{{ $r->cover_image }}" alt="{{ $r->title }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="p-3">
                                    <h4 class="text-xs font-bold text-on-surface group-hover:text-primary transition-colors line-clamp-2">{{ $r->title }}</h4>
                                    <p class="text-[11px] text-on-surface-variant mt-1">{{ $r->reading_time }} menit baca</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Comments -->
            <div class="pt-10 mt-10 border-t border-outline-variant">
                <livewire:pages.blog.comments :post-id="$post->id" wire:key="post-comments-{{ $post->id }}" />
            </div>
        </div>
    </article>
</div>
