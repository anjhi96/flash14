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

<div class="bg-surface text-on-surface min-h-screen transition-colors duration-200">
    <x-slot name="title">Konsultasi Proyek & Kontak - FlashDev</x-slot>
    <x-slot name="description">Hubungi tim FlashDev untuk mendapatkan konsultasi teknis dan estimasi penawaran harga pembuatan website atau aplikasi enterprise.</x-slot>

    <!-- Header Banner -->
    <section class="py-14 border-b border-outline-variant bg-surface-container-lowest">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl text-center space-y-3">
            <x-badge variant="primary">Hubungi Kami</x-badge>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-on-surface">Konsultasi & Penjadwalan Diskusi</h1>
            <p class="text-sm text-on-surface-variant max-w-xl mx-auto leading-relaxed">
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
                    <span class="text-xs font-bold text-primary uppercase tracking-wider">Saluran Langsung</span>
                    <h2 class="text-xl sm:text-2xl font-bold text-on-surface">Informasi Kontak Kantor</h2>
                    <p class="text-on-surface-variant text-xs sm:text-sm leading-relaxed">
                        Kami siap mendengarkan kebutuhan spesifik Anda. Hubungi kami melalui formulir resmi atau kontak langsung berikut.
                    </p>
                </div>

                <div class="space-y-3">
                    <div class="flex items-start gap-3.5 bg-surface-container-lowest p-4 rounded-xl border border-outline-variant">
                        <span class="material-symbols-outlined text-[20px] text-primary shrink-0">location_on</span>
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Lokasi Operasional</h3>
                            <p class="text-xs font-semibold text-on-surface mt-0.5">Karawang, Jawa Barat, Indonesia</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5 bg-surface-container-lowest p-4 rounded-xl border border-outline-variant">
                        <span class="material-symbols-outlined text-[20px] text-primary shrink-0">mail</span>
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Email Resmi</h3>
                            <p class="text-xs font-semibold text-primary mt-0.5">hallo.flashdev@flash14.id</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5 bg-surface-container-lowest p-4 rounded-xl border border-outline-variant">
                        <span class="material-symbols-outlined text-[20px] text-primary shrink-0">call</span>
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">WhatsApp / Telepon</h3>
                            <p class="text-xs font-semibold text-primary mt-0.5">+62 821-2861-6647</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="lg:col-span-7 bg-surface-container-lowest p-6 sm:p-8 rounded-xl border border-outline-variant">
                @if ($submitted)
                    <div class="bg-state-success-container border border-transparent rounded-xl p-6 text-center space-y-2.5">
                        <div class="w-10 h-10 rounded-full bg-state-success text-white flex items-center justify-center mx-auto">
                            <span class="material-symbols-outlined text-[22px]">check</span>
                        </div>
                        <h3 class="text-base font-bold text-state-on-success-container">Pesan Berhasil Terkirim</h3>
                        <p class="text-xs text-state-on-success-container max-w-md mx-auto">Terima kasih telah menghubungi FlashDev. Tim kami akan segera meninjau pesan Anda dan merespons via email atau WhatsApp.</p>
                        <div class="pt-2">
                            <button wire:click="$set('submitted', false)" class="text-xs text-primary font-semibold underline cursor-pointer">Kirim Pesan Lain</button>
                        </div>
                    </div>
                @else
                    <form wire:submit="sendMessage" class="space-y-4 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Nama Lengkap *" class="uppercase" />
                                <x-text-input type="text" wire:model="name" placeholder="Nama Anda" />
                                @error('name') <span class="text-[11px] text-state-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <x-input-label value="Alamat Email *" class="uppercase" />
                                <x-text-input type="email" wire:model="email" placeholder="nama@perusahaan.com" />
                                @error('email') <span class="text-[11px] text-state-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Nomor Telepon / WA" class="uppercase" />
                                <x-text-input type="text" wire:model="phone" placeholder="081234567890" />
                                @error('phone') <span class="text-[11px] text-state-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <x-input-label value="Subjek Permintaan" class="uppercase" />
                                <x-text-input type="text" wire:model="subject" placeholder="Aplikasi Custom / Web Sistem" />
                                @error('subject') <span class="text-[11px] text-state-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <x-input-label value="Rincian Kebutuhan Proyek *" class="uppercase" />
                            <x-textarea-input wire:model="message" :rows="5" placeholder="Uraikan alur bisnis, integrasi yang diinginkan, atau estimasi waktu pengerjaan..." />
                            @error('message') <span class="text-[11px] text-state-error font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-2">
                            <x-primary-button type="submit" class="w-full">
                                Kirim Formulir Konsultasi
                            </x-primary-button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </section>
</div>
