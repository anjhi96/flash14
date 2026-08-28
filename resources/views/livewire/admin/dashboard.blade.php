<?php

use App\Models\Service;
use App\Models\Project;
use App\Models\TeamMember;
use App\Models\ContactMessage;
use App\Models\Post;
use Livewire\Volt\Component;

new class extends Component {
    public string $activeTab = 'overview';

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function with(): array
    {
        return [
            'servicesCount' => Service::count(),
            'servicesActiveCount' => Service::active()->count(),
            'projectsCount' => Project::count(),
            'projectsFeaturedCount' => Project::featured()->count(),
            'teamCount' => TeamMember::count(),
            'messagesCount' => ContactMessage::count(),
            'messagesUnreadCount' => ContactMessage::unread()->count(),
            'recentMessages' => ContactMessage::recent()->take(5)->get(),
            'postsCount' => Post::count(),
        ];
    }
}; ?>

<div class="bg-surface text-on-surface min-h-screen pb-20 transition-colors duration-200">
    <!-- Top Header -->
    <div class="bg-surface-container-lowest border-b border-outline-variant py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <x-page-header eyebrow="Control Center" icon="shield" title="Admin Management Panel" description="Kelola master data layanan, portofolio sistem, tim agensi, dan pesan pengunjung.">
                <x-slot name="actions">
                    <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-surface-container-lowest hover:bg-surface-container text-xs font-semibold text-on-surface border border-outline shadow-2xs transition-colors">
                        <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                        Pratinjau Website
                    </a>
                </x-slot>
            </x-page-header>
        </div>
    </div>

    <!-- Main Container & Tabs -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <!-- Tabs Bar -->
        <div class="flex border-b border-outline-variant overflow-x-auto gap-5 sm:gap-7">
            <x-tab-button wire:click="setTab('overview')" :active="$activeTab === 'overview'">
                <span class="material-symbols-outlined text-[18px]">dashboard</span>
                Ringkasan
            </x-tab-button>
            <x-tab-button wire:click="setTab('services')" :active="$activeTab === 'services'">
                <span class="material-symbols-outlined text-[18px]">design_services</span>
                Layanan ({{ $servicesCount }})
            </x-tab-button>
            <x-tab-button wire:click="setTab('projects')" :active="$activeTab === 'projects'">
                <span class="material-symbols-outlined text-[18px]">folder_special</span>
                Portofolio ({{ $projectsCount }})
            </x-tab-button>
            <x-tab-button wire:click="setTab('team')" :active="$activeTab === 'team'">
                <span class="material-symbols-outlined text-[18px]">group</span>
                Tim Agensi ({{ $teamCount }})
            </x-tab-button>
            <x-tab-button wire:click="setTab('messages')" :active="$activeTab === 'messages'">
                <span class="material-symbols-outlined text-[18px]">mail</span>
                Pesan Masuk ({{ $messagesCount }})
                @if ($messagesUnreadCount > 0)
                    <x-badge variant="error">{{ $messagesUnreadCount }} Baru</x-badge>
                @endif
            </x-tab-button>
            <x-tab-button wire:click="setTab('sections')" :active="$activeTab === 'sections'">
                <span class="material-symbols-outlined text-[18px]">tune</span>
                Pengaturan Halaman
            </x-tab-button>
            <x-tab-button wire:click="setTab('blog')" :active="$activeTab === 'blog'">
                <span class="material-symbols-outlined text-[18px]">article</span>
                Blog & Artikel ({{ $postsCount }})
            </x-tab-button>
        </div>

        <!-- TAB: OVERVIEW -->
        @if ($activeTab === 'overview')
            <div class="pt-6 space-y-6">
                <!-- Summary Metrics Strip -->
                <div class="grid grid-cols-2 lg:grid-cols-4 rounded-xl border border-outline-variant bg-surface-container-lowest divide-y divide-outline-variant lg:divide-y-0 lg:divide-x">
                    <div class="p-5 space-y-1.5">
                        <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Total Layanan</span>
                        <div class="text-2xl font-semibold text-on-surface">{{ $servicesCount }}</div>
                        <p class="text-xs text-on-surface-variant">{{ $servicesActiveCount }} berstatus aktif</p>
                    </div>
                    <div class="p-5 space-y-1.5">
                        <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Portofolio</span>
                        <div class="text-2xl font-semibold text-on-surface">{{ $projectsCount }}</div>
                        <p class="text-xs text-on-surface-variant">{{ $projectsFeaturedCount }} pilihan (featured)</p>
                    </div>
                    <div class="p-5 space-y-1.5">
                        <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Anggota Tim</span>
                        <div class="text-2xl font-semibold text-on-surface">{{ $teamCount }}</div>
                        <p class="text-xs text-on-surface-variant">Profil ditampilkan</p>
                    </div>
                    <div class="p-5 space-y-1.5">
                        <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Pesan Masuk</span>
                        <div class="text-2xl font-semibold text-on-surface">{{ $messagesCount }}</div>
                        <p class="text-xs {{ $messagesUnreadCount > 0 ? 'text-state-error' : 'text-on-surface-variant' }}">{{ $messagesUnreadCount }} belum dibaca</p>
                    </div>
                </div>

                <!-- Recent Messages (read-only glance; full inbox lives in the Messages tab) -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden">
                    <div class="px-5 py-4 border-b border-outline-variant flex items-center justify-between">
                        <h3 class="text-sm font-bold text-on-surface uppercase tracking-wider">Pesan Masuk Terbaru</h3>
                        <button wire:click="setTab('messages')" class="text-xs font-semibold text-primary hover:underline cursor-pointer">
                            Lihat Semua Pesan &rarr;
                        </button>
                    </div>

                    @if ($recentMessages->isEmpty())
                        <div class="p-6 text-center text-xs text-on-surface-variant">Belum ada pesan masuk dari pengunjung.</div>
                    @else
                        <div class="divide-y divide-outline-variant">
                            @foreach ($recentMessages as $msg)
                                <div class="px-5 py-3.5 flex items-center justify-between">
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-on-surface">{{ $msg->name }}</span>
                                            <span class="text-xs text-on-surface-variant">({{ $msg->email }})</span>
                                            @if (!$msg->is_read)
                                                <x-badge variant="error">Baru</x-badge>
                                            @endif
                                        </div>
                                        <p class="text-xs font-medium text-primary">{{ $msg->subject ?: 'Tanpa Subjek' }}</p>
                                        <p class="text-xs text-on-surface-variant line-clamp-1">{{ $msg->message }}</p>
                                    </div>
                                    <span class="text-[11px] text-on-surface-variant shrink-0 ml-4">{{ $msg->created_at->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- TAB: SERVICES -->
        @if ($activeTab === 'services')
            <div class="pt-6">
                <livewire:admin.services-manager wire:key="admin-services-manager" />
            </div>
        @endif

        <!-- TAB: PROJECTS -->
        @if ($activeTab === 'projects')
            <div class="pt-6">
                <livewire:admin.projects-manager wire:key="admin-projects-manager" />
            </div>
        @endif

        <!-- TAB: TEAM -->
        @if ($activeTab === 'team')
            <div class="pt-6">
                <livewire:admin.team-manager wire:key="admin-team-manager" />
            </div>
        @endif

        <!-- TAB: MESSAGES -->
        @if ($activeTab === 'messages')
            <div class="pt-6">
                <livewire:admin.messages-inbox wire:key="admin-messages-inbox" />
            </div>
        @endif

        <!-- TAB: PAGE SECTIONS -->
        @if ($activeTab === 'sections')
            <div class="pt-6">
                <livewire:admin.sections-manager wire:key="admin-sections-manager" />
            </div>
        @endif

        <!-- TAB: BLOG & ARTIKEL -->
        @if ($activeTab === 'blog')
            <div class="pt-6">
                <livewire:admin.blog-manager wire:key="admin-blog-manager" />
            </div>
        @endif
    </div>
</div>
