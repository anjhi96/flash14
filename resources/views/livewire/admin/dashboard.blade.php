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

    <!-- Alert Banner -->
    @if (session()->has('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <x-alert variant="success">{{ session('success') }}</x-alert>
        </div>
    @endif

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
        </div>

        <!-- TAB 1: OVERVIEW -->
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

                <!-- Recent Messages -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden">
                    <div class="px-5 py-4 border-b border-outline-variant flex items-center justify-between">
                        <h3 class="text-sm font-bold text-on-surface uppercase tracking-wider">Pesan Masuk Terbaru</h3>
                        <button wire:click="setTab('messages')" class="text-xs font-semibold text-primary hover:underline cursor-pointer">
                            Lihat Semua Pesan &rarr;
                        </button>
                    </div>

                    @if ($messages->isEmpty())
                        <div class="p-6 text-center text-xs text-on-surface-variant">Belum ada pesan masuk dari pengunjung.</div>
                    @else
                        <div class="divide-y divide-outline-variant">
                            @foreach ($messages->take(5) as $msg)
                                <div class="px-5 py-3.5 flex items-center justify-between hover:bg-surface-container cursor-pointer transition-colors" wire:click="viewMessage({{ $msg->id }})">
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

        <!-- TAB 2: SERVICES CRUD -->
        @if ($activeTab === 'services')
            <div class="pt-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-on-surface">Daftar Layanan</h3>
                        <p class="text-xs text-on-surface-variant">Kelola paket solusi dan layanan software agensi.</p>
                    </div>
                    <x-primary-button type="button" wire:click="openServiceModal()">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        Tambah Layanan
                    </x-primary-button>
                </div>

                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-surface-container text-on-surface-variant uppercase tracking-wider border-b border-outline-variant font-bold">
                            <tr>
                                <th class="py-3 px-4 w-16">Urutan</th>
                                <th class="py-3 px-4">Judul Layanan</th>
                                <th class="py-3 px-4">Deskripsi Singkat</th>
                                <th class="py-3 px-4 w-28">Status</th>
                                <th class="py-3 px-4 w-36 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @foreach ($services as $s)
                                <tr class="hover:bg-surface-container transition-colors">
                                    <td class="py-3 px-4 font-mono font-semibold text-on-surface-variant">{{ $s->order }}</td>
                                    <td class="py-3 px-4 font-bold text-on-surface">{{ $s->title }}</td>
                                    <td class="py-3 px-4 text-on-surface-variant max-w-sm truncate">{{ $s->short_description }}</td>
                                    <td class="py-3 px-4">
                                        <button type="button" wire:click="toggleServiceActive({{ $s->id }})" class="cursor-pointer">
                                            <x-badge :variant="$s->is_active ? 'success' : 'neutral'">
                                                {{ $s->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                                            </x-badge>
                                        </button>
                                    </td>
                                    <td class="py-3 px-4 text-right space-x-1">
                                        <x-link-button wire:click="openServiceModal({{ $s->id }})">Edit</x-link-button>
                                        <x-link-button variant="danger" wire:click="deleteService({{ $s->id }})" wire:confirm="Hapus layanan ini?">Hapus</x-link-button>
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
                        <h3 class="text-lg font-bold text-on-surface">Portofolio Proyek</h3>
                        <p class="text-xs text-on-surface-variant">Koleksi karya & studi kasus sistem klien.</p>
                    </div>
                    <x-primary-button type="button" wire:click="openProjectModal()">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        Tambah Proyek
                    </x-primary-button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($projects as $p)
                        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden flex flex-col justify-between hover:border-outline transition-all">
                            <div>
                                <div class="aspect-video bg-surface-container relative overflow-hidden">
                                    @if ($p->thumbnail)
                                        <img src="{{ $p->thumbnail }}" alt="{{ $p->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-xs text-on-surface-variant">No Image</div>
                                    @endif
                                    <div class="absolute top-2.5 left-2.5">
                                        <span class="px-2.5 py-1 bg-black/70 backdrop-blur-xs text-white text-[10px] font-semibold rounded-md">
                                            {{ $p->category }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-4 space-y-1.5">
                                    <h4 class="font-bold text-on-surface text-sm">{{ $p->title }}</h4>
                                    <p class="text-xs text-primary font-semibold">Klien: {{ $p->client ?? 'N/A' }}</p>
                                    <p class="text-xs text-on-surface-variant line-clamp-2">{{ $p->description }}</p>
                                </div>
                            </div>

                            <div class="p-4 pt-3 flex items-center justify-between border-t border-outline-variant mt-3">
                                <button type="button" wire:click="toggleProjectFeatured({{ $p->id }})" class="cursor-pointer">
                                    <x-badge :variant="$p->is_featured ? 'primary' : 'neutral'">
                                        <span class="material-symbols-outlined text-[12px]">star</span>
                                        {{ $p->is_featured ? 'FEATURED' : 'STANDAR' }}
                                    </x-badge>
                                </button>

                                <div class="space-x-1">
                                    <x-link-button wire:click="openProjectModal({{ $p->id }})">Edit</x-link-button>
                                    <x-link-button variant="danger" wire:click="deleteProject({{ $p->id }})" wire:confirm="Hapus proyek ini?">Hapus</x-link-button>
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
            </div>
        @endif

        <!-- TAB 5: MESSAGES INBOX -->
        @if ($activeTab === 'messages')
            <div class="pt-6 space-y-4">
                <div>
                    <h3 class="text-lg font-bold text-on-surface">Kotak Pesan Masuk</h3>
                    <p class="text-xs text-on-surface-variant">{{ $messagesUnreadCount }} Pesan Belum Dibaca</p>
                </div>

                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden">
                    @if ($messages->isEmpty())
                        <div class="p-8 text-center text-on-surface-variant text-xs">Belum ada pesan masuk.</div>
                    @else
                        <div class="divide-y divide-outline-variant text-xs">
                            @foreach ($messages as $msg)
                                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 {{ !$msg->is_read ? 'bg-primary-container/40' : '' }}">
                                    <div class="space-y-1 cursor-pointer flex-1" wire:click="viewMessage({{ $msg->id }})">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-on-surface text-xs">{{ $msg->name }}</span>
                                            <span class="text-on-surface-variant">({{ $msg->email }})</span>
                                            @if ($msg->phone)
                                                <span class="text-on-surface-variant">&bull; WA: {{ $msg->phone }}</span>
                                            @endif
                                            @if (!$msg->is_read)
                                                <x-badge variant="error">UNREAD</x-badge>
                                            @endif
                                        </div>
                                        <p class="font-semibold text-primary">{{ $msg->subject ?: 'Tanpa Subjek' }}</p>
                                        <p class="text-on-surface-variant line-clamp-1">{{ $msg->message }}</p>
                                    </div>

                                    <div class="flex items-center space-x-1 shrink-0">
                                        <span class="text-[11px] text-on-surface-variant mr-2">{{ $msg->created_at->format('d M Y, H:i') }}</span>
                                        <x-link-button wire:click="toggleMessageRead({{ $msg->id }})">
                                            {{ $msg->is_read ? 'Tandai Belum Dibaca' : 'Tandai Dibaca' }}
                                        </x-link-button>
                                        <x-link-button variant="danger" wire:click="deleteMessage({{ $msg->id }})" wire:confirm="Hapus pesan ini?">Hapus</x-link-button>
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
                    <h2 class="text-lg font-bold text-on-surface">Pengaturan Modul Section Beranda</h2>
                    <p class="text-xs text-on-surface-variant">Aktifkan atau non-aktifkan bagian halaman beranda tanpa menyentuh kode program.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($pageSections as $section)
                        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex items-center justify-between gap-4 transition-all">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full shrink-0 {{ $section->is_enabled ? 'bg-state-success' : 'bg-state-error' }}"></span>
                                    <h4 class="text-xs font-bold text-on-surface truncate">{{ $section->section_name }}</h4>
                                </div>
                                <p class="text-[11px] text-on-surface-variant mt-0.5 ml-4">
                                    Key: <code class="bg-surface-container px-1 py-0.5 rounded text-primary font-mono">{{ $section->section_key }}</code>
                                    &middot; Urutan ke-{{ $section->order }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2.5 shrink-0">
                                @if ($section->section_key === 'tech_stack')
                                    <x-link-button wire:click="openTechStackEditor">Edit Items</x-link-button>
                                @endif
                                <x-switch-input
                                    :checked="$section->is_enabled"
                                    wire:click="toggleSection('{{ $section->section_key }}')"
                                    title="{{ $section->is_enabled ? 'Klik untuk non-aktifkan' : 'Klik untuk aktifkan' }}"
                                />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- MODAL: SERVICE FORM -->
    @if ($showServiceModal)
        <div x-data class="fixed inset-0 bg-on-surface/60 z-50 flex items-center justify-center p-4" wire:click.self="$set('showServiceModal', false)" x-on:keydown.escape.window="$wire.set('showServiceModal', false)">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 max-w-lg w-full space-y-4 shadow-xl text-on-surface">
                <div class="flex items-center justify-between border-b border-outline-variant pb-3">
                    <h3 class="text-base font-bold text-on-surface">{{ $serviceId ? 'Edit Layanan' : 'Tambah Layanan Baru' }}</h3>
                    <button wire:click="$set('showServiceModal', false)" class="text-on-surface-variant hover:text-on-surface font-bold text-xl cursor-pointer" aria-label="Tutup">&times;</button>
                </div>
                <form wire:submit="saveService" class="space-y-3.5 text-xs">
                    <div>
                        <x-input-label value="Judul Layanan *" />
                        <x-text-input type="text" wire:model="serviceTitle" />
                        @error('serviceTitle') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="Nama Icon (contoh: code-bracket, cloud, database)" />
                        <x-text-input type="text" wire:model="serviceIcon" />
                    </div>
                    <div>
                        <x-input-label value="Deskripsi Singkat *" />
                        <x-text-input type="text" wire:model="serviceShortDescription" />
                        @error('serviceShortDescription') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="Deskripsi Lengkap *" />
                        <x-textarea-input wire:model="serviceDescription" :rows="4" />
                        @error('serviceDescription') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-3 items-center">
                        <div>
                            <x-input-label value="Urutan Tampilan *" />
                            <x-text-input type="number" wire:model="serviceOrder" />
                        </div>
                        <div class="flex items-center pt-4">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" wire:model="serviceIsActive" class="rounded border-outline text-primary focus:ring-primary">
                                <span class="font-semibold text-xs text-on-surface-variant">Status Aktif</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 pt-3 border-t border-outline-variant">
                        <x-secondary-button type="button" wire:click="$set('showServiceModal', false)">Batal</x-secondary-button>
                        <x-primary-button type="submit">Simpan Layanan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL: PROJECT FORM -->
    @if ($showProjectModal)
        <div x-data class="fixed inset-0 bg-on-surface/60 z-50 flex items-center justify-center p-4" wire:click.self="$set('showProjectModal', false)" x-on:keydown.escape.window="$wire.set('showProjectModal', false)">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 max-w-lg w-full space-y-4 shadow-xl text-on-surface">
                <div class="flex items-center justify-between border-b border-outline-variant pb-3">
                    <h3 class="text-base font-bold text-on-surface">{{ $projectId ? 'Edit Proyek' : 'Tambah Proyek Baru' }}</h3>
                    <button wire:click="$set('showProjectModal', false)" class="text-on-surface-variant hover:text-on-surface font-bold text-xl cursor-pointer" aria-label="Tutup">&times;</button>
                </div>
                <form wire:submit="saveProject" class="space-y-3 text-xs">
                    <div>
                        <x-input-label value="Judul Proyek *" />
                        <x-text-input type="text" wire:model="projectTitle" />
                        @error('projectTitle') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-input-label value="Nama Klien" />
                            <x-text-input type="text" wire:model="projectClient" />
                        </div>
                        <div>
                            <x-input-label value="Kategori *" />
                            <x-select-input wire:model="projectCategory">
                                <option value="E-Commerce">E-Commerce</option>
                                <option value="Custom Web App">Custom Web App</option>
                                <option value="Company Profile">Company Profile</option>
                                <option value="SaaS App">SaaS App</option>
                            </x-select-input>
                        </div>
                    </div>
                    <div>
                        <x-input-label value="Deskripsi Proyek *" />
                        <x-textarea-input wire:model="projectDescription" :rows="3" />
                        @error('projectDescription') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-input-label value="URL Gambar Thumbnail" />
                        <x-text-input type="text" wire:model="projectThumbnail" placeholder="https://..." />
                        @error('projectThumbnail') <span class="text-state-error font-semibold text-[11px]">{{ $message }}</span> @enderror
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
                                <input type="checkbox" wire:model="projectIsFeatured" class="rounded border-outline text-primary focus:ring-primary">
                                <span class="font-semibold text-xs text-on-surface-variant">Featured di Home</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 pt-3 border-t border-outline-variant">
                        <x-secondary-button type="button" wire:click="$set('showProjectModal', false)">Batal</x-secondary-button>
                        <x-primary-button type="submit">Simpan Proyek</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

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

    <!-- MODAL: VIEW MESSAGE -->
    @if ($showMessageModal && $selectedMessage)
        <div x-data class="fixed inset-0 bg-on-surface/60 z-50 flex items-center justify-center p-4" wire:click.self="$set('showMessageModal', false)" x-on:keydown.escape.window="$wire.set('showMessageModal', false)">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 max-w-lg w-full space-y-4 shadow-xl text-on-surface">
                <div class="flex items-center justify-between border-b border-outline-variant pb-3">
                    <h3 class="text-base font-bold text-on-surface">Detail Pesan Masuk</h3>
                    <button wire:click="$set('showMessageModal', false)" class="text-on-surface-variant hover:text-on-surface font-bold text-xl cursor-pointer" aria-label="Tutup">&times;</button>
                </div>
                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-on-surface-variant block font-semibold uppercase text-[10px]">Pengirim</span>
                        <span class="text-on-surface font-bold text-sm">{{ $selectedMessage->name }}</span>
                        <span class="text-on-surface-variant">({{ $selectedMessage->email }})</span>
                    </div>
                    @if ($selectedMessage->phone)
                        <div>
                            <span class="text-on-surface-variant block font-semibold uppercase text-[10px]">Nomor WhatsApp</span>
                            <span class="text-primary font-bold">{{ $selectedMessage->phone }}</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $selectedMessage->phone) }}" target="_blank" class="ml-2 text-state-success underline font-semibold">Buka WhatsApp &#8599;</a>
                        </div>
                    @endif
                    <div>
                        <span class="text-on-surface-variant block font-semibold uppercase text-[10px]">Subjek</span>
                        <span class="text-on-surface font-semibold">{{ $selectedMessage->subject ?: 'Tanpa Subjek' }}</span>
                    </div>
                    <div>
                        <span class="text-on-surface-variant block font-semibold uppercase text-[10px] mb-1">Isi Pesan</span>
                        <div class="bg-surface-container p-3.5 rounded-lg text-on-surface leading-relaxed border border-outline-variant whitespace-pre-wrap">
                            {{ $selectedMessage->message }}
                        </div>
                    </div>
                    <div class="text-[10px] text-on-surface-variant pt-1">
                        Diterima: {{ $selectedMessage->created_at->format('d F Y, H:i:s') }}
                    </div>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-outline-variant">
                    <x-danger-button wire:click="deleteMessage({{ $selectedMessage->id }})" wire:confirm="Hapus pesan ini?">Hapus Pesan</x-danger-button>
                    <x-secondary-button wire:click="$set('showMessageModal', false)">Tutup</x-secondary-button>
                </div>
            </div>
        </div>
    @endif

    <!-- Tech Stack Editor Modal -->
    @if ($showTechStackEditor)
        <div x-data class="fixed inset-0 z-50 flex items-center justify-center bg-on-surface/60" wire:click.self="$set('showTechStackEditor', false)" x-on:keydown.escape.window="$wire.set('showTechStackEditor', false)">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-md mx-4 shadow-xl space-y-4 text-on-surface">
                <div class="flex items-center justify-between border-b border-outline-variant pb-3">
                    <h3 class="text-base font-bold text-on-surface">Edit Daftar Tech Stack</h3>
                    <button wire:click="$set('showTechStackEditor', false)" class="text-on-surface-variant hover:text-on-surface text-xl font-bold leading-none cursor-pointer" aria-label="Tutup">&times;</button>
                </div>
                <p class="text-xs text-on-surface-variant">Tuliskan satu nama teknologi per baris. Daftar ini akan ditampilkan di banner beranda.</p>
                <div>
                    <x-input-label value="Teknologi (1 Per Baris)" class="uppercase tracking-wider" />
                    <x-textarea-input
                        wire:model="editingTechStack"
                        :rows="7"
                        class="font-mono leading-relaxed"
                        placeholder="Laravel&#10;Livewire&#10;Tailwind CSS&#10;Vue.js&#10;PostgreSQL"
                    />
                </div>
                <div class="flex gap-2.5 pt-1 border-t border-outline-variant">
                    <x-secondary-button class="flex-1" wire:click="$set('showTechStackEditor', false)">Batal</x-secondary-button>
                    <x-primary-button class="flex-1" wire:click="saveTechStack">Simpan</x-primary-button>
                </div>
            </div>
        </div>
    @endif

</div>
