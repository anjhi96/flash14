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

<div class="bg-[#F8FAFC] dark:bg-[#0B0F17] text-slate-900 dark:text-slate-100 min-h-screen transition-colors duration-200">
    <x-slot name="title">Konsultasi Proyek & Kontak - FlashDev</x-slot>
    <x-slot name="description">Hubungi tim FlashDev untuk mendapatkan konsultasi teknis dan estimasi penawaran harga pembuatan website atau aplikasi enterprise.</x-slot>

    <!-- Header Banner -->
    <section class="py-14 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-[#111722]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl text-center space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-amber-50 dark:bg-amber-500/10 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30 text-xs font-semibold uppercase tracking-wider">
                <span class="material-symbols-outlined text-[14px]">contact_support</span>
                Hubungi Kami
            </span>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-slate-900 dark:text-white">Konsultasi & Penjadwalan Diskusi</h1>
            <p class="text-sm text-slate-600 dark:text-slate-400 max-w-xl mx-auto leading-relaxed">
                Punya pertanyaan arsitektur atau kebutuhan sistem khusus? Kirimkan spesifikasi ringkas Anda dan engineer kami akan merespons dalam 1x24 jam kerja.
            </p>
        </div>
    </section>

    <!-- Main Contact Section -->
    <section class="py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Info Panel -->
            <div class="lg:col-span-5 space-y-6">
                <div class="space-y-2">
                    <span class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">Saluran Langsung</span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">Informasi Kontak Kantor</h2>
                    <p class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm leading-relaxed">
                        Kami siap mendengarkan kebutuhan spesifik Anda. Hubungi kami melalui formulir resmi atau kontak langsung berikut.
                    </p>
                </div>

                <div class="space-y-3">
                    <div class="flex items-start gap-3.5 bg-white dark:bg-[#111722] p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs">
                        <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[20px]">location_on</span>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Lokasi Operasional</h3>
                            <p class="text-xs font-semibold text-slate-900 dark:text-white mt-0.5">Karawang, Jawa Barat, Indonesia</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5 bg-white dark:bg-[#111722] p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs">
                        <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[20px]">mail</span>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Email Resmi</h3>
                            <p class="text-xs font-semibold text-amber-700 dark:text-amber-400 mt-0.5">hallo.flashdev@flash14.id</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5 bg-white dark:bg-[#111722] p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs">
                        <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[20px]">call</span>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">WhatsApp / Telepon</h3>
                            <p class="text-xs font-semibold text-amber-700 dark:text-amber-400 mt-0.5">+62 821-2861-6647</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="lg:col-span-7 bg-white dark:bg-[#111722] p-6 sm:p-8 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs">
                @if ($submitted)
                    <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/60 rounded-xl p-6 text-center space-y-2.5">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 flex items-center justify-center mx-auto">
                            <span class="material-symbols-outlined text-[22px]">check</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Pesan Berhasil Terkirim</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 max-w-md mx-auto">Terima kasih telah menghubungi FlashDev. Tim kami akan segera meninjau pesan Anda dan merespons via email atau WhatsApp.</p>
                        <div class="pt-2">
                            <button wire:click="$set('submitted', false)" class="text-xs text-amber-700 dark:text-amber-400 font-semibold underline cursor-pointer">Kirim Pesan Lain</button>
                        </div>
                    </div>
                @else
                    <form wire:submit="sendMessage" class="space-y-4 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider text-[11px] mb-1">Nama Lengkap *</label>
                                <input type="text" wire:model="name" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-[#161F2E] border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 text-xs focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none" placeholder="Nama Anda">
                                @error('name') <span class="text-[11px] text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider text-[11px] mb-1">Alamat Email *</label>
                                <input type="email" wire:model="email" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-[#161F2E] border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 text-xs focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none" placeholder="nama@perusahaan.com">
                                @error('email') <span class="text-[11px] text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider text-[11px] mb-1">Nomor Telepon / WA</label>
                                <input type="text" wire:model="phone" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-[#161F2E] border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 text-xs focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none" placeholder="081234567890">
                                @error('phone') <span class="text-[11px] text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider text-[11px] mb-1">Subjek Permintaan</label>
                                <input type="text" wire:model="subject" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-[#161F2E] border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 text-xs focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none" placeholder="Aplikasi Custom / Web Sistem">
                                @error('subject') <span class="text-[11px] text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider text-[11px] mb-1">Rincian Kebutuhan Proyek *</label>
                            <textarea wire:model="message" rows="5" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-[#161F2E] border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 text-xs focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none" placeholder="Uraikan alur bisnis, integrasi yang diinginkan, atau estimasi waktu pengerjaan..."></textarea>
                            @error('message') <span class="text-[11px] text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-3 rounded-lg bg-amber-600 hover:bg-amber-700 active:bg-amber-800 dark:bg-amber-500 dark:hover:bg-amber-400 text-white dark:text-slate-950 font-semibold text-xs shadow-xs transition-colors cursor-pointer">
                                Kirim Formulir Konsultasi
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </section>
</div>
