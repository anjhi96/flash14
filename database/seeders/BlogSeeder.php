<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Web Architecture', 'slug' => 'web-architecture', 'description' => 'Arsitektur sistem, backend, dan praktik rekayasa perangkat lunak.', 'color' => 'primary'],
            ['name' => 'UI/UX & Design Systems', 'slug' => 'ui-ux-design-systems', 'description' => 'Prinsip desain antarmuka, design token, dan pengalaman pengguna.', 'color' => 'tertiary'],
            ['name' => 'AI & Modern Tech', 'slug' => 'ai-modern-tech', 'description' => 'Tren teknologi terkini, otomasi, dan keamanan aplikasi modern.', 'color' => 'secondary'],
            ['name' => 'Case Studies', 'slug' => 'case-studies', 'description' => 'Studi kasus proyek nyata bersama klien FlashDev.', 'color' => 'primary'],
        ];

        foreach ($categories as $c) {
            Category::updateOrCreate(['slug' => $c['slug']], $c);
        }

        $tags = ['Laravel', 'Livewire', 'Tailwind', 'Docker', 'Cybersecurity'];

        foreach ($tags as $name) {
            Tag::updateOrCreate(['slug' => str($name)->slug()], ['name' => $name, 'slug' => str($name)->slug()]);
        }

        $rian = TeamMember::firstWhere('name', 'Rian Hidayat');
        $siti = TeamMember::firstWhere('name', 'Siti Rahmawati');
        $budi = TeamMember::firstWhere('name', 'Budi Santoso');
        $nadia = TeamMember::firstWhere('name', 'Nadia Putri');

        $webArch = Category::where('slug', 'web-architecture')->first();
        $uiUx = Category::where('slug', 'ui-ux-design-systems')->first();
        $aiTech = Category::where('slug', 'ai-modern-tech')->first();
        $caseStudy = Category::where('slug', 'case-studies')->first();

        $posts = [
            [
                'author_id' => $rian?->id,
                'category_id' => $webArch?->id,
                'title' => 'Membangun Arsitektur Microservices dengan Laravel & Docker',
                'slug' => 'membangun-arsitektur-microservices-laravel-docker',
                'excerpt' => 'Panduan praktis memecah aplikasi monolitik menjadi layanan-layanan kecil yang independen menggunakan Laravel dan Docker Compose.',
                'body' => <<<'MD'
Ketika lalu lintas aplikasi mulai meningkat pesat, arsitektur monolitik yang tadinya cukup memadai bisa berubah menjadi *bottleneck* performa sekaligus mempersulit proses deployment tim.

## Mengapa Microservices?

Microservices memungkinkan setiap tim mengembangkan, menguji, dan men-deploy layanannya secara independen tanpa saling memblokir satu sama lain.

### Keuntungan Utama

- Skalabilitas granular per layanan, bukan seluruh aplikasi sekaligus
- Isolasi kegagalan (*fault isolation*) — satu servis down tidak menjatuhkan semuanya
- Tim dapat bekerja paralel dengan batas tanggung jawab yang jelas

## Menyiapkan Environment dengan Docker

Docker Compose sangat membantu mensimulasikan beberapa service sekaligus di lingkungan lokal sebelum naik ke produksi.

```yaml
version: '3.8'
services:
  app:
    build: .
    ports:
      - "8000:8000"
    depends_on:
      - db
  db:
    image: mysql:8
    environment:
      MYSQL_ROOT_PASSWORD: secret
```

### Komunikasi Antar Layanan

Gunakan REST atau message queue (Laravel Horizon + Redis) tergantung apakah komunikasi butuh respons langsung atau bisa asinkron.

> "Arsitektur yang baik bukan tentang menghindari kompleksitas, tapi mengelola kompleksitas di tempat yang tepat."

## Perbandingan Monolith vs Microservices

| Aspek | Monolith | Microservices |
|---|---|---|
| Deployment | Satu unit | Independen per servis |
| Skalabilitas | Vertikal | Horizontal per servis |
| Kompleksitas Operasional | Rendah | Tinggi |

## Kesimpulan

Microservices bukan solusi untuk semua kasus — untuk tim kecil atau produk tahap awal, monolitik yang terstruktur rapi seringkali lebih masuk akal. Pertimbangkan migrasi bertahap begitu batas skalabilitas mulai terasa.
MD,
                'cover_image' => 'https://images.unsplash.com/photo-1667372393119-3d4c48d07fc9?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'published_at' => now()->subDays(12),
                'views_count' => 482,
                'likes_count' => 63,
                'meta_title' => 'Arsitektur Microservices dengan Laravel & Docker | FlashDev',
                'meta_description' => 'Panduan praktis memecah aplikasi monolitik menjadi microservices menggunakan Laravel dan Docker Compose.',
                'is_featured' => true,
                'tags' => ['Laravel', 'Docker'],
            ],
            [
                'author_id' => $siti?->id,
                'category_id' => $uiUx?->id,
                'title' => 'Prinsip Design System Modern: Dari Tailwind ke Material Design 3',
                'slug' => 'prinsip-design-system-modern-tailwind-material-design-3',
                'excerpt' => 'Bagaimana menyusun token warna, tipografi, dan komponen yang konsisten tanpa terasa seperti template generik.',
                'body' => <<<'MD'
Design system yang baik bukan soal jumlah komponen, tapi soal konsistensi peran (*role*) tiap elemen visual dalam sistem.

## Mulai dari Token, Bukan Komponen

Sebelum membangun tombol atau kartu, tentukan dulu token warna dan tipografi: warna primer, warna permukaan (*surface*), dan warna teks pendukung.

### Kesalahan Umum

1. Menyalin palet warna dari template lain tanpa menyesuaikan identitas brand
2. Menggunakan warna status (merah/hijau) untuk dekorasi, bukan makna
3. Radius sudut yang berbeda-beda di tiap komponen tanpa skala yang jelas

## Menerapkan dengan Tailwind CSS v4

Tailwind v4 memudahkan definisi token lewat `@theme`, sehingga satu sumber kebenaran warna bisa dipakai ulang di seluruh aplikasi.

```css
@theme {
  --color-primary: #B45309;
  --color-surface: #F8FAFC;
}
```

> Material Design 3 adalah fondasi prinsip, bukan cetakan visual yang harus ditiru mentah-mentah.

## Kesimpulan

Sistem desain yang matang lahir dari disiplin menjaga hierarki dan proporsi — bukan dari jumlah efek visual yang ditumpuk.
MD,
                'cover_image' => 'https://images.unsplash.com/photo-1541462608143-67571c6738dd?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'views_count' => 315,
                'likes_count' => 41,
                'meta_title' => 'Prinsip Design System Modern | FlashDev',
                'meta_description' => 'Panduan menyusun design token dan komponen yang konsisten dengan prinsip Material Design 3.',
                'is_featured' => false,
                'tags' => ['Tailwind'],
            ],
            [
                'author_id' => $rian?->id,
                'category_id' => $webArch?->id,
                'title' => 'Livewire 3 + Alpine.js: Reaktivitas Tanpa Build Step JavaScript',
                'slug' => 'livewire-3-alpine-js-reaktivitas-tanpa-build-step',
                'excerpt' => 'Menyusun interaksi UI yang reaktif di Laravel tanpa perlu menulis satu baris pun kode Vue atau React.',
                'body' => <<<'MD'
Livewire memungkinkan komponen PHP merender ulang bagian halaman secara reaktif, sementara Alpine.js menangani interaksi kecil di sisi klien tanpa perlu build step JavaScript terpisah.

## Kapan Pakai Livewire, Kapan Pakai Alpine?

Aturan sederhana: gunakan Livewire untuk apa pun yang butuh data dari server (query database, validasi, penyimpanan), gunakan Alpine untuk interaksi murni UI (toggle, dropdown, animasi).

### Contoh Kombinasi

```php
public function toggleActive(int $id): void
{
    $item = Item::findOrFail($id);
    $item->update(['is_active' => ! $item->is_active]);
}
```

Di sisi Blade, `x-data` Alpine bisa menangani state sementara seperti modal terbuka/tertutup tanpa round-trip ke server.

## Kesimpulan

Kombinasi ini membuat aplikasi terasa modern dan reaktif tanpa kompleksitas toolchain SPA penuh.
MD,
                'cover_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'views_count' => 198,
                'likes_count' => 27,
                'meta_title' => 'Livewire 3 + Alpine.js | FlashDev',
                'meta_description' => 'Menyusun interaksi UI reaktif di Laravel dengan Livewire dan Alpine.js tanpa build step JavaScript.',
                'is_featured' => false,
                'tags' => ['Laravel', 'Livewire'],
            ],
            [
                'author_id' => $budi?->id,
                'category_id' => $aiTech?->id,
                'title' => 'Checklist Keamanan Aplikasi Web untuk Startup',
                'slug' => 'checklist-keamanan-aplikasi-web-untuk-startup',
                'excerpt' => 'Langkah-langkah dasar namun sering terlewat untuk mengamankan aplikasi web sebelum rilis ke publik.',
                'body' => <<<'MD'
Banyak insiden keamanan terjadi bukan karena serangan canggih, tapi karena konfigurasi dasar yang terlewat.

## Checklist Sebelum Rilis

### Level Aplikasi

- Validasi & sanitasi semua input pengguna
- Terapkan *rate limiting* pada endpoint autentikasi
- Simpan kredensial di environment variable, bukan kode sumber

### Level Infrastruktur

- Aktifkan HTTPS dengan sertifikat yang valid
- Batasi akses database hanya dari jaringan internal
- Rutin memperbarui dependensi (`composer audit`, `npm audit`)

```bash
composer audit
php artisan config:cache
```

> Keamanan bukan fitur yang ditambahkan di akhir proyek — ia harus menjadi bagian dari setiap tahap pengembangan.

## Kesimpulan

Startup dengan sumber daya terbatas tetap bisa mencapai standar keamanan yang layak dengan disiplin menjalankan checklist dasar ini secara konsisten.
MD,
                'cover_image' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'published_at' => now()->subDays(20),
                'views_count' => 540,
                'likes_count' => 88,
                'meta_title' => 'Checklist Keamanan Aplikasi Web | FlashDev',
                'meta_description' => 'Langkah-langkah dasar mengamankan aplikasi web startup sebelum rilis ke publik.',
                'is_featured' => true,
                'tags' => ['Cybersecurity', 'Docker'],
            ],
            [
                'author_id' => $nadia?->id,
                'category_id' => $caseStudy?->id,
                'title' => 'Studi Kasus: Migrasi Sistem Legacy ke Cloud untuk Klien Manufaktur',
                'slug' => 'studi-kasus-migrasi-sistem-legacy-ke-cloud-manufaktur',
                'excerpt' => 'Catatan proses migrasi sistem inventaris berbasis desktop ke platform cloud untuk klien manufaktur skala menengah.',
                'body' => <<<'MD'
Artikel ini masih dalam proses penyusunan draf berdasarkan wawancara tim proyek dan belum dipublikasikan ke publik.

## Latar Belakang Proyek

Klien menggunakan sistem desktop lama yang sulit diakses dari luar kantor dan rawan kehilangan data saat perangkat rusak.

### Tantangan Migrasi

- Data historis 8 tahun yang perlu dibersihkan sebelum migrasi
- Downtime operasional harus diminimalkan selama masa transisi

## Rencana Selanjutnya

Draf ini akan dilengkapi dengan hasil akhir dan metrik performa setelah proyek selesai.
MD,
                'cover_image' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1200&q=80',
                'status' => 'draft',
                'published_at' => null,
                'views_count' => 0,
                'likes_count' => 0,
                'meta_title' => null,
                'meta_description' => null,
                'is_featured' => false,
                'tags' => [],
            ],
        ];

        foreach ($posts as $data) {
            $tagNames = $data['tags'];
            unset($data['tags']);

            $data['reading_time'] = Post::calculateReadingTime($data['body']);

            $post = Post::updateOrCreate(['slug' => $data['slug']], $data);

            if ($tagNames !== []) {
                $tagIds = Tag::whereIn('slug', array_map(fn ($t) => str($t)->slug(), $tagNames))->pluck('id');
                $post->tags()->sync($tagIds);
            }
        }
    }
}
