<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Exports\AssetExport;
use App\Imports\AssetImport;
use App\Exports\AssetTemplateExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AssetExportController extends Controller
{
    /**
     * Export all assets to PDF
     */
    public function exportPdf(Request $request)
    {
        $assets = Asset::with(['category', 'location'])
            ->filter(request(['search', 'category', 'status', 'start_date', 'end_date']))
            ->latest()
            ->get();
        
        $pdf = Pdf::loadView('assets.pdf', compact('assets'))->setPaper('a4', 'landscape');
        return $pdf->stream('laporan-inventaris-aset.pdf');
    }

    /**
     * Export all assets to Excel
     */
    public function exportExcel(Request $request)
    {
        $query = Asset::with(['category', 'location'])
            ->filter(request(['search', 'category', 'status', 'start_date', 'end_date']));

        return Excel::download(new AssetExport($query->latest()), 'data-aset.xlsx');
    }

    /**
     * Download standard template for Excel Import
     */
    public function templateExcel()
    {
        return Excel::download(new AssetTemplateExport, 'template_import_assets.xlsx');
    }

    /**
     * Import assets from Excel
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120'
        ]);

        try {
            Excel::import(new AssetImport, $request->file('file_excel'));
            return redirect()->route('assets.index')->with('success', 'Data aset berhasil diimpor dari Excel!');
        } catch (\Exception $e) {
            return redirect()->route('assets.index')->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}
