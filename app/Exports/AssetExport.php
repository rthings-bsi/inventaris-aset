<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssetExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function map($asset): array
    {
        return [
            $asset->asset_code,
            $asset->asset_name,
            $asset->category ? $asset->category->category_name : '-',
            $asset->condition,
            $asset->status,
            $asset->location ? $asset->location->location_name : '-',
            $asset->acquisition_cost,
            $asset->acquisition_date ? \Carbon\Carbon::parse($asset->acquisition_date)->format('Y-m-d') : '',
            $asset->user ? $asset->user->name : 'Tidak ada',
            $asset->description
        ];
    }

    public function headings(): array
    {
        return [
            'Kode Aset',
            'Nama Aset',
            'Category',
            'Condition',
            'Status',
            'Location',
            'Nilai Perolehan',
            'Tanggal Perolehan',
            'Penanggung Jawab',
            'Description'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
