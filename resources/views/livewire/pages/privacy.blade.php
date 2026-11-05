<?php

use Livewire\Volt\Component;

new class extends Component {}; ?>

<div class="bg-surface text-on-surface min-h-screen transition-colors duration-200">
    <x-slot name="title">Kebijakan Privasi - FlashDev</x-slot>
    <x-slot name="description">Kebijakan privasi FlashDev mengenai pengumpulan, perlindungan, dan pengelolaan data pengguna dan klien kami.</x-slot>

    <section class="py-14 border-b border-outline-variant bg-surface-container-lowest">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3 text-center sm:text-left">
            <x-badge variant="primary">Legal & Kepatuhan</x-badge>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-on-surface">Kebijakan Privasi</h1>
            <p class="text-sm text-on-surface-variant max-w-2xl leading-relaxed">
                Komitmen FlashDev dalam menjaga kerahasiaan dan keamanan data klien, pengunjung website, dan mitra bisnis kami.
            </p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 text-xs sm:text-sm text-on-surface leading-relaxed">
            <div class="space-y-3 bg-surface-container-lowest p-6 rounded-xl border border-outline-variant">
                <h2 class="text-base sm:text-lg font-bold text-on-surface">1. Pengumpulan Informasi</h2>
                <p class="text-on-surface-variant">
                    Kami hanya mengumpulkan informasi yang Anda berikan secara sukarela melalui formulir konsultasi, seperti nama lengkap, alamat email, nomor telepon, dan rincian kebutuhan proyek. Kami tidak mengumpulkan data pribadi tanpa persetujuan eksplisit dari Anda.
                </p>
            </div>

            <div class="space-y-3 bg-surface-container-lowest p-6 rounded-xl border border-outline-variant">
                <h2 class="text-base sm:text-lg font-bold text-on-surface">2. Penggunaan Data</h2>
                <p class="text-on-surface-variant">
                    Informasi yang kami kumpulkan semata-mata digunakan untuk:
                </p>
                <ul class="list-disc list-inside space-y-1 text-on-surface-variant">
                    <li>Merespons pertanyaan konsultasi teknis dan estimasi penawaran harga.</li>
                    <li>Menyediakan layanan rekayasa perangkat lunak dan dukungan teknis.</li>
                    <li>Mengirimkan informasi pembaruan terkait layanan atau proyek yang sedang berjalan.</li>
                </ul>
            </div>

            <div class="space-y-3 bg-surface-container-lowest p-6 rounded-xl border border-outline-variant">
                <h2 class="text-base sm:text-lg font-bold text-on-surface">3. Perlindungan & Keamanan Data</h2>
                <p class="text-on-surface-variant">
                    Kami menerapkan standar keamanan teknis dan organisasi yang ketat untuk melindungi data Anda dari akses yang tidak sah, pengubahan, pengungkapan, atau perusakan. Kami tidak pernah menjual atau menyewakan data Anda kepada pihak ketiga mana pun.
                </p>
            </div>

            <div class="space-y-3 bg-surface-container-lowest p-6 rounded-xl border border-outline-variant">
                <h2 class="text-base sm:text-lg font-bold text-on-surface">4. Kontak Privasi</h2>
                <p class="text-on-surface-variant">
                    Jika Anda memiliki pertanyaan mengenai kebijakan privasi ini atau ingin memperbarui data yang Anda kirimkan, silakan hubungi tim kami melalui email di <a href="mailto:hallo.flashdev@flash14.id" class="text-primary font-semibold underline">hallo.flashdev@flash14.id</a>.
                </p>
            </div>
        </div>
    </section>
</div>
