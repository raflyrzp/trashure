# Trashure — Aplikasi Pelaporan Sampah (Laravel + SQLite + TailwindCSS)

Trashure adalah aplikasi web untuk pelaporan lokasi dan jenis sampah oleh masyarakat, disertai sistem poin, leaderboard, dan materi edukasi. Aplikasi dibangun dengan Laravel, database SQLite, dan antarmuka modern berbasis TailwindCSS.

Catatan penting:

-   Seluruh penamaan kode (model, tabel, kolom, variabel) menggunakan Bahasa Inggris.
-   Seluruh pesan yang tampil ke pengguna di antarmuka (flash message, label, validasi, dsb.) menggunakan Bahasa Indonesia.

---

## Fitur Utama

-   Dashboard ringkasan laporan, poin, dan aktivitas terbaru.
-   Tambah Laporan Sampah (unggah foto, isi deskripsi, lokasi, jenis sampah).
-   Riwayat Laporan (filter per pengguna, admin melihat semua).
-   Leaderboard poin pengguna.
-   Panduan & Edukasi pemilahan sampah.
-   Profil pengguna (ubah data diri dan kata sandi).
-   Panel Admin untuk memverifikasi/menolak/menyelesaikan laporan.
-   Sistem Poin:
    -   Poin diberikan saat status laporan menjadi “verified” atau “resolved”.
    -   Poin dicabut jika status kembali ke “pending” atau “rejected”.
    -   Satu riwayat poin per laporan (unik).

---

## Arsitektur & Teknologi

-   Backend: Laravel 12
-   Database: SQLite
-   Frontend: Blade Templates + TailwindCSS + Vite

---

## Skema Data

Tabel inti:

-   `users` (citizen/admin) — kolom utama: `name`, `email`, `password`, `phone_number`, `role`, `total_points`
-   `reports` — kolom utama: `reporter_id`, `admin_id`, `waste_type_id`, `description`, `location`, `photo_url`, `status` (pending|verified|rejected|resolved), `reported_at`, `verified_at`, `admin_notes`
-   `point_histories` — kolom utama: `report_id` (unik), `user_id`, `points`, `granted_at`
-   `waste_types` — master data jenis sampah: `name`, `description`
-   `educations` — konten panduan/edukasi: `title`, `slug`, `content`, `category`, `published_at`

Relasi:

-   User (reporter) 1—\* Reports
-   User (admin) 1—\* Reports (melalui `admin_id`)
-   WasteType 1—\* Reports
-   Report 1—1 PointHistory (unik per laporan)
-   User 1—\* PointHistories

---

## Prasyarat

-   PHP 8.1+ dan Composer
-   Node.js 18+ dan npm 8+
-   Git (opsional)

---

## Instalasi & Setup

1. Clone repo dan masuk direktori

```bash
git clone https://github.com/raflyrzp/trashure.git
cd trashure
```

2. Instal dependensi

```bash
composer install
npm install
```

3. Salin env dan generate key

```bash
cp .env.example .env
php artisan key:generate
```

4. Konfigurasi database SQLite

-   Edit `.env`:

