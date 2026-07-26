# Graph Report - inventaris_aset  (2026-07-26)

## Corpus Check
- 136 files · ~133,202 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 654 nodes · 892 edges · 106 communities (101 shown, 5 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 27 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `c2e2a4ae`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Illuminate\Http\Request
- Illuminate\Database\Eloquent\Model
- Asset
- composer.json
- AssetAuditDataExport
- AssetLoan
- AssetAudit
- User
- scripts
- devDependencies
- Category
- 5. MANAJEMEN ASET
- 2.2 Langkah Instalasi
- BUKU MANUAL PENGGUNA
- 6. PEMINJAMAN ASET
- RolePermission.php
- Illuminate\Database\Migrations\Migration
- 13. PEMECAHAN MASALAH
- 8.1 Manajemen Pengguna (`/users`)
- 4.1 Komponen Dashboard
- Basecamp Aset (Inventaris Cerdas)
- 9. AUDIT ASET
- UserFactory
- AppServiceProvider
- 11. LAPORAN & EKSPOR
- 1. PENDAHULUAN
- 10. PENGATURAN HAK AKSES (ROLE PERMISSIONS)
- 12. NOTIFIKASI
- 3. AUTENTIKASI & MANAJEMEN AKUN
- CreateActivityLogTable
- ExampleTest
- AGENTS.md

## God Nodes (most connected - your core abstractions)
1. `Asset` - 51 edges
2. `User` - 27 edges
3. `AssetAudit` - 26 edges
4. `AssetRepair` - 24 edges
5. `AssetLoan` - 22 edges
6. `AssetAuditController` - 17 edges
7. `Category` - 17 edges
8. `BUKU MANUAL PENGGUNA` - 17 edges
9. `Controller` - 16 edges
10. `Location` - 13 edges

## Surprising Connections (you probably didn't know these)
- `AssetAuditController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/AssetAuditController.php → app/Http/Controllers/Controller.php
- `AssetController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/AssetController.php → app/Http/Controllers/Controller.php
- `AssetLoanController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/AssetLoanController.php → app/Http/Controllers/Controller.php
- `AuditReportController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/AuditReportController.php → app/Http/Controllers/Controller.php
- `CategoryController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/CategoryController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (106 total, 5 thin omitted)

### Community 0 - "Illuminate\Http\Request"
Cohesion: 0.06
Nodes (12): AssetAuditDataController, AssetExportController, AssetRepairController, AuditReportController, AuthController, Controller, NotificationController, CheckPermission (+4 more)

### Community 1 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.06
Nodes (13): LocationController, RoleController, AuditCriteriaItem, Location, Role, AuditCriteriaSeeder, Illuminate\Database\Eloquent\Model, Illuminate\Foundation\Testing\RefreshDatabase (+5 more)

### Community 2 - "Asset"
Cohesion: 0.06
Nodes (13): AssetController, StoreAssetRequest, UpdateAssetRequest, AssetImport, Asset, Illuminate\Database\Eloquent\SoftDeletes, Illuminate\Foundation\Http\FormRequest, Illuminate\Support\Collection (+5 more)

### Community 3 - "composer.json"
Cohesion: 0.04
Nodes (44): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+36 more)

### Community 4 - "AssetAuditDataExport"
Cohesion: 0.10
Nodes (13): AssetAuditDataExport, AssetAuditExport, AssetExport, AssetTemplateExport, Maatwebsite\Excel\Concerns\Exportable, Maatwebsite\Excel\Concerns\FromArray, Maatwebsite\Excel\Concerns\FromCollection, Maatwebsite\Excel\Concerns\FromQuery (+5 more)

### Community 5 - "AssetLoan"
Cohesion: 0.08
Nodes (7): AssetLoanController, AssetLoan, AssetLoanNotification, Illuminate\Bus\Queueable, Illuminate\Notifications\Notification, Illuminate\Support\Facades\Notification, AssetLoanTest

### Community 6 - "AssetAudit"
Cohesion: 0.12
Nodes (4): AssetAuditController, AssetAudit, AssetAuditItem, AuditCriteriaGroup

### Community 7 - "User"
Cohesion: 0.12
Nodes (9): UserController, User, DatabaseSeeder, RoleSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Seeder, Illuminate\Foundation\Auth\User (+1 more)

### Community 8 - "scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 9 - "devDependencies"
Cohesion: 0.10
Nodes (19): axios, concurrently, laravel-vite-plugin, devDependencies, axios, concurrently, laravel-vite-plugin, tailwindcss (+11 more)

### Community 10 - "Category"
Cohesion: 0.17
Nodes (3): CategoryController, DashboardController, Category

### Community 11 - "5. MANAJEMEN ASET"
Cohesion: 0.15
Nodes (13): 5.1 Daftar Aset (`/assets`), 5.2 Tambah Aset (`/assets/create`), 5.3 Detail Aset (`/assets/{id}`), 5.4 Edit Aset (`/assets/{id}/edit`), 5.5 Hapus Aset, 5.6 Hapus Massal (Bulk Delete), 5.7 Impor Data dari Excel, 5.8 Status Aset (+5 more)

### Community 12 - "2.2 Langkah Instalasi"
Cohesion: 0.18
Nodes (11): 2.1 Persyaratan Server, 2.2 Langkah Instalasi, 2.3 Akun Default, 2.4 Menjalankan Test, 2. PERSYARATAN SISTEM & INSTALASI, A. Clone Repository, B. Install Dependencies, C. Konfigurasi Environment (+3 more)

### Community 13 - "BUKU MANUAL PENGGUNA"
Cohesion: 0.20
Nodes (9): 7.1 Kategori Aset (`/categories`), 7.2 Lokasi Aset (`/locations`), 7. DATA MASTER, A. Daftar Route Utama, B. Versi Library, BUKU MANUAL PENGGUNA, DAFTAR ISI, LAMPIRAN (+1 more)

### Community 14 - "6. PEMINJAMAN ASET"
Cohesion: 0.20
Nodes (10): 6.1 Alur Peminjaman, 6.2 Daftar Peminjaman (`/loans`), 6.3 Ajukan Peminjaman (`/loans/create`), 6.4 Menyetujui Peminjaman (Approve), 6.5 Menolak Peminjaman (Reject), 6.6 Membatalkan Pengajuan (Cancel), 6.7 Pengembalian Aset (Return), 6.8 Serah Terima Langsung (Admin Only) (+2 more)

### Community 16 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.28
Nodes (3): AddEventColumnToActivityLogTable, AddBatchUuidColumnToActivityLogTable, Illuminate\Database\Migrations\Migration

### Community 17 - "13. PEMECAHAN MASALAH"
Cohesion: 0.25
Nodes (8): 13.1 Error "Target class [...] does not exist", 13.2 Error saat Import Excel, 13.3 Notifikasi Tidak Muncul, 13.4 Halaman Error 403 (Forbidden), 13.5 Session Hilang / Terlogout Otomatis, 13.6 Error SQLite "attempt to write a readonly database", 13.7 Menjalankan Test Suite, 13. PEMECAHAN MASALAH

### Community 18 - "8.1 Manajemen Pengguna (`/users`)"
Cohesion: 0.25
Nodes (8): 8.1 Manajemen Pengguna (`/users`), 8.2 Manajemen Role (`/roles`), 8.3 Pengaturan Role & Permission (`/settings/roles`), 8. MANAJEMEN PENGGUNA & ROLE, Daftar Pengguna, Edit Pengguna, Hapus Pengguna, Tambah Pengguna

### Community 19 - "4.1 Komponen Dashboard"
Cohesion: 0.29
Nodes (7): 4.1 Komponen Dashboard, 4.2 Permission yang Diperlukan, 4. DASHBOARD, Grafik Distribusi Kategori (Donut Chart), Grafik Mutu Fisik (Bar Chart), Kartu Statistik (5 Kartu), Tabel Inventaris Terbaru

### Community 20 - "Basecamp Aset (Inventaris Cerdas)"
Cohesion: 0.29
Nodes (6): Basecamp Aset (Inventaris Cerdas), Cara Instalasi, Fitur Utama, Menjalankan Test, Standar Kode (Refactored), Teknologi

### Community 21 - "9. AUDIT ASET"
Cohesion: 0.33
Nodes (6): 9.1 Daftar Sesi Audit (`/audits`), 9.2 Membuat Sesi Audit Baru, 9.3 Proses Scanning, 9.4 Menyelesaikan Audit, 9.5 Laporan Audit, 9. AUDIT ASET

### Community 22 - "UserFactory"
Cohesion: 0.47
Nodes (3): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, static

### Community 24 - "11. LAPORAN & EKSPOR"
Cohesion: 0.40
Nodes (5): 11.1 Ekspor PDF, 11.2 Ekspor Excel, 11.3 Template Impor Excel, 11.4 Laporan Audit, 11. LAPORAN & EKSPOR

### Community 25 - "1. PENDAHULUAN"
Cohesion: 0.40
Nodes (5): 1.1 Tentang Sistem, 1.2 Fitur Utama, 1.3 Teknologi yang Digunakan, 1.4 Struktur Role Pengguna, 1. PENDAHULUAN

### Community 26 - "10. PENGATURAN HAK AKSES (ROLE PERMISSIONS)"
Cohesion: 0.50
Nodes (4): 10.1 Matriks Permission, 10.2 Daftar Permission Lengkap, 10.3 Cara Mengatur, 10. PENGATURAN HAK AKSES (ROLE PERMISSIONS)

### Community 27 - "12. NOTIFIKASI"
Cohesion: 0.50
Nodes (4): 12.1 Notifikasi Peminjaman, 12.2 Menandai Notifikasi, 12.3 Konfigurasi Email (Opsional), 12. NOTIFIKASI

### Community 28 - "3. AUTENTIKASI & MANAJEMEN AKUN"
Cohesion: 0.50
Nodes (4): 3.1 Halaman Login, 3.2 Logout, 3.3 Session, 3. AUTENTIKASI & MANAJEMEN AKUN

## Knowledge Gaps
- **140 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+135 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **5 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Asset` connect `Asset` to `Illuminate\Http\Request`, `Illuminate\Database\Eloquent\Model`, `AssetAuditDataExport`, `AssetLoan`, `AssetAudit`, `User`, `Category`?**
  _High betweenness centrality (0.055) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `Illuminate\Database\Eloquent\Model`, `AssetLoan`, `RolePermission.php`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Why does `AssetLoan` connect `AssetLoan` to `Illuminate\Database\Eloquent\Model`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._
- **Are the 9 inferred relationships involving `Asset` (e.g. with `.collection()` and `.exportExcel()`) actually correct?**
  _`Asset` has 9 INFERRED edges - model-reasoned connections that need verification._
- **Are the 2 inferred relationships involving `AssetAudit` (e.g. with `.collection()` and `.index()`) actually correct?**
  _`AssetAudit` has 2 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _140 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Illuminate\Http\Request` be split into smaller, more focused modules?**
  _Cohesion score 0.06334841628959276 - nodes in this community are weakly interconnected._