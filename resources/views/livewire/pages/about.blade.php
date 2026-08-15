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

<div class="bg-[#F8FAFC] dark:bg-[#0B0F17] text-slate-900 dark:text-white min-h-screen transition-colors duration-300">
    <x-slot name="title">Tentang FlashDev - Tim & Visi Agensi Web Development</x-slot>
    <x-slot name="description">Mengenal tim pengembang software, desainer UI/UX, serta visi misi FlashDev dalam mendukung transformasi digital bisnis Anda.</x-slot>
    <!-- Header Banner -->
    <section class="py-20 border-b border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#080C13] text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl space-y-4">
            <span class="px-3.5 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-700 dark:text-amber-400 text-xs font-bold uppercase tracking-wider">
                Tentang FlashDev
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 dark:text-white">Mitra Teknologi & Transformasi Digital Anda</h1>
            <p class="text-slate-600 dark:text-gray-400 text-lg">
                Kami adalah tim ahli teknologi, insinyur perangkat lunak, dan desainer UI/UX yang berdedikasi membangun aplikasi berkualitas tinggi.
            </p>
        </div>
    </section>

    <!-- Company Story & Vision Mission -->
    <section class="py-20 border-b border-slate-200/80 dark:border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">Visi & Komitmen Kami</h2>
                <p class="text-slate-600 dark:text-gray-300 text-sm leading-relaxed">
                    FlashDev didirikan dengan misi membantu perusahaan dan UMKM bersaing di era digital melalui perangkat lunak kustom yang tidak hanya menarik secara visual, tetapi juga sangat cepat, aman, dan mudah dioperasikan.
                </p>
                <div class="space-y-4 pt-2">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30 flex items-center justify-center shrink-0 font-bold">1</div>
                        <div>
                            <h4 class="text-base font-bold text-slate-900 dark:text-white">Clean Code & Scalable Architecture</h4>
                            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Pengodean rapi berstandar industri yang siap dikembangkan tanpa batas.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30 flex items-center justify-center shrink-0 font-bold">2</div>
                        <div>
                            <h4 class="text-base font-bold text-slate-900 dark:text-white">User-Centric Design</h4>
                            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Fokus utama pada kenyamanan & alur pengguna untuk memaksimalkan angka konversi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#131A26] p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-3">Nilai-Nilai Utama (Core Values)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-amber-700 dark:text-amber-400 font-bold text-sm mb-1">Kecepatan & Performa</h4>
                        <p class="text-xs text-slate-600 dark:text-gray-400">Setiap milidetik berharga. Kami mengoptimalkan setiap aset & query database.</p>
                    </div>
                    <div>
                        <h4 class="text-amber-700 dark:text-amber-400 font-bold text-sm mb-1">Transparansi</h4>
                        <p class="text-xs text-slate-600 dark:text-gray-400">Komunikasi terbuka terkait perkembangan proyek & dokumentasi lengkap.</p>
                    </div>
                    <div>
                        <h4 class="text-amber-700 dark:text-amber-400 font-bold text-sm mb-1">Keamanan Sistem</h4>
                        <p class="text-xs text-slate-600 dark:text-gray-400">Perlindungan data sensitif dan penerapan enkripsi standar keamanan terkini.</p>
                    </div>
                    <div>
                        <h4 class="text-amber-700 dark:text-amber-400 font-bold text-sm mb-1">Dukungan Berkelanjutan</h4>
                        <p class="text-xs text-slate-600 dark:text-gray-400">Kerja sama jangka panjang dengan bantuan teknis pasca-rilis.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Showcase -->
    @if ($teamMembers->count() > 0)
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
                <h2 class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-widest">Tim Talenta</h2>
                <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white">Di Balik Layar FlashDev</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                @foreach ($teamMembers as $member)
                    <div class="bg-white dark:bg-[#131A26] rounded-2xl p-6 border border-slate-200 dark:border-slate-800 text-center space-y-4 hover:border-amber-400 dark:hover:border-amber-500/40 hover:shadow-md transition-all duration-300">
                        <img src="{{ $member->avatar }}" alt="{{ $member->name }}" class="w-24 h-24 rounded-full mx-auto object-cover border-2 border-amber-400 dark:border-amber-500/40">
                        <div>
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white">{{ $member->name }}</h4>
                            <p class="text-xs text-amber-700 dark:text-amber-400 font-semibold mt-0.5">{{ $member->role }}</p>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-gray-400 leading-relaxed">{{ $member->bio }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
