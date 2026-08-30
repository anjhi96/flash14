<?php

use App\Models\Post;
use Livewire\Volt\Component;

new class extends Component {
    public string $activeSubTab = 'articles';

    public function setSubTab(string $tab): void
    {
        $this->activeSubTab = $tab;
    }

    public function with(): array
    {
        return [
            'postsCount' => Post::count(),
        ];
    }
}; ?>

<div class="space-y-4">
    <div class="flex border-b border-outline-variant overflow-x-auto gap-5 sm:gap-7">
        <x-tab-button wire:click="setSubTab('articles')" :active="$activeSubTab === 'articles'">
            <span class="material-symbols-outlined text-[18px]">article</span>
            Artikel ({{ $postsCount }})
        </x-tab-button>
        <x-tab-button wire:click="setSubTab('taxonomy')" :active="$activeSubTab === 'taxonomy'">
            <span class="material-symbols-outlined text-[18px]">sell</span>
            Kategori & Tag
        </x-tab-button>
        <x-tab-button wire:click="setSubTab('comments')" :active="$activeSubTab === 'comments'">
            <span class="material-symbols-outlined text-[18px]">forum</span>
            Komentar
        </x-tab-button>
        <x-tab-button wire:click="setSubTab('news-sources')" :active="$activeSubTab === 'news-sources'">
            <span class="material-symbols-outlined text-[18px]">rss_feed</span>
            Sumber Berita
        </x-tab-button>
        <x-tab-button wire:click="setSubTab('auto-blog')" :active="$activeSubTab === 'auto-blog'">
            <span class="material-symbols-outlined text-[18px]">smart_toy</span>
            Auto-Blog
        </x-tab-button>
    </div>

    @if ($activeSubTab === 'articles')
        <div class="pt-2">
            <livewire:admin.blog-posts-manager wire:key="admin-blog-posts-manager" />
        </div>
    @endif

    @if ($activeSubTab === 'taxonomy')
        <div class="pt-2">
            <livewire:admin.blog-taxonomy-manager wire:key="admin-blog-taxonomy-manager" />
        </div>
    @endif

    @if ($activeSubTab === 'comments')
        <div class="pt-2">
            <livewire:admin.blog-comments-moderator wire:key="admin-blog-comments-moderator" />
        </div>
    @endif

    @if ($activeSubTab === 'news-sources')
        <div class="pt-2">
            <livewire:admin.news-sources-manager wire:key="admin-news-sources-manager" />
        </div>
    @endif

    @if ($activeSubTab === 'auto-blog')
        <div class="pt-2">
            <livewire:admin.news-generation-panel wire:key="admin-news-generation-panel" />
        </div>
    @endif
</div>
