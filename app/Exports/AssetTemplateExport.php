<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['AST-001', 'Laptop Lenovo Thinkpad', 'Electronics', 'IT Room', 'John Doe', 'Good', 'active', 12000000, '2026-01-15', 'Used by Lead Dev'],
            ['AST-002', 'Office Desk Wood', 'Furniture', 'Main Office', 'Jane Smith', 'Good', 'active', 1500000, '2025-11-20', ''],
            ['AST-003', 'Projector Epson', 'Electronics', 'Meeting Room', 'Admin', 'Broken', 'broken', 5500000, '2024-05-10', 'Needs lamp replacement']
        ];
    }

    public function headings(): array
    {
        return [
            'asset_code',
            'asset_name',
            'category',
            'location',
            'person_in_charge',
            'condition',
            'status',
            'acquisition_cost',
            'acquisition_date',
            'description'
        ];
    }
}
