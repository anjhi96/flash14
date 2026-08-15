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

<div class="bg-gray-900 text-white min-h-screen">
    <x-slot name="title">Tentang FlashDev - Tim & Visi Agensi Web Development</x-slot>
    <x-slot name="description">Mengenal tim pengembang software, desainer UI/UX, serta visi misi FlashDev dalam mendukung transformasi digital bisnis Anda.</x-slot>
    <!-- Header Banner -->
    <section class="py-20 border-b border-gray-800 bg-gray-950/60 text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl space-y-4">
            <span class="px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 text-xs font-semibold uppercase tracking-wider">
                Tentang FlashDev
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold">Mitra Teknologi & Transformasi Digital Anda</h1>
            <p class="text-gray-400 text-lg">
                Kami adalah tim ahli teknologi, insinyur perangkat lunak, dan desainer UI/UX yang berdedikasi membangun aplikasi berkualitas tinggi.
            </p>
        </div>
    </section>

    <!-- Company Story & Vision Mission -->
    <section class="py-20 border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <h2 class="text-3xl font-extrabold text-white">Visi & Komitmen Kami</h2>
                <p class="text-gray-300 text-sm leading-relaxed">
                    FlashDev didirikan dengan misi membantu perusahaan dan UMKM bersaing di era digital melalui perangkat lunak kustom yang tidak hanya menarik secara visual, tetapi juga sangat cepat, aman, dan mudah dioperasikan.
                </p>
                <div class="space-y-4 pt-2">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0 font-bold">1</div>
                        <div>
                            <h4 class="text-base font-bold text-white">Clean Code & Scalable Architecture</h4>
                            <p class="text-xs text-gray-400 mt-0.5">Pengodean rapi berstandar industri yang siap dikembangkan tanpa batas.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0 font-bold">2</div>
                        <div>
                            <h4 class="text-base font-bold text-white">User-Centric Design</h4>
                            <p class="text-xs text-gray-400 mt-0.5">Fokus utama pada kenyamanan & alur pengguna untuk memaksimalkan angka konversi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800/40 p-8 rounded-3xl border border-gray-700/60 space-y-6">
                <h3 class="text-xl font-bold text-white border-b border-gray-700/60 pb-3">Nilai-Nilai Utama (Core Values)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-bold text-indigo-300">Integritas & Transparansi</h4>
                        <p class="text-xs text-gray-400 mt-1">Laporan alur kerja terbuka & tanpa biaya tersembunyi.</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-indigo-300">Kecepatan Eksekusi</h4>
                        <p class="text-xs text-gray-400 mt-1">Pengerjaan disiplin waktu sesuai sprint milestone.</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-indigo-300">Inovasi Berkelanjutan</h4>
                        <p class="text-xs text-gray-400 mt-1">Adopsi framework & standar keamanan terbaru.</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-indigo-300">Orientasi Hasil</h4>
                        <p class="text-xs text-gray-400 mt-1">Mengukur sukses dari dampak nyata pada bisnis Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Members Showcase -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
                <h2 class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Tim Talenta Kami</h2>
                <h3 class="text-3xl sm:text-4xl font-extrabold text-white">Orang di Balik Karya Kami</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($teamMembers as $member)
                    <div class="bg-gray-800/60 rounded-2xl p-6 border border-gray-700/60 text-center space-y-4 hover:border-indigo-500/50 transition-all duration-300 hover:-translate-y-1">
                        <div class="w-24 h-24 rounded-full overflow-hidden mx-auto border-2 border-indigo-500/40 bg-gray-900">
                            @if ($member->photo)
                                <img src="{{ $member->photo }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-500 font-bold text-xl">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white">{{ $member->name }}</h4>
                            <p class="text-xs font-medium text-indigo-300 mt-0.5">{{ $member->position }}</p>
                        </div>
                        @if ($member->bio)
                            <p class="text-xs text-gray-400 leading-relaxed">{{ $member->bio }}</p>
                        @endif
                        @if ($member->linkedin_url)
                            <a href="{{ $member->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-xs font-semibold text-gray-400 hover:text-indigo-400">
                                Connect di LinkedIn
                                <svg class="w-3.5 h-3.5 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
