<?php

use App\Models\Service;
use App\Models\Project;
use App\Models\TeamMember;
use App\Models\ContactMessage;
use App\Models\PageSection;
use Illuminate\Support\Str;
use Livewire\Volt\Component;

new class extends Component {
    public string $activeTab = 'overview';

    // Services state
    public ?int $serviceId = null;
    public string $serviceTitle = '';
    public string $serviceSlug = '';
    public string $serviceIcon = 'code-bracket';
    public string $serviceShortDescription = '';
    public string $serviceDescription = '';
    public int $serviceOrder = 0;
    public bool $serviceIsActive = true;
    public bool $showServiceModal = false;

    // Projects state
    public ?int $projectId = null;
    public string $projectTitle = '';
    public string $projectSlug = '';
    public string $projectClient = '';
    public string $projectCategory = 'Web Dev';
    public string $projectDescription = '';
    public string $projectThumbnail = '';
    public string $projectUrl = '';
    public bool $projectIsFeatured = false;
    public int $projectOrder = 0;
    public bool $showProjectModal = false;

    // Team Members state
    public ?int $teamMemberId = null;
    public string $teamName = '';
    public string $teamPosition = '';
    public string $teamPhoto = '';
    public string $teamBio = '';
    public string $teamLinkedin = '';
    public int $teamOrder = 0;
    public bool $showTeamModal = false;

    // Message detail state
    public ?ContactMessage $selectedMessage = null;
    public bool $showMessageModal = false;

    // Page Sections state
    public array $sectionToggles = [];
    public string $editingTechStack = '';
    public bool $showTechStackEditor = false;

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    // --- SERVICE ACTIONS ---
    public function openServiceModal(?int $id = null): void
    {
        $this->resetValidation();
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
        $validated = $this->validate([
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
        session()->flash('success', 'Layanan berhasil disimpan.');
    }

    public function toggleServiceActive(int $id): void
    {
        $s = Service::findOrFail($id);
        $s->is_active = !$s->is_active;
        $s->save();
        session()->flash('success', 'Status layanan berhasil diperbarui.');
    }

    public function deleteService(int $id): void
    {
        Service::findOrFail($id)->delete();
        session()->flash('success', 'Layanan berhasil dihapus.');
    }

    // --- PROJECT ACTIONS ---
    public function openProjectModal(?int $id = null): void
    {
        $this->resetValidation();
        if ($id) {
            $p = Project::findOrFail($id);
            $this->projectId = $p->id;
            $this->projectTitle = $p->title;
            $this->projectSlug = $p->slug;
            $this->projectClient = $p->client ?? '';
            $this->projectCategory = $p->category ?? 'Web Dev';
            $this->projectDescription = $p->description ?? '';
            $this->projectThumbnail = $p->thumbnail ?? '';
            $this->projectUrl = $p->project_url ?? '';
            $this->projectIsFeatured = $p->is_featured;
            $this->projectOrder = $p->order;
        } else {
            $this->reset(['projectId', 'projectTitle', 'projectSlug', 'projectClient', 'projectDescription', 'projectThumbnail', 'projectUrl']);
            $this->projectCategory = 'E-Commerce';
            $this->projectIsFeatured = false;
            $this->projectOrder = Project::count() + 1;
        }
        $this->showProjectModal = true;
    }

    public function saveProject(): void
    {
        $this->validate([
            'projectTitle' => 'required|string|max:255',
            'projectCategory' => 'required|string|max:100',
            'projectDescription' => 'required|string',
            'projectClient' => 'nullable|string|max:255',
            'projectThumbnail' => 'nullable|url|max:500',
            'projectUrl' => 'nullable|url|max:500',
            'projectOrder' => 'required|integer',
            'projectIsFeatured' => 'required|boolean',
        ]);

        $slug = Str::slug($this->projectTitle);

        Project::updateOrCreate(
            ['id' => $this->projectId],
            [
                'title' => $this->projectTitle,
                'slug' => $slug,
                'client' => $this->projectClient,
                'category' => $this->projectCategory,
                'description' => $this->projectDescription,
                'thumbnail' => $this->projectThumbnail,
                'project_url' => $this->projectUrl,
                'is_featured' => $this->projectIsFeatured,
                'order' => $this->projectOrder,
            ]
        );

        $this->showProjectModal = false;
        session()->flash('success', 'Proyek portofolio berhasil disimpan.');
    }

    public function toggleProjectFeatured(int $id): void
    {
        $p = Project::findOrFail($id);
        $p->is_featured = !$p->is_featured;
        $p->save();
        session()->flash('success', 'Status featured proyek berhasil diperbarui.');
    }

    public function deleteProject(int $id): void
    {
        Project::findOrFail($id)->delete();
        session()->flash('success', 'Proyek berhasil dihapus.');
    }

    // --- TEAM ACTIONS ---
    public function openTeamModal(?int $id = null): void
    {
        $this->resetValidation();
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
        session()->flash('success', 'Anggota tim berhasil disimpan.');
    }

    public function deleteTeamMember(int $id): void
    {
        TeamMember::findOrFail($id)->delete();
        session()->flash('success', 'Anggota tim berhasil dihapus.');
    }

    // --- MESSAGES ACTIONS ---
    public function viewMessage(int $id): void
    {
        $msg = ContactMessage::findOrFail($id);
        if (!$msg->is_read) {
            $msg->is_read = true;
            $msg->save();
        }
        $this->selectedMessage = $msg;
        $this->showMessageModal = true;
    }

    public function toggleMessageRead(int $id): void
    {
        $msg = ContactMessage::findOrFail($id);
        $msg->is_read = !$msg->is_read;
        $msg->save();
    }

    public function deleteMessage(int $id): void
    {
        ContactMessage::findOrFail($id)->delete();
        $this->showMessageModal = false;
        session()->flash('success', 'Pesan berhasil dihapus.');
    }

    // --- PAGE SECTIONS ACTIONS ---
    public function toggleSection(string $key): void
    {
        $section = PageSection::where('section_key', $key)->first();
        if ($section) {
            $section->is_enabled = !$section->is_enabled;
            $section->save();
        }
        session()->flash('success', 'Pengaturan section berhasil diperbarui.');
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
        session()->flash('success', 'Daftar Tech Stack berhasil diperbarui.');
    }

    public function with(): array
    {
        return [
            'servicesCount' => Service::count(),
            'servicesActiveCount' => Service::where('is_active', true)->count(),
            'projectsCount' => Project::count(),
            'projectsFeaturedCount' => Project::where('is_featured', true)->count(),
            'teamCount' => TeamMember::count(),
            'messagesCount' => ContactMessage::count(),
            'messagesUnreadCount' => ContactMessage::where('is_read', false)->count(),
            'services' => Service::orderBy('order')->get(),
            'projects' => Project::orderBy('order')->get(),
            'teamMembers' => TeamMember::orderBy('order')->get(),
            'messages' => ContactMessage::latest()->get(),
            'pageSections' => PageSection::forPage('home'),
        ];
    }
}; ?>

<div class="bg-[#F8FAFC] dark:bg-[#111722] text-slate-900 dark:text-slate-100 min-h-screen pb-20 transition-colors duration-200">
    <!-- Top Header -->
    <div class="bg-white dark:bg-[#111722] border-b border-slate-200 dark:border-slate-800 py-6 px-4 sm:px-6 lg:px-8 shadow-2xs">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-amber-50 dark:bg-amber-500/10 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30 text-xs font-semibold uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[14px]">shield</span>
                    Control Center
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Admin Management Panel</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Kelola master data layanan, portofolio sistem, tim agensi, dan pesan pengunjung.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-white dark:bg-[#161F2E] hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-700 shadow-2xs transition-colors">
                    <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                    Pratinjau Website
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Banner -->
    @if (session()->has('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between shadow-2xs">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px] text-emerald-600 dark:text-emerald-400">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 dark:text-emerald-400 hover:text-emerald-900 p-1 rounded-md">&times;</button>
            </div>
        </div>
    @endif

    <!-- Main Container & M3 Navigation Tabs -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <!-- Tabs Bar -->
        <div class="flex border-b border-slate-200 dark:border-slate-800 overflow-x-auto gap-2 pb-2">
            <button wire:click="setTab('overview')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-xs sm:text-sm font-semibold transition-all whitespace-nowrap cursor-pointer {{ $activeTab === 'overview' ? 'bg-amber-500 text-slate-950 shadow-xs' : 'bg-white dark:bg-[#111722] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800' }}">
                <span class="material-symbols-outlined text-[18px]">dashboard</span>
                Ringkasan
            </button>
            <button wire:click="setTab('services')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-xs sm:text-sm font-semibold transition-all whitespace-nowrap cursor-pointer {{ $activeTab === 'services' ? 'bg-amber-500 text-slate-950 shadow-xs' : 'bg-white dark:bg-[#111722] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800' }}">
                <span class="material-symbols-outlined text-[18px]">design_services</span>
                Layanan ({{ $servicesCount }})
            </button>
            <button wire:click="setTab('projects')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-xs sm:text-sm font-semibold transition-all whitespace-nowrap cursor-pointer {{ $activeTab === 'projects' ? 'bg-amber-500 text-slate-950 shadow-xs' : 'bg-white dark:bg-[#111722] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800' }}">
                <span class="material-symbols-outlined text-[18px]">folder_special</span>
                Portofolio ({{ $projectsCount }})
            </button>
            <button wire:click="setTab('team')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-xs sm:text-sm font-semibold transition-all whitespace-nowrap cursor-pointer {{ $activeTab === 'team' ? 'bg-amber-500 text-slate-950 shadow-xs' : 'bg-white dark:bg-[#111722] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800' }}">
                <span class="material-symbols-outlined text-[18px]">group</span>
                Tim Agensi ({{ $teamCount }})
            </button>
            <button wire:click="setTab('messages')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-xs sm:text-sm font-semibold transition-all whitespace-nowrap cursor-pointer {{ $activeTab === 'messages' ? 'bg-amber-500 text-slate-950 shadow-xs' : 'bg-white dark:bg-[#111722] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800' }}">
                <span class="material-symbols-outlined text-[18px]">mail</span>
                Pesan Masuk ({{ $messagesCount }})
                @if ($messagesUnreadCount > 0)
                    <span class="px-1.5 py-0.2 text-[10px] bg-red-600 text-white rounded-full font-bold">
                        {{ $messagesUnreadCount }} Baru
                    </span>
                @endif
            </button>
            <button wire:click="setTab('sections')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-xs sm:text-sm font-semibold transition-all whitespace-nowrap cursor-pointer {{ $activeTab === 'sections' ? 'bg-amber-500 text-slate-950 shadow-xs' : 'bg-white dark:bg-[#111722] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800' }}">
                <span class="material-symbols-outlined text-[18px]">tune</span>
                Pengaturan Halaman
            </button>
        </div>

        <!-- TAB 1: OVERVIEW -->
        @if ($activeTab === 'overview')
            <div class="pt-6 space-y-6">
                <!-- Summary Tonal Stat Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-[#111722] p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Layanan</span>
                            <span class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">design_services</span>
                            </span>
                        </div>
                        <div class="text-3xl font-bold text-slate-900 dark:text-white">{{ $servicesCount }}</div>
                        <div class="text-xs text-amber-700 dark:text-amber-400 font-semibold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            {{ $servicesActiveCount }} Layanan Berstatus Aktif
                        </div>
                    </div>

                    <div class="bg-white dark:bg-[#111722] p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Portofolio</span>
                            <span class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:bg-blue-400/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">folder_special</span>
                            </span>
                        </div>
                        <div class="text-3xl font-bold text-slate-900 dark:text-white">{{ $projectsCount }}</div>
                        <div class="text-xs text-slate-600 dark:text-slate-300 font-semibold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px] text-amber-500">star</span>
                            {{ $projectsFeaturedCount }} Pilihan (Featured)
                        </div>
                    </div>

                    <div class="bg-white dark:bg-[#111722] p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Anggota Tim</span>
                            <span class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">group</span>
                            </span>
                        </div>
                        <div class="text-3xl font-bold text-slate-900 dark:text-white">{{ $teamCount }}</div>
                        <div class="text-xs text-emerald-700 dark:text-emerald-400 font-semibold">Profil Ditampilkan</div>
                    </div>

                    <div class="bg-white dark:bg-[#111722] p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pesan Masuk</span>
                            <span class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">mail</span>
                            </span>
                        </div>
                        <div class="text-3xl font-bold text-slate-900 dark:text-white">{{ $messagesCount }}</div>
                        <div class="text-xs text-rose-600 dark:text-rose-400 font-semibold">
                            {{ $messagesUnreadCount }} Pesan Belum Dibaca
                        </div>
                    </div>
                </div>

                <!-- Recent Messages High-Density Table/List -->
                <div class="bg-white dark:bg-[#111722] rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-amber-600">inbox</span>
                            Pesan Masuk Terbaru
                        </h3>
                        <button wire:click="setTab('messages')" class="text-xs font-semibold text-amber-700 dark:text-amber-400 hover:underline">
                            Lihat Semua Pesan →
                        </button>
                    </div>

                    @if ($messages->isEmpty())
                        <div class="p-6 text-center text-xs text-slate-500">Belum ada pesan masuk dari pengunjung.</div>
                    @else
                        <div class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($messages->take(5) as $msg)
                                <div class="px-5 py-3.5 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-[#161F2E] cursor-pointer transition-colors" wire:click="viewMessage({{ $msg->id }})">
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $msg->name }}</span>
                                            <span class="text-xs text-slate-500">({{ $msg->email }})</span>
                                            @if (!$msg->is_read)
                                                <span class="px-2 py-0.5 bg-rose-100 dark:bg-rose-500/20 text-rose-700 dark:text-rose-300 text-[10px] font-bold rounded-md">Baru</span>
                                            @endif
                                        </div>
                                        <p class="text-xs font-medium text-amber-700 dark:text-amber-400">{{ $msg->subject ?: 'Tanpa Subjek' }}</p>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-1">{{ $msg->message }}</p>
                                    </div>
                                    <span class="text-[11px] text-slate-400 shrink-0 ml-4">{{ $msg->created_at->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- TAB 2: SERVICES CRUD -->
        @if ($activeTab === 'services')
            <div class="pt-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Daftar Layanan</h3>
                        <p class="text-xs text-slate-500">Kelola paket solusi dan layanan software agensi.</p>
                    </div>
                    <button wire:click="openServiceModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-xs transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        Tambah Layanan
                    </button>
                </div>

                <div class="bg-white dark:bg-[#111722] rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 dark:bg-[#161F2E] text-slate-600 dark:text-slate-300 uppercase tracking-wider border-b border-slate-200 dark:border-slate-800 font-bold">
                            <tr>
                                <th class="py-3 px-4 w-16">Urutan</th>
                                <th class="py-3 px-4">Judul Layanan</th>
                                <th class="py-3 px-4">Deskripsi Singkat</th>
                                <th class="py-3 px-4 w-28">Status</th>
                                <th class="py-3 px-4 w-36 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                            @foreach ($services as $s)
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#161F2E]/60 transition-colors">
                                    <td class="py-3 px-4 font-mono font-semibold text-slate-500">{{ $s->order }}</td>
                                    <td class="py-3 px-4 font-bold text-slate-900 dark:text-white">{{ $s->title }}</td>
                                    <td class="py-3 px-4 text-slate-600 dark:text-slate-300 max-w-sm truncate">{{ $s->short_description }}</td>
                                    <td class="py-3 px-4">
                                        <button wire:click="toggleServiceActive({{ $s->id }})" class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold border cursor-pointer {{ $s->is_active ? 'bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-500/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $s->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                            {{ $s->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                                        </button>
                                    </td>
                                    <td class="py-3 px-4 text-right space-x-1.5">
                                        <button wire:click="openServiceModal({{ $s->id }})" class="px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-semibold text-xs transition-colors cursor-pointer">Edit</button>
                                        <button wire:click="deleteService({{ $s->id }})" wire:confirm="Hapus layanan ini?" class="px-2.5 py-1 rounded-md bg-red-50 dark:bg-red-950/40 hover:bg-red-100 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/40 font-semibold text-xs transition-colors cursor-pointer">Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- TAB 3: PROJECTS CRUD -->
        @if ($activeTab === 'projects')
            <div class="pt-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Portofolio Proyek</h3>
                        <p class="text-xs text-slate-500">Koleksi karya & studi kasus sistem klien.</p>
                    </div>
                    <button wire:click="openProjectModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-xs transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        Tambah Proyek
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($projects as $p)
                        <div class="bg-white dark:bg-[#111722] rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs overflow-hidden flex flex-col justify-between hover:border-slate-300 dark:hover:border-slate-700 transition-all">
                            <div>
                                <div class="aspect-video bg-slate-100 dark:bg-[#161F2E] relative overflow-hidden">
                                    @if ($p->thumbnail)
                                        <img src="{{ $p->thumbnail }}" alt="{{ $p->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-xs text-slate-400">No Image</div>
                                    @endif
                                    <div class="absolute top-2.5 left-2.5">
                                        <span class="px-2.5 py-1 bg-slate-900/80 backdrop-blur-xs text-white text-[10px] font-semibold rounded-md border border-slate-700">
                                            {{ $p->category }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-4 space-y-1.5">
                                    <h4 class="font-bold text-slate-900 dark:text-white text-sm">{{ $p->title }}</h4>
                                    <p class="text-xs text-amber-700 dark:text-amber-400 font-semibold">Klien: {{ $p->client ?? 'N/A' }}</p>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2">{{ $p->description }}</p>
                                </div>
                            </div>

                            <div class="p-4 pt-0 flex items-center justify-between border-t border-slate-100 dark:border-slate-800/80 mt-3 pt-3">
                                <button wire:click="toggleProjectFeatured({{ $p->id }})" class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold border cursor-pointer {{ $p->is_featured ? 'bg-amber-50 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 border-amber-300 dark:border-amber-500/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700' }}">
                                    <span class="material-symbols-outlined text-[12px] {{ $p->is_featured ? 'text-amber-600' : 'text-slate-400' }}">star</span>
                                    {{ $p->is_featured ? 'FEATURED' : 'STANDAR' }}
                                </button>

                                <div class="space-x-1">
                                    <button wire:click="openProjectModal({{ $p->id }})" class="px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-semibold text-xs cursor-pointer">Edit</button>
                                    <button wire:click="deleteProject({{ $p->id }})" wire:confirm="Hapus proyek ini?" class="px-2.5 py-1 rounded-md bg-red-50 dark:bg-red-950/40 hover:bg-red-100 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/40 font-semibold text-xs cursor-pointer">Hapus</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- TAB 4: TEAM MEMBERS CRUD -->
        @if ($activeTab === 'team')
            <div class="pt-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Anggota Tim Agensi</h3>
                        <p class="text-xs text-slate-500">Struktur engineer dan personel kunci.</p>
                    </div>
                    <button wire:click="openTeamModal()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-xs transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">person_add</span>
                        Tambah Anggota Tim
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($teamMembers as $m)
                        <div class="bg-white dark:bg-[#111722] rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs p-4 text-center space-y-3 flex flex-col justify-between hover:border-slate-300 dark:hover:border-slate-700 transition-all">
                            <div class="space-y-2.5">
                                <div class="w-16 h-16 rounded-full overflow-hidden mx-auto bg-slate-100 dark:bg-[#161F2E] border-2 border-amber-500">
                                    @if ($m->photo)
                                        <img src="{{ $m->photo }}" alt="{{ $m->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center font-bold text-slate-500 text-base">{{ substr($m->name, 0, 1) }}</div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white text-xs">{{ $m->name }}</h4>
                                    <p class="text-[11px] text-amber-700 dark:text-amber-400 font-semibold mt-0.5">{{ $m->position }}</p>
                                </div>
                                <p class="text-[11px] text-slate-600 dark:text-slate-400 line-clamp-2">{{ $m->bio }}</p>
                            </div>

                            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex justify-center space-x-1.5">
                                <button wire:click="openTeamModal({{ $m->id }})" class="px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-semibold text-xs cursor-pointer">Edit</button>
                                <button wire:click="deleteTeamMember({{ $m->id }})" wire:confirm="Hapus anggota tim ini?" class="px-2.5 py-1 rounded-md bg-red-50 dark:bg-red-950/40 hover:bg-red-100 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/40 font-semibold text-xs cursor-pointer">Hapus</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- TAB 5: MESSAGES INBOX -->
        @if ($activeTab === 'messages')
            <div class="pt-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Kotak Pesan Masuk</h3>
                        <p class="text-xs text-slate-500">{{ $messagesUnreadCount }} Pesan Belum Dibaca</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#111722] rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs overflow-hidden">
                    @if ($messages->isEmpty())
                        <div class="p-8 text-center text-slate-500 text-xs">Belum ada pesan masuk.</div>
                    @else
                        <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                            @foreach ($messages as $msg)
                                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 {{ !$msg->is_read ? 'bg-amber-50/40 dark:bg-amber-950/20' : '' }}">
                                    <div class="space-y-1 cursor-pointer flex-1" wire:click="viewMessage({{ $msg->id }})">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-900 dark:text-white text-xs">{{ $msg->name }}</span>
                                            <span class="text-slate-500">({{ $msg->email }})</span>
                                            @if ($msg->phone)
                                                <span class="text-slate-400">• WA: {{ $msg->phone }}</span>
                                            @endif
                                            @if (!$msg->is_read)
                                                <span class="px-2 py-0.5 bg-rose-100 dark:bg-rose-500/20 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30 text-[10px] font-bold rounded-md">UNREAD</span>
                                            @endif
                                        </div>
                                        <p class="font-semibold text-amber-700 dark:text-amber-400">{{ $msg->subject ?: 'Tanpa Subjek' }}</p>
                                        <p class="text-slate-600 dark:text-slate-400 line-clamp-1">{{ $msg->message }}</p>
                                    </div>

                                    <div class="flex items-center space-x-2 shrink-0">
                                        <span class="text-[11px] text-slate-400 mr-2">{{ $msg->created_at->format('d M Y, H:i') }}</span>
                                        <button wire:click="toggleMessageRead({{ $msg->id }})" class="px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs cursor-pointer">
                                            {{ $msg->is_read ? 'Tandai Belum Dibaca' : 'Tandai Dibaca' }}
                                        </button>
                                        <button wire:click="deleteMessage({{ $msg->id }})" wire:confirm="Hapus pesan ini?" class="px-2.5 py-1 rounded-md bg-red-50 dark:bg-red-950/40 hover:bg-red-100 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/40 font-semibold text-xs cursor-pointer">Hapus</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- TAB 6: PAGE SECTIONS MANAGEMENT -->
        @if ($activeTab === 'sections')
            <div class="pt-6 space-y-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Pengaturan Modul Section Beranda</h2>
                    <p class="text-xs text-slate-500">Aktifkan atau non-aktifkan bagian halaman beranda tanpa menyentuh kode program.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($pageSections as $section)
                        <div class="bg-white dark:bg-[#111722] border {{ $section->is_enabled ? 'border-slate-200 dark:border-slate-800' : 'border-red-200 dark:border-red-900/30 bg-red-50/20' }} rounded-xl p-4 flex items-center justify-between gap-4 transition-all shadow-2xs">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $section->is_enabled ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $section->section_name }}</h4>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-0.5 ml-4">
                                    Key: <code class="bg-slate-100 dark:bg-slate-900 px-1 py-0.5 rounded text-amber-700 dark:text-amber-300 font-mono">{{ $section->section_key }}</code>
                                    · Urutan ke-{{ $section->order }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2.5 flex-shrink-0">
                                @if ($section->section_key === 'tech_stack')
                                    <button wire:click="openTechStackEditor" class="px-2.5 py-1 rounded-md bg-amber-50 dark:bg-amber-500/10 hover:bg-amber-100 text-amber-800 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30 text-xs font-semibold cursor-pointer">
                                        Edit Items
                                    </button>
                                @endif
                                <button
                                    wire:click="toggleSection('{{ $section->section_key }}')"
                                    class="relative w-11 h-6 rounded-full transition-all duration-200 focus:outline-none cursor-pointer {{ $section->is_enabled ? 'bg-amber-500' : 'bg-slate-300 dark:bg-slate-700' }}"
                                    title="{{ $section->is_enabled ? 'Klik untuk non-aktifkan' : 'Klik untuk aktifkan' }}"
                                >
                                    <span class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow-xs transition-transform duration-200 {{ $section->is_enabled ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                                <span class="text-xs font-semibold w-16 text-right {{ $section->is_enabled ? 'text-emerald-700 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $section->is_enabled ? 'AKTIF' : 'HIDDEN' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- MODAL: SERVICE FORM -->
    @if ($showServiceModal)
        <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-[#161F2E] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 max-w-lg w-full space-y-4 shadow-xl text-slate-900 dark:text-white">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $serviceId ? 'Edit Layanan' : 'Tambah Layanan Baru' }}</h3>
                    <button wire:click="$set('showServiceModal', false)" class="text-slate-400 hover:text-slate-700 dark:hover:text-white font-bold text-xl cursor-pointer">&times;</button>
                </div>
                <form wire:submit="saveService" class="space-y-3.5 text-xs">
                    <div>
                        <x-input-label value="Judul Layanan *" />
                        <x-text-input type="text" wire:model="serviceTitle" />
                        @error('serviceTitle') <span class="text-red-500 font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="Nama Icon (contoh: code-bracket, cloud, database)" />
                        <x-text-input type="text" wire:model="serviceIcon" />
                    </div>
                    <div>
                        <x-input-label value="Deskripsi Singkat *" />
                        <x-text-input type="text" wire:model="serviceShortDescription" />
                        @error('serviceShortDescription') <span class="text-red-500 font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="Deskripsi Lengkap *" />
                        <textarea wire:model="serviceDescription" rows="4" class="w-full px-3.5 py-2.5 rounded-lg bg-white dark:bg-[#111722] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 text-xs focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none"></textarea>
                        @error('serviceDescription') <span class="text-red-500 font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-3 items-center">
                        <div>
                            <x-input-label value="Urutan Tampilan *" />
                            <x-text-input type="number" wire:model="serviceOrder" />
                        </div>
                        <div class="flex items-center pt-4">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" wire:model="serviceIsActive" class="rounded border-slate-300 dark:border-slate-700 text-amber-600 focus:ring-amber-500">
                                <span class="font-semibold text-xs text-slate-700 dark:text-slate-300">Status Aktif</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <x-secondary-button type="button" wire:click="$set('showServiceModal', false)">Batal</x-secondary-button>
                        <x-primary-button type="submit">Simpan Layanan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL: PROJECT FORM -->
    @if ($showProjectModal)
        <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-[#161F2E] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 max-w-lg w-full space-y-4 shadow-xl text-slate-900 dark:text-white">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $projectId ? 'Edit Proyek' : 'Tambah Proyek Baru' }}</h3>
                    <button wire:click="$set('showProjectModal', false)" class="text-slate-400 hover:text-slate-700 dark:hover:text-white font-bold text-xl cursor-pointer">&times;</button>
                </div>
                <form wire:submit="saveProject" class="space-y-3 text-xs">
                    <div>
                        <x-input-label value="Judul Proyek *" />
                        <x-text-input type="text" wire:model="projectTitle" />
                        @error('projectTitle') <span class="text-red-500 font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-input-label value="Nama Klien" />
                            <x-text-input type="text" wire:model="projectClient" />
                        </div>
                        <div>
                            <x-input-label value="Kategori *" />
                            <select wire:model="projectCategory" class="w-full px-3 py-2.5 rounded-lg bg-white dark:bg-[#111722] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 text-xs focus:border-amber-500 focus:outline-none">
                                <option value="E-Commerce">E-Commerce</option>
                                <option value="Custom Web App">Custom Web App</option>
                                <option value="Company Profile">Company Profile</option>
                                <option value="SaaS App">SaaS App</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <x-input-label value="Deskripsi Proyek *" />
                        <textarea wire:model="projectDescription" rows="3" class="w-full px-3.5 py-2.5 rounded-lg bg-white dark:bg-[#111722] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 text-xs focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none"></textarea>
                        @error('projectDescription') <span class="text-red-500 font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="URL Gambar Thumbnail" />
                        <x-text-input type="text" wire:model="projectThumbnail" placeholder="https://..." />
                        @error('projectThumbnail') <span class="text-red-500 font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="URL Live Website" />
                        <x-text-input type="text" wire:model="projectUrl" placeholder="https://..." />
                    </div>
                    <div class="grid grid-cols-2 gap-3 items-center">
                        <div>
                            <x-input-label value="Urutan" />
                            <x-text-input type="number" wire:model="projectOrder" />
                        </div>
                        <div class="flex items-center pt-4">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" wire:model="projectIsFeatured" class="rounded border-slate-300 dark:border-slate-700 text-amber-600 focus:ring-amber-500">
                                <span class="font-semibold text-xs text-slate-700 dark:text-slate-300">Featured di Home</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <x-secondary-button type="button" wire:click="$set('showProjectModal', false)">Batal</x-secondary-button>
                        <x-primary-button type="submit">Simpan Proyek</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL: TEAM MEMBER FORM -->
    @if ($showTeamModal)
        <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-[#161F2E] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 max-w-lg w-full space-y-4 shadow-xl text-slate-900 dark:text-white">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $teamMemberId ? 'Edit Anggota Tim' : 'Tambah Anggota Tim' }}</h3>
                    <button wire:click="$set('showTeamModal', false)" class="text-slate-400 hover:text-slate-700 dark:hover:text-white font-bold text-xl cursor-pointer">&times;</button>
                </div>
                <form wire:submit="saveTeamMember" class="space-y-3 text-xs">
                    <div>
                        <x-input-label value="Nama Lengkap *" />
                        <x-text-input type="text" wire:model="teamName" />
                        @error('teamName') <span class="text-red-500 font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="Jabatan / Posisi *" />
                        <x-text-input type="text" wire:model="teamPosition" placeholder="Lead Software Engineer" />
                        @error('teamPosition') <span class="text-red-500 font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="URL Foto Profil" />
                        <x-text-input type="text" wire:model="teamPhoto" placeholder="https://..." />
                    </div>
                    <div>
                        <x-input-label value="Biografi Singkat" />
                        <textarea wire:model="teamBio" rows="3" class="w-full px-3.5 py-2.5 rounded-lg bg-white dark:bg-[#111722] border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100 text-xs focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none"></textarea>
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
                    <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <x-secondary-button type="button" wire:click="$set('showTeamModal', false)">Batal</x-secondary-button>
                        <x-primary-button type="submit">Simpan Anggota Tim</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL: VIEW MESSAGE -->
    @if ($showMessageModal && $selectedMessage)
        <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-[#161F2E] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 max-w-lg w-full space-y-4 shadow-xl text-slate-900 dark:text-white">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Detail Pesan Masuk</h3>
                    <button wire:click="$set('showMessageModal', false)" class="text-slate-400 hover:text-slate-700 dark:hover:text-white font-bold text-xl cursor-pointer">&times;</button>
                </div>
                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block font-semibold uppercase text-[10px]">Pengirim</span>
                        <span class="text-slate-900 dark:text-white font-bold text-sm">{{ $selectedMessage->name }}</span>
                        <span class="text-slate-500">({{ $selectedMessage->email }})</span>
                    </div>
                    @if ($selectedMessage->phone)
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 block font-semibold uppercase text-[10px]">Nomor WhatsApp</span>
                            <span class="text-amber-700 dark:text-amber-400 font-bold">{{ $selectedMessage->phone }}</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $selectedMessage->phone) }}" target="_blank" class="ml-2 text-emerald-600 dark:text-emerald-400 underline font-semibold">Buka WhatsApp ↗</a>
                        </div>
                    @endif
                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block font-semibold uppercase text-[10px]">Subjek</span>
                        <span class="text-slate-900 dark:text-white font-semibold">{{ $selectedMessage->subject ?: 'Tanpa Subjek' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block font-semibold uppercase text-[10px] mb-1">Isi Pesan</span>
                        <div class="bg-slate-50 dark:bg-[#111722] p-3.5 rounded-lg text-slate-800 dark:text-slate-200 leading-relaxed border border-slate-200 dark:border-slate-800 whitespace-pre-wrap">
                            {{ $selectedMessage->message }}
                        </div>
                    </div>
                    <div class="text-[10px] text-slate-400 pt-1">
                        Diterima: {{ $selectedMessage->created_at->format('d F Y, H:i:s') }}
                    </div>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-slate-100 dark:border-slate-800">
                    <x-danger-button wire:click="deleteMessage({{ $selectedMessage->id }})" wire:confirm="Hapus pesan ini?">Hapus Pesan</x-danger-button>
                    <x-secondary-button wire:click="$set('showMessageModal', false)">Tutup</x-secondary-button>
                </div>
            </div>
        </div>
    @endif

    <!-- Tech Stack Editor Modal -->
    @if ($showTechStackEditor)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 dark:bg-black/70 backdrop-blur-xs" wire:click.self="$set('showTechStackEditor', false)">
            <div class="bg-white dark:bg-[#161F2E] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 w-full max-w-md mx-4 shadow-xl space-y-4 text-slate-900 dark:text-white">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Edit Daftar Tech Stack</h3>
                    <button wire:click="$set('showTechStackEditor', false)" class="text-slate-400 hover:text-slate-700 dark:hover:text-white text-xl font-bold leading-none cursor-pointer">&times;</button>
                </div>
                <p class="text-xs text-slate-500">Tuliskan satu nama teknologi per baris. Daftar ini akan ditampilkan di banner beranda.</p>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Teknologi (1 Per Baris)</label>
                    <textarea
                        wire:model="editingTechStack"
                        rows="7"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-[#111722] border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white text-xs font-mono focus:outline-none focus:border-amber-500 resize-none leading-relaxed"
                        placeholder="Laravel&#10;Livewire&#10;Tailwind CSS&#10;Vue.js&#10;PostgreSQL"
                    ></textarea>
                </div>
                <div class="flex gap-2.5 pt-1 border-t border-slate-100 dark:border-slate-800">
                    <x-secondary-button class="flex-1" wire:click="$set('showTechStackEditor', false)">Batal</x-secondary-button>
                    <x-primary-button class="flex-1" wire:click="saveTechStack">Simpan</x-primary-button>
                </div>
            </div>
        </div>
    @endif

</div>
