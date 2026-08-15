<?php

use App\Models\ContactMessage;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $subject = '';
    public string $message = '';
    public bool $submitted = false;

    public function sendMessage(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:5',
        ]);

        ContactMessage::create($validated);

        $this->reset(['name', 'email', 'phone', 'subject', 'message']);
        $this->submitted = true;
    }
}; ?>

<div class="bg-gray-900 text-white min-h-screen">
    <x-slot name="title">Konsultasi Proyek Gratis & Hubungi Kami - FlashDev</x-slot>
    <x-slot name="description">Hubungi tim FlashDev untuk mendapatkan konsultasi gratis dan estimasi penawaran harga pembuatan website/aplikasi Anda.</x-slot>
    <!-- Header Banner -->
    <section class="py-20 border-b border-gray-800 bg-gray-950/60 text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl space-y-4">
            <span class="px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 text-xs font-semibold uppercase tracking-wider">
                Hubungi Kami
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold">Konsultasi Proyek Gratis</h1>
            <p class="text-gray-400 text-lg">
                Punya pertanyaan atau rencana proyek website? Kirimkan pesan Anda dan tim ahli kami akan merespons dalam waktu 1x24 jam.
            </p>
        </div>
    </section>

    <!-- Main Contact Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left Info Panel -->
            <div class="lg:col-span-5 space-y-8">
                <div class="space-y-4">
                    <h2 class="text-2xl font-bold text-white">Informasi Kontak Direct</h2>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Kami siap mendengarkan kebutuhan aplikasi web Anda. Jangan ragu untuk berdiskusi melalui form atau saluran langsung di bawah ini.
                    </p>
                </div>

                <div class="space-y-6">
                    <div class="flex items-start space-x-4 bg-gray-800/40 p-5 rounded-2xl border border-gray-700/60">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white">Alamat Kantor</h4>
                            <p class="text-xs text-gray-400 mt-1">Gedung Flash Tower Lt. 5, Jl. Asia Afrika No. 88, Bandung / Jakarta</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 bg-gray-800/40 p-5 rounded-2xl border border-gray-700/60">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white">Email Resmi</h4>
                            <p class="text-xs text-gray-400 mt-1">halo@flashdev.co.id / info@flashdev.co.id</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 bg-gray-800/40 p-5 rounded-2xl border border-gray-700/60">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h32a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white">WhatsApp & Telepon</h4>
                            <p class="text-xs text-gray-400 mt-1">+62 812-3456-7890 (Senin - Jumat, 08:00 - 17:00 WIB)</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-gradient-to-br from-indigo-900/40 to-purple-900/30 rounded-2xl border border-indigo-500/30">
                    <h4 class="text-sm font-bold text-white">Respon Cepat via WhatsApp</h4>
                    <p class="text-xs text-indigo-200 mt-1">Ingin respon instan tanpa mengisi form?</p>
                    <a href="https://wa.me/6281234567890?text=Halo%20FlashDev,%20saya%20ingin%20konsultasi%20pembuatan%20website" target="_blank" rel="noopener noreferrer" class="inline-flex items-center mt-3 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg transition-colors">
                        Chat WhatsApp Sekarang
                    </a>
                </div>
            </div>

            <!-- Right Interactive Form -->
            <div class="lg:col-span-7 bg-gray-800/60 p-8 sm:p-12 rounded-3xl border border-gray-700/60">
                @if ($submitted)
                    <div class="bg-emerald-500/10 border border-emerald-500/40 rounded-2xl p-6 text-center space-y-3 mb-6">
                        <div class="w-12 h-12 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto text-xl font-bold">✓</div>
                        <h4 class="text-lg font-bold text-white">Pesan Anda Berhasil Terkirim!</h4>
                        <p class="text-xs text-emerald-200">Terima kasih telah menghubungi FlashDev. Tim kami akan segera meninjau pesan Anda.</p>
                        <button wire:click="$set('submitted', false)" class="text-xs text-indigo-400 underline font-semibold mt-2">Kirim Pesan Lain</button>
                    </div>
                @endif

                <form wire:submit="sendMessage" class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-300">Nama Lengkap <span class="text-indigo-400">*</span></label>
                            <input type="text" id="name" wire:model="name" class="w-full px-4 py-3 rounded-xl bg-gray-900/80 border border-gray-700 text-white text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors" placeholder="John Doe">
                            @error('name') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-300">Alamat Email <span class="text-indigo-400">*</span></label>
                            <input type="email" id="email" wire:model="email" class="w-full px-4 py-3 rounded-xl bg-gray-900/80 border border-gray-700 text-white text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors" placeholder="john@example.com">
                            @error('email') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-gray-300">Nomor WhatsApp / HP</label>
                            <input type="text" id="phone" wire:model="phone" class="w-full px-4 py-3 rounded-xl bg-gray-900/80 border border-gray-700 text-white text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors" placeholder="081234567890">
                            @error('phone') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="subject" class="block text-xs font-bold uppercase tracking-wider text-gray-300">Subjek / Topik</label>
                            <input type="text" id="subject" wire:model="subject" class="w-full px-4 py-3 rounded-xl bg-gray-900/80 border border-gray-700 text-white text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors" placeholder="Pembuatan Website Company Profile">
                            @error('subject') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="message" class="block text-xs font-bold uppercase tracking-wider text-gray-300">Detail Pesan / Kebutuhan Proyek <span class="text-indigo-400">*</span></label>
                        <textarea id="message" wire:model="message" rows="5" class="w-full px-4 py-3 rounded-xl bg-gray-900/80 border border-gray-700 text-white text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors" placeholder="Ceritakan ide proyek, kisaran anggaran, atau timeline pengerjaan yang diinginkan..."></textarea>
                        @error('message') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled" class="w-full py-4 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold text-sm shadow-lg shadow-indigo-500/25 transition-all duration-300 hover:scale-[1.01] flex items-center justify-center">
                        <span wire:loading.remove>Kirim Pesan Konsultasi</span>
                        <span wire:loading>Mengirim Pesan...</span>
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>