```
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

-   Buat file database:

```bash
mkdir -p database
touch database/database.sqlite
```

5. Migrasi database

```bash
php artisan migrate
```

6. Link storage publik (untuk akses foto laporan)

```bash
php artisan storage:link
```

7. Jalankan aplikasi (dua terminal)

```bash
php artisan serve
```

Buka http://127.0.0.1:8000

---

## Alur Penggunaan

Peran Pengguna:

-   citizen: pengguna umum, hanya melihat & mengelola laporannya sendiri.
-   admin: dapat melihat semua laporan dan mengubah status.

1. Registrasi/Login

-   Buat akun melalui halaman auth (Breeze atau sistem Anda).
-   Secara default peran adalah `citizen`. Untuk menjadikan admin:
    -   Ubah kolom `role` pengguna menjadi `admin` (via Tinker/DB):
    ```bash
    php artisan tinker
    >>> \App\Models\User::where('email','admin@contoh.com')->update(['role' => 'admin']);
    ```

2. Dashboard

-   Melihat ringkasan Total Laporan, Total Poin, Laporan terbaru, dan cuplikan leaderboard.

3. Tambah Laporan

-   Masuk ke “Tambah Laporan”.
-   Isi “Deskripsi”, “Lokasi”, (opsional) “Jenis Sampah”, unggah “Foto”.
-   Setelah dikirim, status awal: “pending”.

4. Verifikasi/Admin

-   Admin membuka detail laporan, mengubah status:
    -   “verified” atau “resolved” → Poin diberikan (default 10).
    -   “pending” atau “rejected” → Poin dicabut bila sebelumnya sudah diberikan.

5. Riwayat Laporan

-   Citizen melihat daftar laporannya sendiri.
-   Admin melihat semua laporan.

6. Leaderboard

-   Menampilkan peringkat berdasarkan `total_points`.

7. Panduan & Edukasi

-   Daftar materi edukasi, serta halaman detail.

8. Profil

-   Lihat profil, ubah data (nama, email, telepon, kata sandi).

Seluruh pesan UI ke pengguna menggunakan Bahasa Indonesia (misalnya notifikasi “Laporan berhasil dikirim”, “Status laporan berhasil diperbarui”, dst).

---

## Rute (Ringkas)

Web routes (semua di belakang middleware `auth`, beberapa di belakang `admin`):

-   Dashboard
    -   GET `/` → DashboardController@index
-   Reports (citizen/admin)
    -   GET `/reports` → ReportController@index
    -   GET `/reports/create` → ReportController@create
    -   POST `/reports` → ReportController@store
    -   GET `/reports/{report}` → ReportController@show
    -   DELETE `/reports/{report}` → ReportController@destroy (hanya pemilik & status pending)
-   Admin
    -   POST `/reports/{report}/status` → ReportController@updateStatus (middleware `admin`)
-   Leaderboard
    -   GET `/leaderboard` → LeaderboardController@index
-   Education
    -   GET `/education` → EducationController@index
    -   GET `/education/{slug}` → EducationController@show
-   Profile
    -   GET `/profile` → ProfileController@show
    -   GET `/profile/edit` → ProfileController@edit
    -   PUT `/profile` → ProfileController@update

---

## Spesifikasi Teknis

Model utama:

-   `User`:
    -   Fields: `name`, `email`, `password`, `phone_number`, `role`, `total_points`
    -   Relasi: `reportedReports()`, `verifiedReports()`, `pointHistories()`
-   `Report`:
    -   Fields: `reporter_id`, `admin_id`, `waste_type_id`, `description`, `location`, `photo_url`, `status`, `reported_at`, `verified_at`, `admin_notes`
    -   Status: `pending|verified|rejected|resolved`
    -   Relasi: `reporter()`, `admin()`, `wasteType()`, `pointHistory()`
-   `PointHistory`:
    -   Fields: `report_id` (unik), `user_id`, `points`, `granted_at`
    -   Relasi: `report()`, `user()`
-   `WasteType`:
    -   Fields: `name`, `description`
    -   Relasi: `reports()`
-   `Education`:
    -   Fields: `title`, `slug`, `content`, `category`, `published_at`

Poin:

-   Default 10 poin per laporan saat berpindah ke `verified` atau `resolved`.
-   Dicabut jika kembali ke `pending` atau `rejected`.
-   Implementasi berada di `ReportController@updateStatus` (variabel `$pointsPerReport`).

Upload foto:

-   Disimpan pada disk `public` (storage/app/public).
-   URL di view menggunakan `asset('storage/'.$report->photo_url)`.
-   Wajib menjalankan `php artisan storage:link`.

Validasi:

-   Pesan kesalahan form ditampilkan dalam Bahasa Indonesia (contoh di controllers).
-   Untuk lokalisasi validasi penuh, pertimbangkan paket komunitas seperti `laravel-lang`.

Keamanan:

-   Akses admin dilindungi middleware `EnsureAdmin`.
-   Akses laporan dibatasi: pengguna hanya boleh melihat/menghapus laporan miliknya, dan menghapus hanya jika status `pending`.

---

## Seed Data (Opsional)

Jenis Sampah (contoh cepat via Tinker):

```bash
php artisan tinker
>>> \App\Models\WasteType::insert([
... ['name' => 'Plastik', 'description' => 'Kemasan plastik, botol, dsb.'],
... ['name' => 'Organik', 'description' => 'Sisa makanan, daun, dsb.'],
... ['name' => 'Kertas', 'description' => 'Koran, kardus, dsb.'],
... ['name' => 'Kaca', 'description' => 'Botol kaca, pecahan kaca'],
... ['name' => 'Logam', 'description' => 'Kaleng, besi, alumunium'],
... ]);
```

Konten Edukasi (contoh cepat):

```bash
php artisan tinker
>>> \App\Models\Education::create([
... 'title' => 'Cara Memilah Sampah Rumah Tangga',
... 'slug' => 'cara-memilah-sampah-rumah-tangga',
... 'category' => 'Pemilahan',
... 'content' => '<p>Pisahkan sampah organik dan anorganik...</p>',
... 'published_at' => now(),
... ]);
```

---

## Build & Deploy

-   Build assets produksi:

```bash
npm run build
```

-   Pastikan konfigurasi `.env` produksi:
    -   `APP_ENV=production`
    -   `APP_DEBUG=false`
    -   `DB_CONNECTION=sqlite` (atau ganti sesuai DB produksi)
    -   Jalankan `php artisan migrate --force`
    -   Jalankan `php artisan storage:link` (sekali saja)

---

## Troubleshooting

-   Foto tidak tampil:
    -   Pastikan `php artisan storage:link` sudah dijalankan.
    -   Periksa path: harus `asset('storage/...')`.
-   Migrasi gagal di SQLite:
    -   Pastikan file `database/database.sqlite` ada dan writable.
-   Tailwind tidak memproses gaya:
    -   Pastikan `tailwind.config.js` `content` mencakup path Blade: `./resources/views/**/*.blade.php`.
    -   Jalankan ulang `npm run dev`.
-   Tidak ada halaman login:
    -   Pasang Breeze atau scaffolding auth lain.

---

## Kontribusi

-   Gunakan branch feature, ajukan Pull Request, sertakan deskripsi perubahan.
-   Jalankan `php artisan test` (jika tersedia) sebelum mengajukan PR.

Happy Coding y'all!!!
