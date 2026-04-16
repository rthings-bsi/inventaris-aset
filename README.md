# Basecamp Aset (Inventaris Cerdas)

Sistem manajemen inventaris aset berbasis Laravel 12 untuk monitoring, peminjaman, dan pelaporan aset secara terpusat.

## Fitur Utama
- **Dashboard Statistik**: Visualisasi kondisi dan kategori aset menggunakan Chart.js.
- **Manajemen Aset (CRUD)**: Pengelolaan data aset lengkap dengan foto, kategori, dan lokasi.
- **Sistem Peminjaman (Loans)**: Alur pengajuan peminjaman oleh staf dan persetujuan oleh administrator.
- **Ekspor & Impor**: Dukungan laporan dalam format PDF dan Excel, serta fitur impor massal.
- **Log Keamanan**: Pencatatan aktivitas sistem menggunakan Spatie Activity Log.
- **Izin Berbasis Peran**: Sistem permission terpusat menggunakan middleware.

## Teknologi
- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Tailwind CSS, RemixIcon
- **Database**: SQLite / MySQL
- **Testing**: PHPUnit

## Standar Kode (Refactored)
Proyek ini telah melalui proses overhaul untuk memenuhi standar Senior Web Developer:
- **Single Responsibility Principle (SRP)**: Controller dipisah berdasarkan fungsinya (Dashboard, Export, Loans, CRUD).
- **Security Check Middleware**: Menggunakan middleware `permission` untuk proteksi route yang konsisten.
- **FormRequests**: Validasi input dipisahkan ke dalam class request khusus.
- **Automated Testing**: Tersedia test suite untuk Auth, Asset, dan Loans.

## Cara Instalasi
1. Clone repository.
2. Jalankan `composer install`.
3. Salin `.env.example` ke `.env` dan sesuaikan konfigurasi.
4. Jalankan `php artisan key:generate`.
5. Jalankan `php artisan migrate --seed`.
6. Jalankan `php artisan serve`.

## Menjalankan Test
Gunakan perintah berikut untuk menjalankan pengujian otomatis:
```bash
php artisan test
```
