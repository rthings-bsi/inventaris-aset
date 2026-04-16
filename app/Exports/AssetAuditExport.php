<?php

namespace App\Exports;

use App\Models\Asset;
use App\Models\AssetAudit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class AssetAuditExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $audit;

    public function __construct(AssetAudit $audit)
    {
        $this->audit = $audit;
    }

    public function collection()
    {
        $scannedItems = $this->audit->items()->with('asset')->get();
        $scannedIds = $scannedItems->pluck('asset_id')->filter()->toArray();

        // All assets that should be there
        $missingAssets = Asset::whereNotIn('id', $scannedIds)->get();

        $data = collect([]);

        // 1. Found Items
        foreach ($scannedItems as $item) {
            $data->push([
                'code' => $item->scanned_code,
                'name' => $item->asset ? $item->asset->asset_name : '(Tidak Terdaftar)',
                'system_status' => $item->asset ? $item->asset->status : '-',
                'audit_status' => $item->asset ? 'Ditemukan' : 'Tidak Terduga',
                'scanned_at' => $item->scanned_at,
            ]);
        }

        // 2. Missing Items
        foreach ($missingAssets as $asset) {
            $data->push([
                'code' => $asset->asset_code,
                'name' => $asset->asset_name,
                'system_status' => $asset->status,
                'audit_status' => 'Hilang / Tidak Terpindai',
                'scanned_at' => '-',
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Kode Aset',
            'Nama Aset',
            'Status di Sistem',
            'Hasil Audit',
            'Waktu Pindai',
        ];
    }

    public function map($row): array
    {
        return [
            $row['code'],
            $row['name'],
            $row['system_status'],
            $row['audit_status'],
            $row['scanned_at'],
        ];
    }

    public function title(): string
    {
        return 'Hasil Audit ' . $this->audit->title;
    }
}
