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

<div class="bg-[#F8FAFC] dark:bg-[#0B0F17] text-slate-900 dark:text-white min-h-screen pb-20 transition-colors duration-300">
    <!-- Top Header -->
    <div class="bg-slate-900 dark:bg-slate-950 border-b border-slate-800 py-8 px-4 sm:px-6 lg:px-8 text-white shadow-xs">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="px-3 py-1 bg-amber-500/20 text-amber-400 border border-amber-500/40 text-xs font-extrabold rounded-full uppercase tracking-wider">
                    Control Panel
                </span>
                <h1 class="text-3xl font-extrabold mt-2 text-white">Admin Management Panel</h1>
                <p class="text-xs text-slate-400 mt-1">Kelola konten Layanan, Portofolio, Tim Agensi, dan Pesan Masuk.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('home') }}" target="_blank" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold text-amber-400 border border-slate-700 flex items-center transition-colors">
                    Buka Website Utama ↗
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Success -->
    @if (session()->has('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/40 text-emerald-800 dark:text-emerald-400 px-4 py-3 rounded-xl text-sm font-semibold flex items-center justify-between shadow-xs">
                <span>✓ {{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-800 dark:text-emerald-400 font-bold">&times;</button>
            </div>
        </div>
    @endif

    <!-- Main Container & Navigation Tabs -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        <!-- Tabs Bar -->
        <div class="flex border-b border-slate-200/90 dark:border-slate-800 overflow-x-auto space-x-2 pb-2">
            <button wire:click="setTab('overview')" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap {{ $activeTab === 'overview' ? 'bg-slate-900 dark:bg-slate-800 text-amber-400 shadow-md' : 'bg-white dark:bg-[#131A26] text-slate-700 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-slate-800' }}">
                📊 Overview
            </button>
            <button wire:click="setTab('services')" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap {{ $activeTab === 'services' ? 'bg-slate-900 dark:bg-slate-800 text-amber-400 shadow-md' : 'bg-white dark:bg-[#131A26] text-slate-700 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-slate-800' }}">
                🛠️ Layanan ({{ $servicesCount }})
            </button>
            <button wire:click="setTab('projects')" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap {{ $activeTab === 'projects' ? 'bg-slate-900 dark:bg-slate-800 text-amber-400 shadow-md' : 'bg-white dark:bg-[#131A26] text-slate-700 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-slate-800' }}">
                🚀 Portofolio ({{ $projectsCount }})
            </button>
            <button wire:click="setTab('team')" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap {{ $activeTab === 'team' ? 'bg-slate-900 dark:bg-slate-800 text-amber-400 shadow-md' : 'bg-white dark:bg-[#131A26] text-slate-700 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-slate-800' }}">
                👥 Tim Agensi ({{ $teamCount }})
            </button>
            <button wire:click="setTab('messages')" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap relative {{ $activeTab === 'messages' ? 'bg-slate-900 dark:bg-slate-800 text-amber-400 shadow-md' : 'bg-white dark:bg-[#131A26] text-slate-700 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-slate-800' }}">
                ✉️ Pesan Masuk ({{ $messagesCount }})
                @if ($messagesUnreadCount > 0)
                    <span class="ml-1.5 px-2 py-0.5 text-xs bg-rose-500 text-white rounded-full font-extrabold animate-pulse">
                        {{ $messagesUnreadCount }} Baru
                    </span>
                @endif
            </button>
            <button wire:click="setTab('sections')" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap {{ $activeTab === 'sections' ? 'bg-slate-900 dark:bg-slate-800 text-amber-400 shadow-md' : 'bg-white dark:bg-[#131A26] text-slate-700 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-slate-800' }}">
                ⚙️ Pengaturan Halaman
            </button>
        </div>

        <!-- TAB 1: OVERVIEW -->
        @if ($activeTab === 'overview')
            <div class="pt-8 space-y-8">
                <!-- Summary Widgets -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white dark:bg-[#131A26] p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                        <div class="text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Total Layanan</div>
                        <div class="text-3xl font-extrabold text-slate-900 dark:text-white mt-2">{{ $servicesCount }}</div>
                        <div class="text-xs text-amber-600 dark:text-amber-400 mt-1 font-bold">{{ $servicesActiveCount }} Layanan Aktif</div>
                    </div>
                    <div class="bg-white dark:bg-[#131A26] p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                        <div class="text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Total Portofolio</div>
                        <div class="text-3xl font-extrabold text-slate-900 dark:text-white mt-2">{{ $projectsCount }}</div>
                        <div class="text-xs text-slate-700 dark:text-purple-400 mt-1 font-bold">{{ $projectsFeaturedCount }} Pilihan (Featured)</div>
                    </div>
                    <div class="bg-white dark:bg-[#131A26] p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                        <div class="text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Tim Agensi</div>
                        <div class="text-3xl font-extrabold text-slate-900 dark:text-white mt-2">{{ $teamCount }}</div>
                        <div class="text-xs text-emerald-600 dark:text-emerald-400 mt-1 font-bold">Anggota Aktif</div>
                    </div>
                    <div class="bg-white dark:bg-[#131A26] p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                        <div class="text-xs font-extrabold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Pesan Masuk</div>
                        <div class="text-3xl font-extrabold text-slate-900 dark:text-white mt-2">{{ $messagesCount }}</div>
                        <div class="text-xs text-rose-600 dark:text-rose-400 mt-1 font-bold">{{ $messagesUnreadCount }} Pesan Belum Dibaca</div>
                    </div>
                </div>

                <!-- Recent Messages Preview -->
                <div class="bg-white dark:bg-[#131A26] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">Pesan Masuk Terbaru</h3>
                        <button wire:click="setTab('messages')" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300">Lihat Semua Pesan →</button>
                    </div>

                    @if ($messages->isEmpty())
                        <p class="text-xs text-slate-500 dark:text-gray-400 py-4">Belum ada pesan dari pengunjung.</p>
                    @else
                        <div class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($messages->take(5) as $msg)
                                <div class="py-3 flex items-center justify-between cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/60 px-3 rounded-xl transition-colors" wire:click="viewMessage({{ $msg->id }})">
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $msg->name }}</span>
                                            <span class="text-xs text-slate-500 dark:text-gray-400">({{ $msg->email }})</span>
                                            @if (!$msg->is_read)
                                                <span class="px-2 py-0.5 bg-rose-100 dark:bg-rose-500/20 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30 text-[10px] font-bold rounded-full">Baru</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-amber-700 dark:text-amber-400 font-semibold mt-0.5">{{ $msg->subject ?: 'Tanpa Subjek' }}</p>
                                        <p class="text-xs text-slate-600 dark:text-gray-400 line-clamp-1 mt-0.5">{{ $msg->message }}</p>
                                    </div>
                                    <span class="text-[10px] text-slate-400 dark:text-gray-500 shrink-0 ml-4">{{ $msg->created_at->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- TAB 2: SERVICES CRUD -->
        @if ($activeTab === 'services')
            <div class="pt-8 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Daftar Layanan Agensi</h3>
                    <button wire:click="openServiceModal()" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 dark:bg-amber-500 dark:hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-md transition-all">
                        + Tambah Layanan Baru
                    </button>
                </div>

                <div class="bg-white dark:bg-[#131A26] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-100/80 dark:bg-[#0B0F17] text-slate-700 dark:text-gray-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-800 font-bold">
                            <tr>
                                <th class="p-4">Urutan</th>
                                <th class="p-4">Judul Layanan</th>
                                <th class="p-4">Deskripsi Singkat</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                            @foreach ($services as $s)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="p-4 font-bold text-slate-500 dark:text-gray-400">{{ $s->order }}</td>
                                    <td class="p-4 font-bold text-slate-900 dark:text-white">{{ $s->title }}</td>
                                    <td class="p-4 text-slate-600 dark:text-gray-300 max-w-xs truncate">{{ $s->short_description }}</td>
                                    <td class="p-4">
                                        <button wire:click="toggleServiceActive({{ $s->id }})" class="px-2.5 py-1 rounded-full text-[10px] font-bold border transition-colors {{ $s->is_active ? 'bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-500/40' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-gray-400 border-slate-300 dark:border-slate-700' }}">
                                            {{ $s->is_active ? 'Aktif' : 'Non-Aktif' }}
                                        </button>
                                    </td>
                                    <td class="p-4 text-right space-x-2">
                                        <button wire:click="openServiceModal({{ $s->id }})" class="px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold">Edit</button>
                                        <button wire:click="deleteService({{ $s->id }})" wire:confirm="Hapus layanan ini?" class="px-3 py-1.5 rounded-lg bg-rose-50 dark:bg-rose-900/30 hover:bg-rose-100 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 font-bold">Hapus</button>
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
            <div class="pt-8 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Daftar Portofolio Proyek</h3>
                    <button wire:click="openProjectModal()" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 dark:bg-amber-500 dark:hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-md transition-all">
                        + Tambah Proyek Baru
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($projects as $p)
                        <div class="bg-white dark:bg-[#131A26] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col justify-between hover:border-amber-400 dark:hover:border-amber-500/40 transition-all">
                            <div>
                                <div class="aspect-video bg-slate-100 dark:bg-slate-900 relative">
                                    @if ($p->thumbnail)
                                        <img src="{{ $p->thumbnail }}" alt="{{ $p->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-xs text-slate-400 dark:text-gray-500">No Image</div>
                                    @endif
                                    <div class="absolute top-3 left-3 flex gap-2">
                                        <span class="px-2.5 py-0.5 bg-white/95 dark:bg-[#0B0F17]/90 text-slate-900 dark:text-amber-300 text-[10px] font-bold rounded-full border border-slate-200 dark:border-amber-500/30 shadow-2xs">
                                            {{ $p->category }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-5 space-y-2">
                                    <h4 class="font-bold text-slate-900 dark:text-white text-base">{{ $p->title }}</h4>
                                    <p class="text-xs text-amber-600 dark:text-amber-400/80 font-semibold">Klien: {{ $p->client ?? 'N/A' }}</p>
                                    <p class="text-xs text-slate-600 dark:text-gray-400 line-clamp-2">{{ $p->description }}</p>
                                </div>
                            </div>

                            <div class="p-5 pt-0 flex items-center justify-between border-t border-slate-100 dark:border-slate-800 mt-4">
                                <button wire:click="toggleProjectFeatured({{ $p->id }})" class="text-[10px] font-bold px-2.5 py-1 rounded-full border transition-colors {{ $p->is_featured ? 'bg-amber-50 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 border-amber-300 dark:border-amber-500/40' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-gray-400 border-slate-200 dark:border-slate-700' }}">
                                    {{ $p->is_featured ? '★ Featured' : 'Standar' }}
                                </button>

                                <div class="space-x-1">
                                    <button wire:click="openProjectModal({{ $p->id }})" class="px-2.5 py-1 rounded-lg bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold text-xs">Edit</button>
                                    <button wire:click="deleteProject({{ $p->id }})" wire:confirm="Hapus proyek ini?" class="px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-900/30 hover:bg-rose-100 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 font-bold text-xs">Hapus</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- TAB 4: TEAM MEMBERS CRUD -->
        @if ($activeTab === 'team')
            <div class="pt-8 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Anggota Tim Agensi</h3>
                    <button wire:click="openTeamModal()" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 dark:bg-amber-500 dark:hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-md transition-all">
                        + Tambah Anggota Tim
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($teamMembers as $m)
                        <div class="bg-white dark:bg-[#131A26] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 text-center space-y-3 flex flex-col justify-between hover:border-amber-400 dark:hover:border-amber-500/40 transition-all">
                            <div class="space-y-3">
                                <div class="w-20 h-20 rounded-full overflow-hidden mx-auto bg-slate-100 dark:bg-slate-900 border-2 border-amber-400 dark:border-amber-500/40">
                                    @if ($m->photo)
                                        <img src="{{ $m->photo }}" alt="{{ $m->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center font-bold text-slate-500 dark:text-gray-400 text-lg">{{ substr($m->name, 0, 1) }}</div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white text-sm">{{ $m->name }}</h4>
                                    <p class="text-xs text-amber-600 dark:text-amber-400 font-semibold mt-0.5">{{ $m->position }}</p>
                                </div>
                                <p class="text-[11px] text-slate-600 dark:text-gray-400 line-clamp-2">{{ $m->bio }}</p>
                            </div>

                            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex justify-center space-x-2">
                                <button wire:click="openTeamModal({{ $m->id }})" class="px-3 py-1 rounded-lg bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold text-xs">Edit</button>
                                <button wire:click="deleteTeamMember({{ $m->id }})" wire:confirm="Hapus anggota tim ini?" class="px-3 py-1 rounded-lg bg-rose-50 dark:bg-rose-900/30 hover:bg-rose-100 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 font-bold text-xs">Hapus</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- TAB 5: MESSAGES INBOX -->
        @if ($activeTab === 'messages')
            <div class="pt-8 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Kotak Pesan Masuk</h3>
                    <span class="text-xs text-slate-500 dark:text-gray-400 font-semibold">{{ $messagesUnreadCount }} Pesan Belum Dibaca</span>
                </div>

                <div class="bg-white dark:bg-[#131A26] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                    @if ($messages->isEmpty())
                        <div class="p-8 text-center text-slate-500 dark:text-gray-400 text-sm">Belum ada pesan masuk.</div>
                    @else
                        <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                            @foreach ($messages as $msg)
                                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ !$msg->is_read ? 'bg-amber-50/40 dark:bg-amber-950/20' : '' }}">
                                    <div class="space-y-1 cursor-pointer flex-1" wire:click="viewMessage({{ $msg->id }})">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-bold text-slate-900 dark:text-white text-sm">{{ $msg->name }}</span>
                                            <span class="text-slate-500 dark:text-gray-400">({{ $msg->email }})</span>
                                            @if ($msg->phone)
                                                <span class="text-slate-400 dark:text-gray-500">• WA: {{ $msg->phone }}</span>
                                            @endif
                                            @if (!$msg->is_read)
                                                <span class="px-2 py-0.5 bg-rose-100 dark:bg-rose-500/20 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30 text-[10px] font-bold rounded-full">Belum Dibaca</span>
                                            @endif
                                        </div>
                                        <p class="font-semibold text-amber-700 dark:text-amber-400">{{ $msg->subject ?: 'Tanpa Subjek' }}</p>
                                        <p class="text-slate-600 dark:text-gray-400 line-clamp-1">{{ $msg->message }}</p>
                                    </div>

                                    <div class="flex items-center space-x-2 shrink-0">
                                        <span class="text-[10px] text-slate-400 dark:text-gray-500 mr-2">{{ $msg->created_at->format('d M Y, H:i') }}</span>
                                        <button wire:click="toggleMessageRead({{ $msg->id }})" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-gray-300 font-bold text-[11px]">
                                            {{ $msg->is_read ? 'Tandai Belum Dibaca' : 'Tandai Dibaca' }}
                                        </button>
                                        <button wire:click="deleteMessage({{ $msg->id }})" wire:confirm="Hapus pesan ini?" class="px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-900/30 hover:bg-rose-100 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 font-bold text-[11px]">Hapus</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- MODAL: SERVICE FORM -->
    @if ($showServiceModal)
        <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-[#131A26] border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 max-w-lg w-full space-y-6 shadow-2xl text-slate-900 dark:text-white">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $serviceId ? 'Edit Layanan' : 'Tambah Layanan Baru' }}</h3>
                    <button wire:click="$set('showServiceModal', false)" class="text-slate-400 hover:text-slate-900 dark:hover:text-white font-bold text-xl">&times;</button>
                </div>
                <form wire:submit="saveService" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">Judul Layanan *</label>
                        <input type="text" wire:model="serviceTitle" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs focus:border-amber-500">
                        @error('serviceTitle') <span class="text-rose-500 dark:text-rose-400">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">Nama Icon (misal: code-bracket, shopping-cart)</label>
                        <input type="text" wire:model="serviceIcon" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">Deskripsi Singkat *</label>
                        <input type="text" wire:model="serviceShortDescription" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs focus:border-amber-500">
                        @error('serviceShortDescription') <span class="text-rose-500 dark:text-rose-400">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">Deskripsi Lengkap *</label>
                        <textarea wire:model="serviceDescription" rows="4" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs focus:border-amber-500"></textarea>
                        @error('serviceDescription') <span class="text-rose-500 dark:text-rose-400">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">Urutan *</label>
                            <input type="number" wire:model="serviceOrder" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs">
                        </div>
                        <div class="flex items-center pt-5">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" wire:model="serviceIsActive" class="rounded border-slate-300 dark:border-slate-700 text-amber-500 focus:ring-amber-500">
                                <span class="font-bold text-slate-700 dark:text-gray-300">Status Aktif</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="$set('showServiceModal', false)" class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-gray-300 font-bold">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 dark:bg-amber-500 dark:hover:bg-amber-600 text-slate-950 font-bold">Simpan Layanan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL: PROJECT FORM -->
    @if ($showProjectModal)
        <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-[#131A26] border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 max-w-lg w-full space-y-6 shadow-2xl text-slate-900 dark:text-white">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $projectId ? 'Edit Proyek Portofolio' : 'Tambah Proyek Baru' }}</h3>
                    <button wire:click="$set('showProjectModal', false)" class="text-slate-400 hover:text-slate-900 dark:hover:text-white font-bold text-xl">&times;</button>
                </div>
                <form wire:submit="saveProject" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">Judul Proyek *</label>
                        <input type="text" wire:model="projectTitle" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs">
                        @error('projectTitle') <span class="text-rose-500 dark:text-rose-400">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">Nama Klien</label>
                            <input type="text" wire:model="projectClient" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">Kategori *</label>
                            <select wire:model="projectCategory" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs">
                                <option value="E-Commerce">E-Commerce</option>
                                <option value="Custom Web App">Custom Web App</option>
                                <option value="Company Profile">Company Profile</option>
                                <option value="SaaS App">SaaS App</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">Deskripsi Proyek *</label>
                        <textarea wire:model="projectDescription" rows="3" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs"></textarea>
                        @error('projectDescription') <span class="text-rose-500 dark:text-rose-400">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">URL Gambar Thumbnail</label>
                        <input type="text" wire:model="projectThumbnail" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs" placeholder="https://images.unsplash.com/...">
                        @error('projectThumbnail') <span class="text-rose-500 dark:text-rose-400">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">URL Live Website</label>
                        <input type="text" wire:model="projectUrl" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs" placeholder="https://example.com">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">Urutan</label>
                            <input type="number" wire:model="projectOrder" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs">
                        </div>
                        <div class="flex items-center pt-5">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" wire:model="projectIsFeatured" class="rounded border-slate-300 dark:border-slate-700 text-amber-500 focus:ring-amber-500">
                                <span class="font-bold text-slate-700 dark:text-gray-300">Featured (Tampil di Home)</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="$set('showProjectModal', false)" class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-gray-300 font-bold">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 dark:bg-amber-500 dark:hover:bg-amber-600 text-slate-950 font-bold">Simpan Proyek</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL: TEAM MEMBER FORM -->
    @if ($showTeamModal)
        <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-[#131A26] border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 max-w-lg w-full space-y-6 shadow-2xl text-slate-900 dark:text-white">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $teamMemberId ? 'Edit Anggota Tim' : 'Tambah Anggota Tim Baru' }}</h3>
                    <button wire:click="$set('showTeamModal', false)" class="text-slate-400 hover:text-slate-900 dark:hover:text-white font-bold text-xl">&times;</button>
                </div>
                <form wire:submit="saveTeamMember" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">Nama Lengkap *</label>
                        <input type="text" wire:model="teamName" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs">
                        @error('teamName') <span class="text-rose-500 dark:text-rose-400">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">Jabatan / Posisi *</label>
                        <input type="text" wire:model="teamPosition" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs" placeholder="Lead Fullstack Developer">
                        @error('teamPosition') <span class="text-rose-500 dark:text-rose-400">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">URL Foto Profil</label>
                        <input type="text" wire:model="teamPhoto" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs" placeholder="https://images.unsplash.com/...">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">Biografi Singkat</label>
                        <textarea wire:model="teamBio" rows="3" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs"></textarea>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">URL LinkedIn</label>
                        <input type="text" wire:model="teamLinkedin" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs" placeholder="https://linkedin.com/in/username">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-gray-300 uppercase mb-1">Urutan Tampilan</label>
                        <input type="number" wire:model="teamOrder" class="w-full px-3 py-2.5 rounded-xl bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-xs">
                    </div>
                    <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="$set('showTeamModal', false)" class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-gray-300 font-bold">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 dark:bg-amber-500 dark:hover:bg-amber-600 text-slate-950 font-bold">Simpan Anggota Tim</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL: VIEW MESSAGE -->
    @if ($showMessageModal && $selectedMessage)
        <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-[#131A26] border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 max-w-lg w-full space-y-6 shadow-2xl text-slate-900 dark:text-white">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Detail Pesan Masuk</h3>
                    <button wire:click="$set('showMessageModal', false)" class="text-slate-400 hover:text-slate-900 dark:hover:text-white font-bold text-xl">&times;</button>
                </div>
                <div class="space-y-4 text-xs">
                    <div>
                        <span class="text-slate-500 dark:text-gray-400 block font-bold uppercase">Pengirim:</span>
                        <span class="text-slate-900 dark:text-white font-bold text-sm">{{ $selectedMessage->name }}</span> ({{ $selectedMessage->email }})
                    </div>
                    @if ($selectedMessage->phone)
                        <div>
                            <span class="text-slate-500 dark:text-gray-400 block font-bold uppercase">Nomor Telepon / WA:</span>
                            <span class="text-amber-700 dark:text-amber-400 font-bold">{{ $selectedMessage->phone }}</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $selectedMessage->phone) }}" target="_blank" class="ml-2 text-emerald-600 dark:text-emerald-400 underline font-bold">Chat via WhatsApp ↗</a>
                        </div>
                    @endif
                    <div>
                        <span class="text-slate-500 dark:text-gray-400 block font-bold uppercase">Subjek:</span>
                        <span class="text-slate-900 dark:text-white font-bold">{{ $selectedMessage->subject ?: 'Tanpa Subjek' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 dark:text-gray-400 block font-bold uppercase mb-1">Isi Pesan:</span>
                        <div class="bg-[#F8FAFC] dark:bg-[#0B0F17] p-4 rounded-xl text-slate-800 dark:text-gray-200 leading-relaxed border border-slate-200 dark:border-slate-800 whitespace-pre-wrap">
                            {{ $selectedMessage->message }}
                        </div>
                    </div>
                    <div class="text-[10px] text-slate-400 dark:text-gray-500 pt-2">
                        Diterima pada: {{ $selectedMessage->created_at->format('d F Y, H:i:s') }}
                    </div>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button wire:click="deleteMessage({{ $selectedMessage->id }})" wire:confirm="Hapus pesan ini?" class="px-4 py-2 rounded-xl bg-rose-50 dark:bg-rose-900/30 hover:bg-rose-100 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 text-xs font-bold">Hapus Pesan</button>
                    <button wire:click="$set('showMessageModal', false)" class="px-5 py-2 rounded-xl bg-slate-900 dark:bg-slate-800 text-amber-400 text-xs font-bold">Tutup</button>
                </div>
            </div>
        </div>
    @endif

    <!-- TAB 6: PAGE SECTIONS MANAGEMENT -->
    @if ($activeTab === 'sections')
        <div class="pt-8 space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">Pengaturan Section Halaman Beranda</h2>
                    <p class="text-xs text-slate-500 dark:text-gray-400 mt-1">Tampilkan atau sembunyikan setiap section di halaman utama website secara instan.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach ($pageSections as $section)
                    <div class="bg-white dark:bg-[#131A26] border {{ $section->is_enabled ? 'border-slate-200 dark:border-slate-800' : 'border-rose-300 dark:border-rose-500/30 bg-rose-50/30 dark:bg-rose-900/10' }} rounded-2xl p-5 flex items-center justify-between gap-4 transition-all duration-300 shadow-2xs">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 {{ $section->is_enabled ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $section->section_name }}</h4>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-gray-400 mt-1 ml-4">
                                Key: <code class="bg-slate-100 dark:bg-slate-900 px-1 py-0.5 rounded text-amber-700 dark:text-amber-300 text-[11px] font-mono">{{ $section->section_key }}</code>
                                · Urutan ke-{{ $section->order }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            @if ($section->section_key === 'tech_stack')
                                <button wire:click="openTechStackEditor" class="px-3 py-1.5 rounded-lg bg-amber-50 dark:bg-amber-500/10 hover:bg-amber-100 dark:hover:bg-amber-500/20 text-amber-800 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30 text-xs font-bold transition-all">
                                    ✏️ Edit Items
                                </button>
                            @endif
                            <button
                                wire:click="toggleSection('{{ $section->section_key }}')"
                                class="relative w-12 h-6 rounded-full transition-all duration-300 focus:outline-none {{ $section->is_enabled ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600' }}"
                                title="{{ $section->is_enabled ? 'Klik untuk sembunyikan' : 'Klik untuk tampilkan' }}"
                            >
                                <span class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow transition-transform duration-300 {{ $section->is_enabled ? 'translate-x-6' : 'translate-x-0' }}"></span>
                            </button>
                            <span class="text-xs font-bold w-14 text-right {{ $section->is_enabled ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                {{ $section->is_enabled ? 'Tampil' : 'Tersembunyi' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-xl p-4">
                <p class="text-xs text-amber-800 dark:text-amber-300 font-bold">ℹ️ Tips: Perubahan pada toggle akan langsung berlaku di halaman website utama tanpa perlu reload server.</p>
            </div>
        </div>
    @endif

    <!-- Tech Stack Editor Modal -->
    @if ($showTechStackEditor)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 dark:bg-black/70 backdrop-blur-xs" wire:click.self="$set('showTechStackEditor', false)">
            <div class="bg-white dark:bg-[#131A26] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl space-y-5 text-slate-900 dark:text-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">✏️ Edit Daftar Tech Stack</h3>
                    <button wire:click="$set('showTechStackEditor', false)" class="text-slate-400 hover:text-slate-900 dark:hover:text-white text-xl font-bold leading-none">&times;</button>
                </div>
                <p class="text-xs text-slate-500 dark:text-gray-400">Masukkan satu teknologi per baris. Perubahan akan langsung terlihat di halaman Beranda.</p>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-gray-300 mb-2">Daftar Teknologi (satu per baris)</label>
                    <textarea
                        wire:model="editingTechStack"
                        rows="8"
                        class="w-full px-4 py-3 bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white text-sm font-mono focus:outline-none focus:border-amber-500 resize-none leading-relaxed"
                        placeholder="Laravel 13&#10;Livewire 3&#10;Tailwind CSS v4&#10;React / Vue.js&#10;MySQL / PostgreSQL&#10;Docker &amp; AWS"
                    ></textarea>
                </div>
                <div class="flex gap-3 pt-1">
                    <button wire:click="$set('showTechStackEditor', false)" class="flex-1 px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-gray-300 text-sm font-bold">Batal</button>
                    <button wire:click="saveTechStack" class="flex-1 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 dark:bg-amber-500 dark:hover:bg-amber-600 text-slate-950 text-sm font-bold shadow-md shadow-amber-500/20">Simpan</button>
                </div>
            </div>
        </div>
    @endif

</div>
