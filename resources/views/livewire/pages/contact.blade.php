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

<div class="bg-[#F8FAFC] dark:bg-[#0B0F17] text-slate-900 dark:text-white min-h-screen transition-colors duration-300">
    <x-slot name="title">Konsultasi Proyek Gratis & Hubungi Kami - FlashDev</x-slot>
    <x-slot name="description">Hubungi tim FlashDev untuk mendapatkan konsultasi gratis dan estimasi penawaran harga pembuatan website/aplikasi Anda.</x-slot>
    <!-- Header Banner -->
    <section class="py-20 border-b border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#080C13] text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl space-y-4">
            <span class="px-3.5 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-700 dark:text-amber-400 text-xs font-bold uppercase tracking-wider">
                Hubungi Kami
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 dark:text-white">Konsultasi Proyek Gratis</h1>
            <p class="text-slate-600 dark:text-gray-400 text-lg">
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
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Informasi Kontak Direct</h2>
                    <p class="text-slate-600 dark:text-gray-400 text-sm leading-relaxed">
                        Kami siap mendengarkan kebutuhan aplikasi web Anda. Jangan ragu untuk berdiskusi melalui form atau saluran langsung di bawah ini.
                    </p>
                </div>

                <div class="space-y-6">
                    <div class="flex items-start space-x-4 bg-white dark:bg-[#131A26] p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xs">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white">Lokasi Kantor</h4>
                            <p class="text-xs text-slate-600 dark:text-gray-400 mt-1">Karawang, Jawa Barat, Indonesia</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 bg-white dark:bg-[#131A26] p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xs">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white">Email Resmi</h4>
                            <p class="text-xs text-amber-700 dark:text-amber-400 font-semibold mt-1">hallo.flashdev@flash14.id</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 bg-white dark:bg-[#131A26] p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xs">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h32a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white">WhatsApp / Telepon</h4>
                            <p class="text-xs text-amber-700 dark:text-amber-400 font-semibold mt-1">+62 821-2861-6647</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="lg:col-span-7 bg-white dark:bg-[#131A26] p-8 sm:p-10 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
                @if ($submitted)
                    <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/40 rounded-2xl p-6 text-center space-y-3">
                        <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 flex items-center justify-center mx-auto text-xl font-bold">✓</div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Pesan Anda Berhasil Terkirim!</h3>
                        <p class="text-sm text-slate-600 dark:text-gray-300">Terima kasih telah menghubungi FlashDev. Tim kami akan segera mempelajari pesan Anda dan menghubungi kembali.</p>
                        <button wire:click="$set('submitted', false)" class="text-xs text-amber-700 dark:text-amber-400 font-bold underline mt-2">Kirim Pesan Lain</button>
                    </div>
                @else
                    <form wire:submit="sendMessage" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-2">Nama Lengkap *</label>
                                <input type="text" wire:model="name" class="w-full px-4 py-3 bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors" placeholder="Masukkan nama Anda">
                                @error('name') <span class="text-xs text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-2">Alamat Email *</label>
                                <input type="email" wire:model="email" class="w-full px-4 py-3 bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors" placeholder="nama@email.com">
                                @error('email') <span class="text-xs text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-2">Nomor Telepon / WA</label>
                                <input type="text" wire:model="phone" class="w-full px-4 py-3 bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors" placeholder="081234567890">
                                @error('phone') <span class="text-xs text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-2">Subjek / Topik</label>
                                <input type="text" wire:model="subject" class="w-full px-4 py-3 bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors" placeholder="Pembuatan Website Company Profile">
                                @error('subject') <span class="text-xs text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-gray-300 uppercase tracking-wider mb-2">Detail Pesan / Rencana Proyek *</label>
                            <textarea wire:model="message" rows="5" class="w-full px-4 py-3 bg-[#F8FAFC] dark:bg-[#0B0F17] border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors" placeholder="Ceritakan ide proyek atau fitur yang Anda butuhkan..."></textarea>
                            @error('message') <span class="text-xs text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full py-4 rounded-xl bg-amber-500 hover:bg-amber-600 dark:bg-gradient-to-r dark:from-amber-500 dark:via-amber-400 dark:to-yellow-500 text-slate-950 font-extrabold shadow-md shadow-amber-500/20 hover:scale-[1.01] transition-all duration-300">
                            Kirim Pesan Konsultasi
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </section>
</div>
