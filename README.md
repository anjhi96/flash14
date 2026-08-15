# ⚡ FlashDev Agency — Website & Software Development Agency

Website company profile dan admin management panel untuk **FlashDev Agency**, sebuah agensi pengembang software & website modern berbasis di Karawang, Indonesia.

---

## 🌐 Tentang Project

**FlashDev** adalah platform website agensi digital yang dibangun menggunakan stack teknologi modern. Website ini mencakup halaman publik yang informatif, panel admin berbasis Livewire, serta fitur **Dual Theme Switcher** (Light Mode & Dark Mode) yang dapat diubah langsung oleh pengunjung.

---

## ✨ Fitur Utama

- 🌗 **Dual Theme Switcher** — Light Mode (Tema B: Clean Premium) & Dark Mode (Tema A: Cyber Tech Obsidian) dengan penyimpanan preferensi via `localStorage`
- 🏠 **Halaman Publik Lengkap** — Beranda, Layanan, Portofolio, Tentang Kami, Kontak
- 🛠️ **Admin Control Panel** — Dashboard manajemen konten (Layanan, Portofolio, Tim, Pesan Masuk, Pengaturan Halaman)
- 📬 **Contact Form** — Form konsultasi dengan notifikasi pesan masuk di dashboard admin
- 🔐 **Autentikasi & Role-Based Access** — Role admin dan user terpisah
- 📱 **Fully Responsive** — Tampilan optimal di semua ukuran layar (Mobile, Tablet, Desktop)
- ⚡ **Performa Tinggi** — Livewire Volt untuk komponen reaktif tanpa full-page reload
- 🎨 **Premium Design** — Typography Inter, smooth transitions, glassmorphism effects, dan micro-animations

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|---|---|
| **Backend Framework** | Laravel 13 (PHP 8.3) |
| **Reactive Components** | Livewire 3 + Volt |
| **Frontend Styling** | Tailwind CSS v4 |
| **JavaScript** | Alpine.js |
| **Database** | MySQL (Production) / SQLite (Development) |
| **UI Component** | Livewire Flux |
| **Build Tool** | Vite |

---

## 📂 Struktur Halaman

```
/ (Beranda)          — Hero, Metrik, Tech Stack, Layanan, Portofolio, CTA
/services            — Daftar Layanan Lengkap
/portfolio           — Galeri Proyek dengan Filter Kategori
/about               — Profil Agensi, Core Values, Tim
/contact             — Form Konsultasi Gratis
/dashboard           — Dashboard User
/admin/dashboard     — Admin Control Panel (khusus role admin)
```

---

## 🚀 Cara Instalasi Lokal

### Prasyarat
- PHP ^8.3
- Composer
- Node.js & NPM
- MySQL / SQLite

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/username/flash14.git
cd flash14

# 2. Install dependensi PHP
composer install

# 3. Install dependensi Node.js
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate Application Key
php artisan key:generate

# 6. Konfigurasi database di file .env
# DB_CONNECTION=mysql
# DB_DATABASE=flash14
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Migrasi database & isi data default
php artisan migrate:fresh --seed

# 8. Link storage
php artisan storage:link

# 9. Build asset frontend
npm run build

# 10. Jalankan development server
php artisan serve
```

Buka di browser: `http://localhost:8000`

---

## 🌍 Panduan Deploy ke Hostinger (Production)

### 1. Setup Database MySQL di Hostinger hPanel
Buat database MySQL baru di menu **Databases** → **MySQL Databases**. Catat nama database, username, dan password.

### 2. Upload via GitHub Integration
- Buka **hPanel** → **Advanced** → **Git**
- Hubungkan repository GitHub, pilih **Branch: `main`**, **Directory: `public_html`**

### 3. Buat File `.env` di Server (via File Manager / Terminal)
```ini
APP_NAME="FlashDev Agency"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://domain-anda.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_db_hostinger
DB_USERNAME=user_db_hostinger
DB_PASSWORD=password_db_hostinger

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 4. Jalankan Setup via Terminal SSH Hostinger
```bash
cd public_html
php artisan key:generate
php artisan migrate:fresh --seed --force
rm -rf public/storage && ln -s ../storage/app/public public/storage
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔐 Akun Admin Default

Akun admin akan otomatis dibuat saat menjalankan `php artisan migrate --seed`.

| Field | Value |
|---|---|
| **Email** | `hallo.flashdev@flash14.id` |
| **Password** | *(diset saat seeder)* |
| **Role** | `admin` |

---

## 📄 Lisensi

Project ini dibuat khusus untuk kebutuhan internal **FlashDev Agency**. Seluruh hak cipta dilindungi.

---

<p align="center">
  Made with ⚡ by <strong>FlashDev Agency</strong> — Karawang, Indonesia
</p>
