<?php

namespace Database\Seeders;

use App\Models\AuditCriteriaGroup;
use App\Models\AuditCriteriaItem;
use Illuminate\Database\Seeder;

class AuditCriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. General criteria (all categories)
        $general = AuditCriteriaGroup::create([
            'name' => 'Kondisi Fisik Umum',
            'description' => 'Kriteria penilaian kondisi fisik dasar untuk semua aset',
            'category_type' => null,
        ]);
        AuditCriteriaItem::insert([
            ['id_criteria_groups' => $general->id_criteria_groups, 'name' => 'Fisik utuh/tidak cacat', 'description' => 'Tidak ada kerusakan fisik yang terlihat', 'weight' => 3],
            ['id_criteria_groups' => $general->id_criteria_groups, 'name' => 'Berfungsi normal', 'description' => 'Aset dapat beroperasi sesuai fungsi', 'weight' => 4],
            ['id_criteria_groups' => $general->id_criteria_groups, 'name' => 'Kebersihan terjaga', 'description' => 'Tidak ada kotoran atau debu berlebih', 'weight' => 1],
            ['id_criteria_groups' => $general->id_criteria_groups, 'name' => 'Label/identitas jelas', 'description' => 'Kode aset atau label inventaris terbaca', 'weight' => 1],
            ['id_criteria_groups' => $general->id_criteria_groups, 'name' => 'Kelengkapan aksesoris', 'description' => 'Semua aksesoris/cable sesuai standar', 'weight' => 2],
        ]);

        // 2. Laptop/Komputer criteria
        $laptop = AuditCriteriaGroup::create([
            'name' => 'Inspeksi Laptop/Komputer',
            'description' => 'Kriteria khusus untuk aset laptop, PC, dan notebook',
            'category_type' => 'Laptop',
        ]);
        AuditCriteriaItem::insert([
            ['id_criteria_groups' => $laptop->id_criteria_groups, 'name' => 'SSD/HDD berfungsi', 'description' => 'Storage terdeteksi dan berfungsi normal', 'weight' => 4],
            ['id_criteria_groups' => $laptop->id_criteria_groups, 'name' => 'RAM terdeteksi', 'description' => 'RAM terpasang dan dikenali sistem', 'weight' => 3],
            ['id_criteria_groups' => $laptop->id_criteria_groups, 'name' => 'Layar tidak pecah/retak', 'description' => 'Tidak ada retak, dead pixel, atau goresan', 'weight' => 4],
            ['id_criteria_groups' => $laptop->id_criteria_groups, 'name' => 'Keyboard berfungsi', 'description' => 'Semua tombol berfungsi normal', 'weight' => 2],
            ['id_criteria_groups' => $laptop->id_criteria_groups, 'name' => 'Touchpad/mouse berfungsi', 'description' => 'Pointer device berfungsi normal', 'weight' => 2],
            ['id_criteria_groups' => $laptop->id_criteria_groups, 'name' => 'Battery kondisi baik', 'description' => 'Battery tidak mengembung dan tahan >30menit', 'weight' => 3],
            ['id_criteria_groups' => $laptop->id_criteria_groups, 'name' => 'Port I/O berfungsi', 'description' => 'USB, HDMI, audio jack berfungsi', 'weight' => 2],
            ['id_criteria_groups' => $laptop->id_criteria_groups, 'name' => 'Webcam & audio', 'description' => 'Kamera dan microphone/speaker berfungsi', 'weight' => 1],
            ['id_criteria_groups' => $laptop->id_criteria_groups, 'name' => 'Charger/adaptor original', 'description' => 'Charger sesuai dan berfungsi', 'weight' => 2],
        ]);

        // 3. Furniture criteria
        $furniture = AuditCriteriaGroup::create([
            'name' => 'Inspeksi Furniture',
            'description' => 'Kriteria untuk meja, kursi, lemari, rak',
            'category_type' => 'Furniture',
        ]);
        AuditCriteriaItem::insert([
            ['id_criteria_groups' => $furniture->id_criteria_groups, 'name' => 'Struktur kokoh', 'description' => 'Tidak goyang atau retak pada struktur', 'weight' => 4],
            ['id_criteria_groups' => $furniture->id_criteria_groups, 'name' => 'Cat/lapisan tidak mengelupas', 'description' => 'Permukaan masih dalam kondisi baik', 'weight' => 2],
            ['id_criteria_groups' => $furniture->id_criteria_groups, 'name' => 'Engsel/laci berfungsi', 'description' => 'Engsel, laci, dan pintu berfungsi normal', 'weight' => 3],
            ['id_criteria_groups' => $furniture->id_criteria_groups, 'name' => 'Kaki/roda utuh', 'description' => 'Kaki atau roda dalam kondisi lengkap', 'weight' => 2],
            ['id_criteria_groups' => $furniture->id_criteria_groups, 'name' => 'Baut/sambungan kencang', 'description' => 'Tidak ada baut longgar atau sambungan renggang', 'weight' => 2],
        ]);

        // 4. Elektronik criteria
        $elektronik = AuditCriteriaGroup::create([
            'name' => 'Inspeksi Elektronik',
            'description' => 'Kriteria untuk printer, scanner, proyektor, dan perangkat elektronik',
            'category_type' => 'Elektronik',
        ]);
        AuditCriteriaItem::insert([
            ['id_criteria_groups' => $elektronik->id_criteria_groups, 'name' => 'Menyala normal', 'description' => 'Perangkat dapat menyala dengan normal', 'weight' => 4],
            ['id_criteria_groups' => $elektronik->id_criteria_groups, 'name' => 'Kabel power utuh', 'description' => 'Kabel tidak terkelupas atau putus', 'weight' => 3],
            ['id_criteria_groups' => $elektronik->id_criteria_groups, 'name' => 'Fungsi operasional', 'description' => 'Semua fungsi berjalan normal', 'weight' => 4],
            ['id_criteria_groups' => $elektronik->id_criteria_groups, 'name' => 'Tidak ada suara abnormal', 'description' => 'Tidak ada suara bising atau getaran aneh', 'weight' => 2],
            ['id_criteria_groups' => $elektronik->id_criteria_groups, 'name' => 'Suhu operasi normal', 'description' => 'Tidak overheat dalam penggunaan normal', 'weight' => 2],
        ]);

        // 5. Kendaraan criteria
        $kendaraan = AuditCriteriaGroup::create([
            'name' => 'Inspeksi Kendaraan',
            'description' => 'Kriteria untuk mobil, motor, dan kendaraan operasional',
            'category_type' => 'Kendaraan',
        ]);
        AuditCriteriaItem::insert([
            ['id_criteria_groups' => $kendaraan->id_criteria_groups, 'name' => 'Mesin menyala normal', 'description' => 'Mesin dapat dinyalakan dan idling normal', 'weight' => 4],
            ['id_criteria_groups' => $kendaraan->id_criteria_groups, 'name' => 'Body kendaraan utuh', 'description' => 'Tidak ada penyok atau karat parah', 'weight' => 3],
            ['id_criteria_groups' => $kendaraan->id_criteria_groups, 'name' => 'Rem berfungsi baik', 'description' => 'Sistem pengereman berfungsi normal', 'weight' => 5],
            ['id_criteria_groups' => $kendaraan->id_criteria_groups, 'name' => 'Lampu sein/rem menyala', 'description' => 'Semua lampu indikator berfungsi', 'weight' => 2],
            ['id_criteria_groups' => $kendaraan->id_criteria_groups, 'name' => 'Ban kondisi layak', 'description' => 'Tekanan dan ketebalan ban masih memadai', 'weight' => 3],
            ['id_criteria_groups' => $kendaraan->id_criteria_groups, 'name' => 'STNK/Buku pemilik tersedia', 'description' => 'Dokumen kendaraan lengkap dan berlaku', 'weight' => 3],
        ]);
    }
}
