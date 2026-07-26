<?php

namespace App\Exports;

use App\Models\AssetAudit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class AssetAuditDataExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected string $period;
    protected string $startDate;
    protected string $endDate;
    protected string $label;

    public function __construct(string $period)
    {
        $this->period = $period;
        $now = Carbon::now();

        switch ($period) {
            case 'weekly':
                $this->startDate = $now->startOfWeek()->format('Y-m-d');
                $this->endDate = $now->endOfWeek()->format('Y-m-d');
                $this->label = 'Minggu Ini';
                break;
            case 'monthly':
                $this->startDate = $now->startOfMonth()->format('Y-m-d');
                $this->endDate = $now->endOfMonth()->format('Y-m-d');
                $this->label = 'Bulan Ini';
                break;
            case 'yearly':
                $this->startDate = $now->startOfYear()->format('Y-m-d');
                $this->endDate = $now->endOfYear()->format('Y-m-d');
                $this->label = 'Tahun Ini';
                break;
            default:
                $this->startDate = $now->startOfMonth()->format('Y-m-d');
                $this->endDate = $now->endOfMonth()->format('Y-m-d');
                $this->label = 'Bulan Ini';
        }
    }

    public function collection()
    {
        $audits = AssetAudit::with(['items.asset.category', 'items.asset.location', 'creator'])
            ->whereBetween('audit_date', [$this->startDate, $this->endDate])
            ->latest('audit_date')
            ->get();

        $rows = collect([]);

        foreach ($audits as $audit) {
            $items = $audit->items;

            if ($items->isEmpty()) {
                $rows->push([
                    'audit_title' => $audit->title,
                    'audit_date' => $audit->audit_date->format('d/m/Y'),
                    'audit_type' => $audit->audit_type_label,
                    'auditor' => $audit->creator?->name ?? '-',
                    'status_sesi' => $audit->status === 'open' ? 'Open' : 'Selesai',
                    'asset_code' => '-',
                    'asset_name' => '-',
                    'category' => '-',
                    'condition_grade' => '-',
                    'checklist_status' => '-',
                    'scanned_at' => '-',
                    'location' => '-',
                ]);
            }

            foreach ($items as $item) {
                $rows->push([
                    'audit_title' => $audit->title,
                    'audit_date' => $audit->audit_date->format('d/m/Y'),
                    'audit_type' => $audit->audit_type_label,
                    'auditor' => $audit->creator?->name ?? '-',
                    'status_sesi' => $audit->status === 'open' ? 'Open' : 'Selesai',
                    'asset_code' => $item->scanned_code ?? '-',
                    'asset_name' => $item->asset?->asset_name ?? '(Tidak Terdaftar)',
                    'category' => $item->asset?->category?->category_name ?? '-',
                    'condition_grade' => $item->condition_grade ?? '-',
                    'checklist_status' => $item->checklist_status,
                    'scanned_at' => $item->scanned_at ? Carbon::parse($item->scanned_at)->format('d/m/Y H:i') : '-',
                    'location' => $item->asset?->location?->location_name ?? '-',
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Judul Sesi Audit',
            'Tanggal Audit',
            'Tipe Audit',
            'Auditor',
            'Status Sesi',
            'Kode Aset',
            'Nama Aset',
            'Kategori',
            'Grade Kondisi',
            'Status Checklist',
            'Waktu Pindai',
            'Lokasi',
        ];
    }

    public function map($row): array
    {
        return [
            $row['audit_title'],
            $row['audit_date'],
            $row['audit_type'],
            $row['auditor'],
            $row['status_sesi'],
            $row['asset_code'],
            $row['asset_name'],
            $row['category'],
            $row['condition_grade'],
            $row['checklist_status'],
            $row['scanned_at'],
            $row['location'],
        ];
    }

    public function title(): string
    {
        return "Data Audit - {$this->label}";
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
