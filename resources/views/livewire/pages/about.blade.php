<?php

use App\Models\TeamMember;
use Livewire\Volt\Component;

new class extends Component {
    public function with(): array
    {
        return [
            'teamMembers' => TeamMember::orderBy('order')->get(),
        ];
    }
}; ?>

<div class="bg-surface text-on-surface min-h-screen transition-colors duration-200">
    <x-slot name="title">Tentang FlashDev - Rekayasa Perangkat Lunak</x-slot>
    <x-slot name="description">Mengenal tim pengembang software, desainer UI/UX, serta visi misi FlashDev dalam mendukung transformasi digital bisnis Anda.</x-slot>

    <!-- Header Banner -->
    <section class="py-14 border-b border-outline-variant bg-surface-container-lowest">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl text-center space-y-3">
            <x-badge variant="primary">Tentang FlashDev</x-badge>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-on-surface">Mitra Teknologi & Rekayasa Perangkat Lunak</h1>
            <p class="text-sm text-on-surface-variant max-w-xl mx-auto leading-relaxed">
                Kami adalah kelompok insinyur perangkat lunak dan konsultan teknologi yang berdedikasi membangun aplikasi berkualitas tinggi dan berkinerja stabil.
            </p>
        </div>
    </section>

    <!-- Company Story & Vision Mission -->
    <section class="py-14 border-b border-outline-variant">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div class="space-y-4">
                <span class="text-xs font-bold text-primary uppercase tracking-wider">Visi & Komitmen</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-on-surface">Teknologi Andal untuk Kebutuhan Riil</h2>
                <p class="text-on-surface-variant text-xs sm:text-sm leading-relaxed">
                    FlashDev didirikan untuk menjembatani kompleksitas teknis dengan kebutuhan bisnis riil. Kami menghadirkan arsitektur kode bersih, sistem yang dapat diskalakan (*scalable*), serta antarmuka fungsional.
                </p>
                <div class="space-y-3 pt-2">
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-md bg-primary-container text-on-primary-container flex items-center justify-center shrink-0 text-xs font-mono font-bold">1</div>
                        <div>
                            <h3 class="text-sm font-bold text-on-surface">Clean Architecture & Maintainability</h3>
                            <p class="text-xs text-on-surface-variant mt-0.5">Pengodean terstruktur berstandar industri dengan dokumentasi komprehensif.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-md bg-primary-container text-on-primary-container flex items-center justify-center shrink-0 text-xs font-mono font-bold">2</div>
                        <div>
                            <h3 class="text-sm font-bold text-on-surface">Operational Clarity</h3>
                            <p class="text-xs text-on-surface-variant mt-0.5">Desain fungsional yang berfokus pada efisiensi alur kerja dan kemudahan operasional.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-surface-container-lowest p-6 sm:p-7 rounded-xl border border-outline-variant space-y-4">
                <h3 class="text-sm font-bold text-on-surface uppercase tracking-wider border-b border-outline-variant pb-2.5">Nilai-Nilai Kerja (*Core Principles*)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-primary font-semibold text-xs mb-0.5">Kecepatan & Optimasi</h4>
                        <p class="text-xs text-on-surface-variant">Efisiensi kueri database dan performa pemuatan data optimal.</p>
                    </div>
                    <div>
                        <h4 class="text-primary font-semibold text-xs mb-0.5">Transparansi</h4>
                        <p class="text-xs text-on-surface-variant">Pelaporan progres teknis secara berkala dan terdokumentasi.</p>
                    </div>
                    <div>
                        <h4 class="text-primary font-semibold text-xs mb-0.5">Keamanan Sistem</h4>
                        <p class="text-xs text-on-surface-variant">Proteksi data, validasi ketat, dan enkripsi standar industri.</p>
                    </div>
                    <div>
                        <h4 class="text-primary font-semibold text-xs mb-0.5">Dukungan Berkelanjutan</h4>
                        <p class="text-xs text-on-surface-variant">Pemeliharaan berkesinambungan dan respons teknis tanggap.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Showcase -->
    @if ($teamMembers->count() > 0)
    <section class="py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mb-10 space-y-1 text-center sm:text-left">
                <span class="text-xs font-bold text-primary uppercase tracking-wider">Struktur Organisasi</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-on-surface">Tim Rekayasa & Manajemen</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($teamMembers as $member)
                    <div class="bg-surface-container-lowest rounded-xl p-5 border border-outline-variant text-center space-y-3 hover:border-outline transition-all">
                        <div class="w-16 h-16 rounded-full overflow-hidden mx-auto bg-surface-container border-2 border-primary/60">
                            @if ($member->photo)
                                <img src="{{ $member->photo }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center font-bold text-on-surface-variant text-base">{{ substr($member->name, 0, 1) }}</div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-on-surface">{{ $member->name }}</h3>
                            <p class="text-xs text-primary font-semibold mt-0.5">{{ $member->position }}</p>
                        </div>
                        <p class="text-xs text-on-surface-variant leading-relaxed line-clamp-2">{{ $member->bio }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
