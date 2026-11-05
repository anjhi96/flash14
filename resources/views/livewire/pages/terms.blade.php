<?php

use Livewire\Volt\Component;

new class extends Component {}; ?>

<div class="bg-surface text-on-surface min-h-screen transition-colors duration-200">
    <x-slot name="title">Syarat & Ketentuan - FlashDev</x-slot>
    <x-slot name="description">Syarat dan ketentuan penggunaan layanan rekayasa perangkat lunak dan website FlashDev.</x-slot>

    <section class="py-14 border-b border-outline-variant bg-surface-container-lowest">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3 text-center sm:text-left">
            <x-badge variant="primary">Ketentuan Layanan</x-badge>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-on-surface">Syarat & Ketentuan</h1>
            <p class="text-sm text-on-surface-variant max-w-2xl leading-relaxed">
                Pedoman dan ketentuan operasional yang mengatur hubungan kerja sama antara FlashDev dan klien kami.
            </p>
        </div>
    </section>

    <section class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 text-xs sm:text-sm text-on-surface leading-relaxed">
            <div class="space-y-3 bg-surface-container-lowest p-6 rounded-xl border border-outline-variant">
                <h2 class="text-base sm:text-lg font-bold text-on-surface">1. Ruang Lingkup Layanan</h2>
                <p class="text-on-surface-variant">
                    FlashDev menyediakan layanan perancangan arsitektur perangkat lunak, pengembangan aplikasi web custom, sistem enterprise, dan pemeliharaan sistem. Spesifikasi teknis, batas waktu pengerjaan, dan tahapan rilis disepakati secara tertulis dalam dokumen kesepakatan kerja sama (Statement of Work) per proyek.
                </p>
            </div>

            <div class="space-y-3 bg-surface-container-lowest p-6 rounded-xl border border-outline-variant">
                <h2 class="text-base sm:text-lg font-bold text-on-surface">2. Hak Kekayaan Intelektual</h2>
                <p class="text-on-surface-variant">
                    Seluruh kode program khusus, aset desain, dan dokumentasi yang dibuat khusus untuk klien akan menjadi milik klien secara penuh setelah seluruh kewajiban pembayaran diselesaikan, kecuali pustaka kode sumber terbuka (open source) yang tunduk pada lisensi masing-masing.
                </p>
            </div>

            <div class="space-y-3 bg-surface-container-lowest p-6 rounded-xl border border-outline-variant">
                <h2 class="text-base sm:text-lg font-bold text-on-surface">3. Garansi & Pemeliharaan</h2>
                <p class="text-on-surface-variant">
                    Kami memberikan masa garansi perbaikan bug dan stabilitas sistem setelah serah terima proyek selesai. Layanan penambahan fitur baru di luar cakupan kesepakatan awal akan dihitung sebagai pengembangan terpisah.
                </p>
            </div>

            <div class="space-y-3 bg-surface-container-lowest p-6 rounded-xl border border-outline-variant">
                <h2 class="text-base sm:text-lg font-bold text-on-surface">4. Hubungi Kami</h2>
                <p class="text-on-surface-variant">
                    Untuk pertanyaan mengenai syarat dan ketentuan ini, hubungi kami di <a href="mailto:hallo.flashdev@flash14.id" class="text-primary font-semibold underline">hallo.flashdev@flash14.id</a> atau melalui formulir kontak resmi kami.
                </p>
            </div>
        </div>
    </section>
</div>
