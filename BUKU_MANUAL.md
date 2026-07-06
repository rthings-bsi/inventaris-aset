# BUKU MANUAL PENGGUNA
## Sistem Informasi Inventaris Aset (AsetKu Enterprise)

**Versi Dokumen:** 1.0
**Tanggal:** Mei 2026
**Platform:** Web-Based (Laravel 12)

---

## DAFTAR ISI

1. [Pendahuluan](#1-pendahuluan)
2. [Persyaratan Sistem & Instalasi](#2-persyaratan-sistem--instalasi)
3. [Autentikasi & Manajemen Akun](#3-autentikasi--manajemen-akun)
4. [Dashboard](#4-dashboard)
5. [Manajemen Aset](#5-manajemen-aset)
6. [Peminjaman Aset](#6-peminjaman-aset)
7. [Data Master](#7-data-master)
8. [Manajemen Pengguna & Role](#8-manajemen-pengguna--role)
9. [Audit Aset](#9-audit-aset)
10. [Pengaturan Hak Akses](#10-pengaturan-hak-akses-role-permissions)
11. [Laporan & Ekspor](#11-laporan--ekspor)
12. [Notifikasi](#12-notifikasi)
13. [Pemecahan Masalah](#13-pemecahan-masalah)

---

## 1. PENDAHULUAN

### 1.1 Tentang Sistem

**AsetKu Enterprise** adalah Sistem Informasi Manajemen Inventaris Aset berbasis web yang dikembangkan menggunakan Laravel 12. Sistem ini dirancang untuk membantu organisasi dalam memonitoring, mengelola peminjaman, dan pelaporan aset secara terpusat, transparan, dan efisien.

### 1.2 Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
| **Dashboard Statistik** | Visualisasi kondisi aset dan kategori menggunakan Chart.js |
| **Manajemen Aset (CRUD)** | Pengelolaan data aset lengkap dengan foto, kategori, lokasi, dan kode aset |
| **Sistem Peminjaman** | Alur pengajuan peminjaman oleh staf dan persetujuan oleh administrator |
| **Ekspor & Impor** | Laporan PDF dan Excel, impor massal dari file Excel |
| **Audit Aset** | Sesi audit dengan pemindaian barcode/QR Code untuk validasi fisik |
| **Log Keamanan** | Pencatatan aktivitas sistem menggunakan Spatie Activity Log |
| **Izin Berbasis Peran** | Sistem permission terpusat menggunakan middleware |

### 1.3 Teknologi yang Digunakan

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Tailwind CSS, Font Awesome, RemixIcon, Chart.js
- **Database:** SQLite (default) / MySQL (opsional)
- **Library Pendukung:**
  - barryvdh/laravel-dompdf — Generate PDF
  - maatwebsite/excel — Import/Export Excel
  - simplesoftwareio/simple-qrcode — Generate QR Code
  - spatie/laravel-activitylog — Activity Logging

### 1.4 Struktur Role Pengguna

| Role | Slug | Deskripsi |
|------|------|-----------|
| **Administrator** | `admin` | Akses penuh ke seluruh sistem, termasuk manajemen user, role, dan permission |
| **Staff** | `staff` | Akses terbatas untuk operasional harian (lihat aset, lihat & ajukan peminjaman) |

---

## 2. PERSYARATAN SISTEM & INSTALASI

### 2.1 Persyaratan Server

- PHP 8.2 atau lebih baru
- Composer 2.x
- Node.js 18+ & NPM (untuk frontend)
- Ekstensi PHP: `BCMath`, `Ctype`, `Fileinfo`, `JSON`, `Mbstring`, `OpenSSL`, `PDO`, `Tokenizer`, `XML`, `GD` (untuk QR Code)

### 2.2 Langkah Instalasi

#### A. Clone Repository

```bash
git clone <repository-url>
cd inventaris_aset
```

#### B. Install Dependencies

```bash
composer install
npm install && npm run build
```

#### C. Konfigurasi Environment

```bash
copy .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi database serta pengaturan lainnya:

```env
APP_NAME=AsetKu Enterprise
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# atau untuk MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=inventaris_aset
# DB_USERNAME=root
# DB_PASSWORD=
```

#### D. Generate Key & Migrate

```bash
php artisan key:generate
php artisan migrate --seed
```

#### E. Jalankan Aplikasi

```bash
php artisan serve
```

Akses aplikasi di `http://localhost:8000`

#### F. Menjalankan Development Mode (Full Stack)

```bash
composer run dev
```

Perintah ini akan menjalankan secara bersamaan:
- `php artisan serve` — Web server
- `php artisan queue:listen` — Queue worker untuk notifikasi
- `php artisan pail` — Log viewer
- `npm run dev` — Vite hot-reload

### 2.3 Akun Default

Setelah menjalankan `php artisan migrate --seed`, tersedia dua role dengan permission default:

| Role | Permissions |
|------|-------------|
| **Admin** | Semua permission (`dashboard.view`, `asset.*`, `loan.*`, `master-data.*`, `user.manage`, `report.export`) |
| **Staff** | `dashboard.view`, `asset.view`, `loan.view`, `loan.create` |

> **Catatan:** Password default untuk user baru yang dibuat melalui menu Manajemen User adalah `password`.

### 2.4 Menjalankan Test

```bash
php artisan test
```

---

## 3. AUTENTIKASI & MANAJEMEN AKUN

### 3.1 Halaman Login

Akses halaman login melalui `/login` atau klik tombol **Otorisasi Akses** pada sidebar.

**Langkah Login:**
1. Masukkan alamat **Email** yang terdaftar
2. Masukkan **Password**
3. Centang **Remember Me** (opsional) untuk tetap login
4. Klik tombol **Login**

![Login Page](https://via.placeholder.com/600x400?text=Halaman+Login)

### 3.2 Logout

Klik tombol **Log Out** (ikon <i class="fas fa-sign-out-alt"></i>) di bagian bawah sidebar, atau tombol **Akhiri Sesi Akses** pada menu mobile.

### 3.3 Session

- Session akan berakhir setelah **120 menit** tidak ada aktivitas (dapat diubah di `.env` melalui `SESSION_LIFETIME`)
- Session disimpan di database (gunakan `php artisan session:table` jika belum ada)

---

## 4. DASHBOARD

Halaman dashboard (`/`) adalah halaman utama setelah login yang menampilkan ringkasan kondisi inventaris secara real-time.

### 4.1 Komponen Dashboard

#### Kartu Statistik (5 Kartu)
1. **Total Aset** — Jumlah seluruh aset yang tercatat
2. **Aset Aktif** — Jumlah aset dengan status `active`
3. **Dalam Perawatan (Maintenance)** — Jumlah aset dengan status `maintenance`
4. **Rusak (Broken)** — Jumlah aset dengan status `broken`
5. **Total Nilai Kapital** — Total nilai perolehan seluruh aset (Rp)

Setiap kartu statistik dapat diklik untuk langsung menuju halaman daftar aset dengan filter status terkait.

#### Tabel Inventaris Terbaru
Menampilkan 5 aset terakhir yang dicatat, lengkap dengan:
- Nama dan foto aset
- Kode aset
- Kategori
- Lokasi / departemen
- Status
- Nilai perolehan

#### Grafik Distribusi Kategori (Donut Chart)
Visualisasi pie/donut yang menampilkan persentase aset per kategori.

#### Grafik Mutu Fisik (Bar Chart)
Diagram batang yang menampilkan distribusi kondisi aset (Baik Sekali, Baik, Cukup, Rusak, dll).

### 4.2 Permission yang Diperlukan

- `dashboard.view` — Semua pengguna dengan akses dashboard

---

## 5. MANAJEMEN ASET

### 5.1 Daftar Aset (`/assets`)

Halaman ini menampilkan seluruh data aset dalam bentuk tabel (desktop) atau kartu (mobile).

#### Kolom Informasi
- **Informasi Aset** — Nama, foto, kode aset, dan kondisi
- **Category** — Kategori aset
- **Penempatan** — Lokasi/departemen penempatan
- **Status** — Active / Maintenance / Broken
- **Valuasi** — Nilai perolehan
- **Aksi** — Tombol detail, edit, hapus

#### Filter & Pencarian

Klik tombol **Filter** untuk membuka panel filter dengan opsi:
- **Pencarian** — Cari berdasarkan nama aset, kode aset, atau lokasi
- **Status** — Semua Status / Aktif / Maintenance / Rusak
- **Kategori** — Filter berdasarkan kategori

#### Management Data

Klik tombol **Management Data** untuk membuka modal dengan opsi:
- **Ekspor Laporan** — Download PDF atau Excel (dengan filter tanggal)
- **Hapus Massal (Bulk Delete)** — Pilih beberapa aset melalui checkbox, lalu hapus sekaligus
- **Impor Data Excel** — Upload file Excel untuk menambahkan aset secara massal

### 5.2 Tambah Aset (`/assets/create`)

**Permission:** `asset.create`

Formulir pengisian data aset baru:

| Field | Tipe | Keterangan |
|-------|------|------------|
| Kode Aset | Text | Kode unik aset (misal: AST-001) |
| Nama Aset | Text | Nama aset |
| Deskripsi | Textarea | Deskripsi detail (opsional) |
| Kategori | Select | Pilih kategori |
| Biaya Perolehan | Number | Nilai perolehan dalam Rupiah |
| Tanggal Perolehan | Date | Tanggal akuisisi |
| Kondisi | Select | Grade kondisi (Baik Sekali / Baik / Cukup / Rusak) |
| Lokasi | Select | Pilih lokasi penempatan |
| Penanggung Jawab | Text | Nama PIC (opsional) |
| Foto | File | Upload foto aset (opsional) |

### 5.3 Detail Aset (`/assets/{id}`)

Menampilkan informasi lengkap aset termasuk:
- Seluruh data aset
- Foto aset (jika ada)
- QR Code / Barcode aset
- Riwayat peminjaman
- Tombol **Serah Terima** (pinjam/kembalikan langsung oleh admin)

### 5.4 Edit Aset (`/assets/{id}/edit`)

**Permission:** `asset.edit`

Formulir yang sama dengan tambah aset, dengan data yang sudah terisi. Foto lama akan diganti jika upload foto baru.

### 5.5 Hapus Aset

**Permission:** `asset.delete`

Aset hanya dapat dihapus jika:
- Tidak sedang dipinjam (`id_users` bernilai `null`)
- Tidak memiliki peminjaman dengan status `pending` atau `borrowed`

Penghapusan bersifat **permanen** (force delete), data tidak dapat dikembalikan.

### 5.6 Hapus Massal (Bulk Delete)

**Permission:** `asset.bulk-delete`

Langkah:
1. Centang checkbox pada aset yang akan dihapus (gunakan checkbox **Select All** di header tabel)
2. Klik tombol **Management Data**
3. Pada modal, klik tombol **Hapus Aset Terpilih**
4. Konfirmasi penghapusan

Validasi sama seperti hapus tunggal — aset yang sedang dipinjam akan membatalkan seluruh proses.

### 5.7 Impor Data dari Excel

**Permission:** `asset.create`

Langkah:
1. Siapkan file Excel dengan format sesuai template (unduh template melalui tautan **Download Template** di modal Management Data)
2. Klik tombol **Management Data**
3. Pada bagian **Impor Data Excel**, klik **Browse** dan pilih file
4. Klik **Proses**

Format template Excel:
| asset_code | asset_name | description | category_id | acquisition_cost | acquisition_date | condition | location_id | status |
|------------|------------|-------------|-------------|-----------------|-----------------|-----------|------------|--------|

### 5.8 Status Aset

| Status | Label | Keterangan |
|--------|-------|------------|
| `active` | Aktif ✅ | Aset dalam kondisi baik dan siap pakai |
| `maintenance` | Servis 🔧 | Aset sedang dalam perawatan |
| `broken` | Rusak ⚠️ | Aset dalam kondisi rusak dan perlu revisi |

### 5.9 Kondisi Aset (Grade)

| Kondisi | Grade |
|---------|-------|
| Baik Sekali | Grade A |
| Baik | Grade B |
| Cukup | Grade C |
| Rusak | Grade D |

> **Catatan:** Staff hanya diizinkan meminjam aset dengan kondisi Grade A (Baik Sekali) atau Grade B (Baik).

---

## 6. PEMINJAMAN ASET

### 6.1 Alur Peminjaman

```
Staff → Ajukan Peminjaman (Pending)
  ↓
Admin/Supervisor → Approve / Reject
  ↓
Approved → Aset Dipinjam (Borrowed)
  ↓
Staff/Admin → Kembalikan Aset (Returned)
```

### 6.2 Daftar Peminjaman (`/loans`)

**Permission:** `loan.view`

Menampilkan riwayat peminjaman. Administrator melihat semua data, staff hanya melihat data miliknya sendiri.

#### Status Peminjaman

| Status | Label | Keterangan |
|--------|-------|------------|
| `pending` | Menunggu 🟡 | Pengajuan menunggu persetujuan |
| `borrowed` | Dipinjam 🔵 | Aset sedang dipinjam |
| `returned` | Dikembalikan 🟢 | Aset telah dikembalikan |
| `rejected` | Ditolak 🔴 | Pengajuan ditolak (disertai alasan) |

### 6.3 Ajukan Peminjaman (`/loans/create`)

**Permission:** `loan.create`

Langkah:
1. Klik tombol **Ajukan Peminjaman**
2. Pilih aset yang tersedia (tidak sedang dipinjam, tidak dalam pengajuan)
3. Tambahkan catatan (opsional)
4. Klik **Ajukan**

Sistem akan otomatis mengirim notifikasi ke user dengan role `admin` atau `supervisor`.

### 6.4 Menyetujui Peminjaman (Approve)

**Permission:** `loan.manage`

1. Pada daftar peminjaman, cari pengajuan dengan status **Menunggu**
2. Klik tombol **Setujui** (ikon centang hijau <i class="fas fa-check"></i>)
3. Konfirmasi tindakan

Sistem akan:
- Mengubah status menjadi `borrowed`
- Mencatat tanggal pinjam
- Mengubah `id_users` pada aset (menandai aset sedang dipinjam)
- Mengirim notifikasi ke peminjam

### 6.5 Menolak Peminjaman (Reject)

**Permission:** `loan.manage`

1. Klik tombol **Tolak** (ikon silang merah <i class="fas fa-times"></i>)
2. Masukkan **alasan penolakan** (wajib diisi)
3. Klik **Ya, Tolak**

Sistem akan mengirim notifikasi penolakan disertai alasan ke peminjam.

### 6.6 Membatalkan Pengajuan (Cancel)

Hanya dapat dilakukan oleh:
- Pemilik pengajuan (staff yang mengajukan)
- Administrator

Syarat: Status harus `pending`. Data peminjaman akan dihapus permanen.

### 6.7 Pengembalian Aset (Return)

**Permission:** `loan.manage` atau pemilik pinjaman

1. Pada peminjaman dengan status **Dipinjam**, klik tombol **Kembalikan**
2. Konfirmasi tindakan

Sistem akan:
- Mengubah status menjadi `returned`
- Mencatat tanggal pengembalian
- Menghapus `id_users` pada aset (aset kembali ke inventory)
- Mengirim notifikasi ke admin

### 6.8 Serah Terima Langsung (Admin Only)

**Permission:** `loan.manage`

Admin dapat melakukan serah terima aset langsung dari halaman **Detail Aset** (`/assets/{id}`):

**Pinjamkan Aset (Serah Terima):**
1. Buka halaman detail aset
2. Pilih pengguna tujuan dari dropdown
3. Klik tombol **Serah Terima**

**Kembalikan Aset ke Inventory:**
1. Buka halaman detail aset
2. Klik tombol **Kembalikan ke Inventory**

---

## 7. DATA MASTER

### 7.1 Kategori Aset (`/categories`)

**Permission:** `master-data.view` (lihat) / `master-data.manage` (kelola)

Fitur:
- **Daftar Kategori** — Tabel dengan kolom Nama dan Deskripsi
- **Tambah Kategori** — Nama (wajib), Deskripsi (opsional)
- **Edit Kategori** — Mengubah data kategori
- **Hapus Kategori** — Menghapus kategori

### 7.2 Lokasi Aset (`/locations`)

**Permission:** `master-data.view` (lihat) / `master-data.manage` (kelola)

Fitur:
- **Daftar Lokasi** — Tabel dengan kolom Nama dan Deskripsi
- **Tambah Lokasi** — Nama (wajib), Deskripsi (opsional)
- **Edit Lokasi** — Mengubah data lokasi
- **Hapus Lokasi** — Menghapus lokasi

> **Catatan:** Kedua menu ini berada di bawah grup **Master Data** pada sidebar.

---

## 8. MANAJEMEN PENGGUNA & ROLE

### 8.1 Manajemen Pengguna (`/users`)

**Permission:** `user.manage`

Halaman ini digunakan untuk mengelola seluruh akun pengguna sistem.

#### Daftar Pengguna
Tabel menampilkan: Nama, Email, Role, dan Aksi.

#### Tambah Pengguna
Formulir:
| Field | Keterangan |
|-------|------------|
| Nama | Nama lengkap |
| Email | Alamat email (unique) |
| Role | Pilih role (admin / staff / kustom) |
| Password | Opsional, default: `password` |

#### Edit Pengguna
- Mengubah nama, email, role
- Reset password (isi hanya jika ingin mengganti)

#### Hapus Pengguna
- Akun sendiri tidak dapat dihapus
- Penghapusan bersifat permanen

### 8.2 Manajemen Role (`/roles`)

**Akses:** Admin only (`isAdmin()`)

Fitur:
- **Daftar Role** — Menampilkan semua role
- **Tambah Role** — Nama role dan deskripsi (slug dibuat otomatis)
- **Edit Role** — Ubah nama/deskripsi (slug tidak berubah untuk role `admin` dan `staff`)
- **Hapus Role** — Role sistem (`admin`/`staff`) tidak dapat dihapus

### 8.3 Pengaturan Role & Permission (`/settings/roles`)

**Akses:** Admin only (`isAdmin()`)

Halaman ini menampilkan matriks permission dalam bentuk tabel yang mengelompokkan permission berdasarkan modul:

| Grup | Permission |
|------|------------|
| **Ringkasan & Dashboard** | `dashboard.view` |
| **Manajemen Aset** | `asset.view`, `asset.create`, `asset.edit`, `asset.delete`, `asset.bulk-delete` |
| **Alur Peminjaman** | `loan.view`, `loan.create`, `loan.manage` |
| **Data Master** | `master-data.view`, `master-data.manage` |
| **Sistem & Laporan** | `user.manage`, `report.export` |

Admin dapat mencentang/menghapus centang permission untuk setiap role, lalu klik **Simpan Pengaturan**.

---

## 9. AUDIT ASET

Sistem audit aset digunakan untuk melakukan validasi fisik aset secara periodik menggunakan pemindaian barcode/QR Code.

### 9.1 Daftar Sesi Audit (`/audits`)

Menampilkan seluruh sesi audit yang pernah dibuat dengan informasi:
- Judul sesi
- Auditor (pembuat)
- Tanggal pelaksanaan
- Status (Open / Locked)

### 9.2 Membuat Sesi Audit Baru

1. Klik tombol **Mulai Audit Baru**
2. Isi **Judul Sesi** (contoh: "Audit Triwulan I 2024")
3. Pilih **Tanggal Pelaksanaan**
4. Tambahkan **Deskripsi** (opsional)
5. Klik **Buat Sesi & Mulai**

Sesi baru akan memiliki status **Open**.

### 9.3 Proses Scanning

Pada halaman scan (`/audits/{id}`):
1. Siapkan barcode/QR Code aset
2. Gunakan scanner barcode atau kamera untuk memindai
3. Setiap aset yang terpindai akan tercatat dengan status:
   - **Present** — Kode cocok dengan aset terdaftar
   - **Unexpected** — Kode dipindai tetapi tidak cocok dengan aset terdaftar
4. Aset yang tidak terpindai akan dianggap **Missing**

Sistem juga mendukung pemindaian melalui URL QR Code (otomatis mengekstrak kode dari URL).

### 9.4 Menyelesaikan Audit

1. Klik tombol **Selesaikan Audit**
2. Status sesi berubah menjadi **Locked**
3. Data scan tidak dapat diubah lagi

### 9.5 Laporan Audit

Setelah audit selesai, laporan dapat diakses melalui halaman detail yang menampilkan:
- **Found Items** — Aset yang berhasil dipindai
- **Missing Assets** — Aset terdaftar yang tidak dipindai
- **Unexpected Items** — Kode asing yang terpindai

Laporan dapat diekspor ke:
- **Excel** (`.xlsx`)
- **PDF** (`.pdf`)

---

## 10. PENGATURAN HAK AKSES (ROLE PERMISSIONS)

### 10.1 Matriks Permission

Akses halaman `/settings/roles` (Admin only) untuk mengatur hak akses setiap role.

### 10.2 Daftar Permission Lengkap

| Permission | Fungsi |
|------------|--------|
| `dashboard.view` | Melihat halaman dashboard |
| `asset.view` | Melihat daftar dan detail aset |
| `asset.create` | Menambah aset baru |
| `asset.edit` | Mengedit aset |
| `asset.delete` | Menghapus aset |
| `asset.bulk-delete` | Menghapus aset secara massal |
| `loan.view` | Melihat riwayat peminjaman |
| `loan.create` | Mengajukan peminjaman |
| `loan.manage` | Menyetujui/menolak pengembalian dan serah terima langsung |
| `master-data.view` | Melihat data kategori dan lokasi |
| `master-data.manage` | Menambah/edit/menghapus kategori dan lokasi |
| `user.manage` | Mengelola pengguna sistem |
| `report.export` | Mengekspor laporan PDF/Excel |

### 10.3 Cara Mengatur

1. Buka menu **Pengaturan Role** pada sidebar
2. Centang atau hapus centang permission untuk setiap role
3. Klik **Simpan Pengaturan** di bagian bawah halaman

---

## 11. LAPORAN & EKSPOR

### 11.1 Ekspor PDF

**Permission:** `report.export`

- Format: A4 Landscape
- Output: `laporan-inventaris-aset.pdf`
- Mendukung filter (search, category, status, range tanggal)

### 11.2 Ekspor Excel

**Permission:** `report.export`

- Format: `.xlsx`
- Output: `data-aset.xlsx`
- Mendukung filter yang sama dengan PDF

### 11.3 Template Impor Excel

Template dapat diunduh melalui tautan **Download Template** pada modal Management Data atau melalui route `/assets/import/template`.

### 11.4 Laporan Audit

- **Excel:** `Audit_{judul}.xlsx`
- **PDF:** `Audit_{judul}.pdf`

---

## 12. NOTIFIKASI

### 12.1 Notifikasi Peminjaman

Sistem akan mengirim notifikasi dalam situasi berikut:

| Event | Penerima | Channel |
|-------|----------|---------|
| Pengajuan peminjaman baru | Admin & Supervisor | Database Notification |
| Peminjaman disetujui | Peminjam | Database Notification |
| Peminjaman ditolak | Peminjam | Database Notification |
| Aset dikembalikan | Admin & Supervisor | Database Notification |

### 12.2 Menandai Notifikasi

Klik tombol **Tandai Semua Dibaca** pada halaman notifikasi untuk membersihkan notifikasi yang sudah dibaca. Notifikasi dapat diakses melalui ikon lonceng di header aplikasi.

### 12.3 Konfigurasi Email (Opsional)

Untuk mengirim notifikasi via email, konfigurasikan pengaturan SMTP di `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email@gmail.com
MAIL_PASSWORD=app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 13. PEMECAHAN MASALAH

### 13.1 Error "Target class [...] does not exist"

Jalankan perintah:
```bash
composer dump-autoload
```

### 13.2 Error saat Import Excel

Pastikan:
- File yang diupload memiliki format `.xlsx`, `.xls`, atau `.csv`
- Ukuran file maksimal 5 MB
- Kolom sesuai dengan template

### 13.3 Notifikasi Tidak Muncul

Jalankan queue worker:
```bash
php artisan queue:listen --tries=1 --timeout=0
```

Atau gunakan `composer run dev` yang sudah mencakup queue worker.

### 13.4 Halaman Error 403 (Forbidden)

User tidak memiliki permission yang diperlukan untuk mengakses halaman tersebut. Hubungi administrator untuk memberikan hak akses melalui menu **Pengaturan Role**.

### 13.5 Session Hilang / Terlogout Otomatis

- Periksa koneksi internet
- Periksa konfigurasi `SESSION_DRIVER` dan `SESSION_LIFETIME` di `.env`

### 13.6 Error SQLite "attempt to write a readonly database"

Pastikan file `database/database.sqlite` memiliki permission write:
```bash
chmod 664 database/database.sqlite
```

### 13.7 Menjalankan Test Suite

```bash
composer run test
```

Atau:
```bash
php artisan config:clear && php artisan test
```

---

## LAMPIRAN

### A. Daftar Route Utama

| Method | URI | Controller | Function |
|--------|-----|------------|----------|
| GET | `/login` | AuthController@showLogin | Halaman login |
| POST | `/login` | AuthController@login | Proses login |
| POST | `/logout` | AuthController@logout | Proses logout |
| GET | `/` | DashboardController@index | Dashboard |
| GET | `/assets` | AssetController@index | Daftar aset |
| GET | `/assets/create` | AssetController@create | Form tambah aset |
| POST | `/assets` | AssetController@store | Simpan aset baru |
| GET | `/assets/{id}` | AssetController@show | Detail aset |
| GET | `/assets/{id}/edit` | AssetController@edit | Form edit aset |
| PUT | `/assets/{id}` | AssetController@update | Update aset |
| DELETE | `/assets/{id}` | AssetController@destroy | Hapus aset |
| DELETE | `/assets/bulk-delete` | AssetController@bulkDestroy | Hapus massal |
| GET | `/loans` | AssetLoanController@index | Daftar peminjaman |
| GET | `/loans/create` | AssetLoanController@create | Form ajukan peminjaman |
| POST | `/loans` | AssetLoanController@store | Simpan pengajuan |
| POST | `/loans/{id}/approve` | AssetLoanController@approve | Setujui peminjaman |
| POST | `/loans/{id}/reject` | AssetLoanController@reject | Tolak peminjaman |
| POST | `/loans/{id}/return` | AssetLoanController@return | Kembalikan aset |
| POST | `/loans/{id}/cancel` | AssetLoanController@cancel | Batalkan pengajuan |
| GET | `/categories` | CategoryController@index | Daftar kategori |
| GET | `/locations` | LocationController@index | Daftar lokasi |
| GET | `/users` | UserController@index | Daftar pengguna |
| GET | `/roles` | RoleController@index | Daftar role |
| GET | `/settings/roles` | RolePermissionController@index | Pengaturan permission |
| GET | `/audits` | AssetAuditController@index | Daftar sesi audit |
| GET | `/audits/{id}/scan` | AssetAuditController@show | Halaman scan |
| GET | `/audits/{id}/report` | AssetAuditController@report | Laporan audit |
| GET | `/assets/export/pdf` | AssetExportController@exportPdf | Ekspor PDF |
| GET | `/assets/export/excel` | AssetExportController@exportExcel | Ekspor Excel |
| POST | `/assets/import/excel` | AssetExportController@importExcel | Impor Excel |

### B. Versi Library

| Library | Versi |
|---------|-------|
| PHP | ^8.2 |
| Laravel | ^12.0 |
| barryvdh/laravel-dompdf | ^3.1 |
| maatwebsite/excel | ^3.1 |
| simplesoftwareio/simple-qrcode | ^4.2 |
| spatie/laravel-activitylog | ^4.12 |

---

> **Dokumen ini disusun untuk membantu pengguna dalam mengoperasikan Sistem Informasi Inventaris Aset (AsetKu Enterprise).**
>
> *© 2026 - Sistem Informasi Inventaris Aset*
